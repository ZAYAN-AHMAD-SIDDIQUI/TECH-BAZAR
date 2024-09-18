<?php

use App\Mail\OrderEmail;
use App\Models\Category;
use App\Models\Order;
use App\Models\Country;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Mail;

function getCategories()
{
  return Category::orderby('name', 'ASC')
    ->with('sub_category')
    ->orderby('id', 'DESC')
    ->where('status', 1)
    ->where('showHome', 'Yes')
    ->get();
}

function getProductImage($productId)
{
  return ProductImage::where('product_id', $productId)->first();
};

function orderEmail($orderId, $userType = "customer")
{
  // Retrieve the order along with its items
  $order = Order::where('id', $orderId)->with('items')->first();

  if ($userType == 'customer') {
    $subject = 'Thanks For Your Order';
    $email = $order->email;
  } else {
    $subject = 'You Have Received An Order';
    $email = "junizzaswer@gmail.com";
  }


  $mailData = [
    'subject' => $subject,
    'order' => $order,
    'userType' => $userType,
  ];

  Mail::to($email)->send(new OrderEmail($mailData));
  // dd($order);
  //  dd($mailData);

}

function getCountryInfo($id)
{
  return Country::where('id', $id)->first();
}
