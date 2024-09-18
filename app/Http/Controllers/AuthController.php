<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Country;
use App\Mail\ResetPasswordEmail;
use App\Models\CustomerAddress;
use App\Models\OrderItem;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\str;

class AuthController extends Controller
{
    public function login()
    {
        return view('front.account.login');
    }


    public function register()
    {
        return view('front.account.register');
    }

    public function processRegister(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|',
        ]);

        if ($validator->passes()) {

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->save();

            Session::flash('Success', 'You Have Been Register Successfully');

            return response()->json([
                'status' => true,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if ($validator->passes()) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
                // Redirect to the intended URL if set, otherwise to the profile route
                if (Session::has('url.intended')) {
                    $url = Session::pull('url.intended'); // Retrieve and clear the intended URL in one step
                    return redirect()->to($url);
                }
                return redirect()->route('account.profile');
            } else {
                // Flash error message and redirect back to login
                return redirect()->route('account.login')
                    ->withInput($request->only('email'))
                    ->with('error', 'Either email or password is incorrect.');
            }
        } else {
            // Redirect back with validation errors and input
            return redirect()->route('account.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('account.login')
            ->with('Success', 'You successfully Logged Out !');
    }

    public function profile()
    {
        $userId = Auth::user()->id;
        $countries = Country::orderBy('name', 'ASC')->get();
        $user = User::find($userId);
        $address = CustomerAddress::where('user_id', $userId)->first();

        return view('front.account.profile', [
            'user' => $user,
            'countries' => $countries,
            'address' => $address
        ]);
    }

    public function updateProfile(Request $request)
    {
        $userId = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $userId,
            'phone' => 'required',
        ]);

        if ($validator->passes()) {
            $user = User::find($userId);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();

            Session::flash('success', 'Profile Updated Successfullly');
            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function updateAddress(Request $request)
    {
        $userId = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:5',
            'last_name' => 'required',
            'email' => 'required|email',
            'country_id' => 'required',
            'address' => 'required|min:14',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required',
        ]);

        if ($validator->passes()) {
            CustomerAddress::updateOrCreate(
                ['user_id' => $userId],
                [
                    'user_id' => $userId,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'country_id' => $request->country_id,
                    'address' => $request->address,
                    'apartment' => $request->apartment,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip,
                    'mobile' => $request->mobile,
                ]
            );
            Session::flash('success', 'Address Updated Successfullly');

            return response()->json([
                'status' => true,
                'message' => 'Address updated successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function orders()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->orderby('created_at', 'DESC')->get();
        $data['orders'] = $orders;
        return view('front.account.order', $data);
    }

    public function orderDetail($id)
    {
        $data = [];
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)
            ->where('id', $id)->first();

        $orderItems  = OrderItem::where('order_id', $id)->get();
        $data['orderItems'] = $orderItems;

        $orderItemsCount  = OrderItem::where('order_id', $id)->count();
        $data['orderItemsCount'] = $orderItemsCount;

        $data['order'] = $order;
        return view('front.account.order-detail', $data);
    }

    public function wishlist()
    {
        $wishlists = Wishlist::where('user_id', Auth::user()->id)->with('product')->get();
        $data['wishlists'] = $wishlists;
        return view('front.account.wishlist', $data);
    }

    public function removeproductfromwishlist(request $request)
    {
        $wishlists = Wishlist::where('user_id', Auth::user()->id)->orwhere('product_id', $request->id)->first();
        if ($wishlists == null) {
            Session::flash('error', 'Product Already Removed');
            return response()->json([
                'status' => true,
            ]);
        } else {
            Wishlist::where('user_id', Auth::user()->id)->orwhere('product_id', $request->id)->delete();
            Session::flash('success', 'Product Removed Successfuly');
            return response()->json([
                'status' => true,
            ]);
        }
    }
    public function ShowchangepasswordForm()
    {
        return view('front.account.change-password');
    }
    
    public function Changepassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ]);
    
        if ($validator->passes()) {
            $user = User::select('id', 'password')->where('id', Auth::user()->id)->first();
    
            if (!Hash::check($request->old_password, $user->password)) {
                Session::flash('error', 'Your old password is incorrect. Please try again.');
                return response()->json([
                    'status' => false,
                    'message' => 'Old password is incorrect',
                ]);
            }
    
            User::where('id', $user->id)->update([
                'password' => Hash::make($request->new_password)
            ]);
    
            Session::flash('success', 'You have successfully changed your password.');
            return response()->json([
                'status' => true,
                'message' => 'Password changed successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }
    
    public function forgotPassword()
    {
        return view('front.account.forgot-password');
    }
    
    public function ProcessForgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);
    
        if ($validator->fails()) {
            return redirect()->route('front.forgotPassword')->withInput()->withErrors($validator);
        }
    
        $token = Str::random(60);
    
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
    
        DB::table('password_reset_tokens')->insert([
            "email" => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);
    
        //send Email
        $user = User::where('email', $request->email)->first();
        $formData = [
            'token' => $token,
            'user' => $user,
            'mailSubject' => 'You have requested to reset your password'
        ];
        Mail::to($request->email)->send(new ResetPasswordEmail($formData));
    
        return redirect()->route('front.forgotPassword')->with('success','Please check your inbox to reset your password.');
    }
    
    public function resetPassword($token)
    {
        $tokenexists = DB::table('password_reset_tokens')->where('token', $token)->first();
    
        if ($tokenexists == null) {
            return redirect()->route('front.forgotPassword')->with('error', 'Invalid request');
        }
    
        return view('front.account.reset-password', [
            'token' => $token
        ]);
    }
    
    public function processResetPassword(Request $request)
    {
        $token = $request->token;
        $tokenobj = DB::table('password_reset_tokens')->where('token', $token)->first();
    
        if ($tokenobj == null) {
            return redirect()->route('front.forgotPassword')->with('error', 'Invalid request');
        }
    
        $user = User::where('email', $tokenobj->email)->first();
    
        $validator = Validator::make($request->all(), [
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password'
        ]);
    
        if ($validator->fails()) {
            return redirect()->route('front.resetPassword', ['token' => $token])->withInput()->withErrors($validator);
        }
    
        User::where('id', $user->id)->update([
            'password' => Hash::make($request->new_password)
        ]);
    
        DB::table('password_reset_tokens')->where('email', $user->email)->delete();
    
        return redirect()->route('account.login')->with('success','You have successfully updated your password.');
    }
    
}

