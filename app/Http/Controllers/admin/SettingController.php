<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use  Illuminate\Support\Facades\hash;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function  showChangePasswordForm()
    {
        return view('admin.change-password');
    }

    public function processChangePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password'

        ]);

        $id = Auth::guard('admin')->user()->id;

        $admin = User::where('id',$id)->first();

        if ($validator->passes()) {
            if (!Hash::check($request->old_password, $admin->password)) {
              $request->session()->flash('error','Your old Password is Inccorect, please try again.');
                return response()->json([
                    'status' => true,
                ]);

            }
          
            User::where('id',$id)->update([
                'password' => hash::make($request->new_password),
            ]);
            $request->session()->flash('success','You Have Successfully Change Your Password');
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
}
