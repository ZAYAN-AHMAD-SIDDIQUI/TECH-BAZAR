<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\CustomerAddress;
use App\Models\ShippingCharge;
use App\Models\DiscountCoupon;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;



class CartController extends Controller
{
    public function addToCart(Request $request)
    {

        $product = Product::with('product_images')->find($request->id);

        if ($product == null) {
            return response()->json([
                'status' => false,
                'message' => 'Record Not Found '
            ]);
        }

        if (cart::count() > 0) {
            //echo "Product already in cart";
            // Products found in cart
            // Check if this product already in the cart
            // Return as message that product already added in your cart 
            // if  product not found in the cart, then add product in cart

            $cartcontent = cart::content();
            $productAlreadyExist = false;

            foreach ($cartcontent as $item) {
                if ($item->id == $product->id) {
                    $productAlreadyExist = true;
                }
            }

            if ($productAlreadyExist == false) {
                Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);
                $status = true;
                $message = '<strong>' . $product->title . '</strong> Added to Cart Successfully';
                Session::flash('success', $message);
            } else {
                $status = false;
                $message =  $product->title . ' Already Added to Cart Successfully';
            }
        } else {
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);
            $status = true;
            $message = '<strong>' . $product->title . '</strong> Added to Cart Successfully';
            Session::flash('success', $message);
        }

        return response()->json([
            'status' => $status,
            'message' =>     $message
        ]);
    }

    public function cart()
    {
        $cartContent = cart::content();
        $data['cartContent'] = $cartContent;
        return view('front.cart', $data);
    }

    public function updateCart(Request $request)
    {
        $rowId = $request->rowId;
        $qty = $request->qty;

        // Retrieve the cart item using the rowId
        $itemInfo = Cart::get($rowId);
        $productId = $itemInfo->id; // Assuming this is the actual product ID

        // Retrieve the product from the database using the product ID
        $product = Product::find($productId);

        // Check if the product exists
        if (!$product) {
            $message = 'Product not found';
            Session::flash('error', $message);
            return response()->json([
                'status' => false,
                'message' => $message,
            ]);
        }

        // Check if stock quantity is available
        if ($product->track_qty == 'Yes') {
            if ($qty <= $product->qty) {
                Cart::update($rowId, $qty);
                $message = 'Cart Updated Successfully';
                Session::flash('success', $message);
                $status = true;
            } else {
                $message = 'Requested qty(' . $qty . ') not available in stock';
                Session::flash('error', $message);
                $status = false;
            }
        } else {
            Cart::update($rowId, $qty);
            $message = 'Cart Updated Successfully';
            Session::flash('success', $message);
            $status = true;
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }


    public function deleteItem(Request $request)
    {
        $rowId = $request->rowId;
        $itemInfo = Cart::get($rowId);

        if (!$itemInfo) {
            $errorMessage = 'Item Not Found In Cart';
            Session::flash('error', $errorMessage);
            return response()->json([
                'status' => false,
                'message' => $errorMessage,
            ]);
        }

        Cart::remove($rowId);
        $message = 'Item Removed From Cart Successfully';
        Session::flash('success', $message);

        return response()->json([
            'status' => true,
            'message' => $message,
        ]);
    }

    public function checkout()
    {
        $discount = 0;
        // If cart is empty, redirect to cart page
        if (Cart::count() == 0) {
            return redirect()->route('front.cart');
        }

        if (!Auth::check()) {
            // Store the current URL in the session if it isn't already stored
            if (!Session::has('url.intended')) {
                Session::put('url.intended', URL::current());
            }
            return redirect()->route('account.login');
        }

        // Clear the intended URL after login
        Session::forget('url.intended');

        $customeraddress = CustomerAddress::where('user_id', Auth::user()->id)->first();

        $countries = Country::orderby('name', 'ASC')->get();
        $subTotal =  Cart::subtotal(2, '.', '');

        //Apply Discount here

        if (session::has('code')) {
            $code = Session::get('code');
            if ($code->type == 'percent') {
                $discount = ($code->discount_amount / 100) * $subTotal;
            } else {
                $discount = $code->discount_amount;
            }
        }

        // calculating Shipping
        if ($customeraddress != '') {

            $userCountry =   $customeraddress->country_id;
            $shippingInfo = ShippingCharge::where('country_id', $userCountry)->first();

            $totalQty = 0;
            $totalshippingCharge = 0;
            $grandTotal = 0;

            foreach (cart::content() as $item) {
                $totalQty += $item->qty;
            }

            $totalshippingCharge =   $totalQty * $shippingInfo->amount;
            $grandTotal =  ($subTotal - $discount) + $totalshippingCharge;
        } else {
            $grandTotal =  ($subTotal - $discount);
            $totalshippingCharge = 0;
        }


        return view('front.checkout', [
            'countries' => $countries,
            'customeraddress' => $customeraddress,
            'totalshippingCharge' => $totalshippingCharge,
            'discount' => $discount,
            'grandTotal' => $grandTotal,
        ]);
    }

    public function processCheckout(Request $request)
    {
        //  Validation ->step 1

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:5',
            'last_name' => 'required',
            'email' => 'required|email',
            'country' => 'required',
            'address' => 'required|min:14',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'please Fix The errors',
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        //  Save Customer Address ->step 2


        $user = Auth::user();


        CustomerAddress::updateOrCreate(
            ['user_id' => $user->id],

            [
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'country_id' => $request->country,
                'address' => $request->address,
                'apartment' => $request->apartment,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip,
                'mobile' => $request->mobile,

            ]
        );


        // store data in orders table ->3

        if ($request->payment_method == 'cod') {

            // // calculate shipping
            $discountCodeId = NULL;
            $promoCode = '';
            $shipping = 0;
            $discount = 0;
            $subTotal = Cart::subtotal(2, ".", '');

            //Apply Discount Here

            if (session::has('code')) {
                $code = Session::get('code');
                if ($code->type == 'percent') {
                    $discount = ($code->discount_amount / 100) * $subTotal;
                } else {
                    $discount = $code->discount_amount;
                }
                $discountCodeId = $code->id;
                $promoCode = $code->code;
            }


            //calculate Shipping 

            $shippingInfo = shippingCharge::where('country_id', $request->country)->first();

            $totalQty = 0;
            foreach (cart::content() as $item) {
                $totalQty += $item->qty;
            }

            if ($shippingInfo != null) {
                $shipping = $totalQty * $shippingInfo->amount;
                $grandTotal = ($subTotal - $discount) + $shipping;
            } else {
                $shippingInfo = shippingCharge::where('country_id', 'rest_of_world')->first();
                $shipping = $totalQty * $shippingInfo->amount;
                $grandTotal =  ($subTotal - $discount) + $shipping;
            }




            $order = new Order;
            $order->subtotal = $subTotal;
            $order->shipping = $shipping;
            $order->grand_total = $grandTotal;
            $order->discount = $discount;
            $order->coupon_code_id = $discountCodeId;
            $order->coupon_code = $promoCode;
            $order->payment_status = 'not paid';
            $order->status = 'pending';
            $order->user_id = $user->id;
            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->country_id = $request->country;
            $order->address = $request->address;
            $order->apartment = $request->apartment;
            $order->city = $request->city;
            $order->state = $request->state;
            $order->zip = $request->zip;
            $order->mobile = $request->mobile;
            $order->notes = $request->order_notes;
            $order->save();

            // store order items in order items table
            foreach (cart::content() as $item) {
                $orderItem = new OrderItem;
                $orderItem->product_id = $item->id;
                $orderItem->order_id = $order->id;
                $orderItem->name = $item->name;
                $orderItem->qty = $item->qty;
                $orderItem->price = $item->price;
                $orderItem->total = $item->price * $item->qty;
                $orderItem->save();
            
                // update product stock
                $ProductData = Product::find($item->id);
                if ($ProductData && $ProductData->track_qty == 'Yes') {
                    $currentQty = $ProductData->qty;
                    $updatedQty = $currentQty - $item->qty;
                    $ProductData->qty = $updatedQty;
                    $ProductData->save();
                }
            }
          
            //SEND EMAIL
            orderEmail($order->id,'customer');


            Session::flash('success', ' You Have Successfully Placed Your Order ');
            Cart::destroy();
            session()->forget('code');
            return response()->json([
                'message' => 'Order Saved Successfully',
                'orderId' => $order->id,
                'status' => true,
            ]);
        } 
    }

    public function thankyou($id)
    {

        return View('front.thanks', [
            'id' => $id
        ]);
    }

    public function getOrderSummary(Request $request)
    {
        $subTotal = Cart::subtotal(2, '.', '');
        $discount = 0;
        $discountString = '';

        //Apply Discount here

        if (session::has('code')) {
            $code = Session::get('code');
            if ($code->type == 'percent') {
                $discount = ($code->discount_amount / 100) * $subTotal;
            } else {
                $discount = $code->discount_amount;
            }
            $discountString = ' <div class=" mt-4"  id="discount-response">
            <strong>' . session()->get('code')->code . '</strong>
            <a class="btn btn-sm btn-danger" id="remove-discount" href=""><i class="fa fa-times"></i></a>
        </div> ';
        }



        //---//

        if ($request->country_id > 0) {


            $shippingInfo = shippingCharge::where('country_id', $request->country_id)->first();
            $totalQty = 0;
            foreach (cart::content() as $item) {
                $totalQty += $item->qty;
            }


            if ($shippingInfo != null) {
                $shippingCharge = $totalQty * $shippingInfo->amount;
                $grandTotal =  ($subTotal - $discount) + $shippingCharge;

                return response()->json([
                    'status' => true,
                    'grandTotal' =>  number_format($grandTotal, 2),
                    'discount' =>  number_format($discount,2),
                    'discountString' => $discountString,
                    'shippingCharge' =>  number_format($shippingCharge, 2),

                ]);
            } else {
                $shippingInfo = shippingCharge::where('country_id', 'rest_of_world')->first();

                $shippingCharge = $totalQty * $shippingInfo->amount;
                $grandTotal =  ($subTotal - $discount) + $shippingCharge;

                return response()->json([
                    'status' => true,
                    'grandTotal' =>  number_format($grandTotal, 2),
                    'discount' =>  number_format($discount,2),
                    'discountString' => $discountString,
                    'shippingCharge' =>  number_format($shippingCharge, 2),

                ]);
            }
        } else {

            return response()->json([
                'status' => true,
                'grandTotal' =>  number_format(($subTotal - $discount), 2),
                'discount' =>  number_format($discount,2),
                'discountString' => $discountString,
                'shippingCharge' =>  number_format(0, 2),

            ]);
        }
    }

    public  function applyDiscount(Request $request)
    {
        $code = DiscountCoupon::where('code', $request->code)->first();

        if ($code == null) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Discount Coupon'
            ]);
        }

        // Debugging code (remove in production)
        Log::info('Coupon Details: ', $code->toArray());

        $now = Carbon::now();

        // Validate start date
        if ($code->starts_at != '') {
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->starts_at);
            if ($now->lt($startDate)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Discount Coupon'
                ]);
            }
        }

        // Validate expiration date

        if ($code->expires_at != '') {
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->expires_at);

            if ($now->gt($endDate)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Discount Coupon'
                ]);
            }
        }


        // max uses chk
        if ($code->max_uses > 0) {
            $couponUsed = order::where('coupon_code_id', $code->id)->count();
            if ($couponUsed >= $code->max_uses) {
                return response()->json([
                    'status' => false,
                    'message' => 'Discount Coupon Limit Exceed'
                ]);
            }
        }
        //Max Uses User
        if ($code->max_uses_user > 0) {
            $couponUsedUser = order::where(['coupon_code_id' => $code->id, 'user_id' => Auth::user()->id])->count();
            if ($couponUsedUser >= $code->max_uses_user) {
                return response()->json([
                    'status' => false,
                    'message' => 'You Already Used this Coupon'
                ]);
            }
        }
        $subTotal = Cart::subtotal(2, '.', '');

        // MInimum Amt chk

        if ($code->min_amount > 0) {
            if ($subTotal < $code->min_amount) {
                return response()->json([
                    'status' => false,
                    'message' => 'Your min must be $' . $code->min_amount . '.',
                ]);
            }
        }


        Session::put('code', $code);

        return  $this->getOrderSummary($request);
    }


    public function removeCoupon(Request $request)
    {
        session::forget('code');
        return  $this->getOrderSummary($request);
    }
}
