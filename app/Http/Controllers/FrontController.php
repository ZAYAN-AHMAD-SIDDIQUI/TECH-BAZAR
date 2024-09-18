<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use App\Models\page;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\url;


class FrontController extends Controller
{
    public function index()
    {
        $products = Product::where('is_featured', 'Yes')
            ->orderby('id', 'DESC')
            ->take(12)
            ->where('status', 1)->get();
        $data['featuredProducts'] = $products;

        $latestProducts = Product::orderby('id', 'DESC')
            ->where('status', 1)
            ->take(12)->get();
        $data['latestProducts'] = $latestProducts;


        $trendProducts = Product::orderby('id', 'ASC')
            ->where('status', 1)
            ->take(12)->get();
        $data['trendProducts'] = $trendProducts;

        //For 4 words Title for featured products
        foreach ($products as $product) {
            $product->short_title = implode(' ', array_slice(explode(' ', $product->title), 0, 3));
        }

        foreach ($trendProducts as $product) {
            $product->short_title = implode(' ', array_slice(explode(' ', $product->title), 0, 3));
        }

        //For 4 words Title for latest products
        foreach ($latestProducts as $product) {
            $product->short_title = implode(' ', array_slice(explode(' ', $product->title), 0, 3));
        }
        return view('front.index', $data);
    }

    public function addToWishlist(Request $request)
    {
        if (Auth::check() == false) {

            Session(['url.intended' => URL::previous()]);

            return response()->json([
                'status' => false
            ]);
        }

        $product = Product::where('id', $request->id)->first();
        if ($product == null) {
            return response()->json([
                'status' => true,
                'message' => '<div class="alert alert-danger">Product Not Found</div>'
            ]);
        }
        Wishlist::UpdateOrCreate(
            [
                'user_id' => Auth::user()->id,
                'product_id' => $request->id
            ],
            [
                'user_id' => Auth::user()->id,
                'product_id' => $request->id
            ]
        );

        // $wishlist = new Wishlist;
        // $wishlist->user_id = Auth::user()->id;
        // $wishlist->product_id = $request->id;
        // $wishlist->save();


        return response()->json([
            'status' => true,
            'message' => '<div class="alert alert-success"><strong>"' . $product->title . '"</strong> Added In Your Wishlist</div>'
        ]);
    }
    public function page($slug)
    {
        $page = Page::where('slug', $slug)->first();
    }

    public function contactUs()
    {
        return view('front.contact-us');
    }


    public function sendContactEmail(Request $request)
    {
        $email = 'ghazifayaiz123@gmail.com';
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:4',
            'email' => 'required|email',
            'subject' => 'required|min:10'
        ]);

        if ($validator->passes()) {
            //send email
            $mailData = [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
                'mail_subject' => 'You have recieved A contact Email'

            ];
            Mail::to($email)->send(new ContactEmail($mailData));
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }
}
