<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCoupon;
use Illuminate\Contracts\Session\Session as SessionSession;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\session;


class DiscountCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {


        $discountCoupons = DiscountCoupon::latest();

        if (!empty($request->get('keyword'))) {
            $discountCoupons = $discountCoupons->where('name', 'like', '%' . $request->get('keyword') . '%');
            $discountCoupons = $discountCoupons->orwhere('code', 'like', '%' . $request->get('keyword') . '%');
        }

        $discountCoupons = $discountCoupons->orderBy('id', 'DESC')->paginate(10);


        return view('admin.coupon.list', compact('discountCoupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.coupon.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'code' => 'required',
            'type' => 'required',
            'status' => 'required',
            'discount_amount' => 'required',
        ]);

        if ($validator->passes()) {
            // starting date greater than current date time
            if (!empty($request->starts_at)) {
                $now = Carbon::now();

                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
                if ($startAt->lte($now) == true) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['starts_at' => 'Start date can not be less than current date time'],

                    ]);
                }
            }


            // Expires date greater than must be   Start date time
            if (!empty($request->starts_at) && !empty($request->expires_at)) {
                $expireAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);

                if ($expireAt->gt($startAt) == false) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => ' Expires date must be greater than Start date time'],

                    ]);
                }
            }



            $discountCoupons = new DiscountCoupon();
            $discountCoupons->code = $request->code;
            $discountCoupons->name = $request->name;
            $discountCoupons->description = $request->description;
            $discountCoupons->max_uses = $request->max_uses;
            $discountCoupons->max_uses_user = $request->max_uses_user;
            $discountCoupons->type = $request->type;
            $discountCoupons->discount_amount = $request->discount_amount;
            $discountCoupons->min_amount = $request->min_amount;
            $discountCoupons->status = $request->status;
            $discountCoupons->starts_at = $request->starts_at;
            $discountCoupons->expires_at = $request->expires_at;
            $discountCoupons->save();

            $message = 'Discount Coupon Added SuccessFully';
            Session::flash('success', $message);

            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(request $request , $id)
    {

        $coupon = DiscountCoupon::find($id);
        if (empty($coupon)) {
            Session::flash('error','Record Not Found');
            return redirect()->route('coupons.index');
        }
       return view('admin.coupon.edit',compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $discountCoupons =  DiscountCoupon::find($id);

        if($discountCoupons == null ){
            Session::flash('error','Record Not Found');
            return response()->json([
                'status' => true
            ]);
        }

        $validator =  Validator::make($request->all(), [
            'code' => 'required',
            'type' => 'required',
            'status' => 'required',
            'discount_amount' => 'required',
        ]);

        if ($validator->passes()) {
        

            // Expires date greater than must be   Start date time
            if (!empty($request->starts_at) && !empty($request->expires_at)) {
                $expireAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);

                if ($expireAt->gt($startAt) == false) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => ' Expires date must be greater than Start date time'],

                    ]);
                }
            }

            $discountCoupons->code = $request->code;
            $discountCoupons->name = $request->name;
            $discountCoupons->description = $request->description;
            $discountCoupons->max_uses = $request->max_uses;
            $discountCoupons->max_uses_user = $request->max_uses_user;
            $discountCoupons->type = $request->type;
            $discountCoupons->discount_amount = $request->discount_amount;
            $discountCoupons->min_amount = $request->min_amount;
            $discountCoupons->status = $request->status;
            $discountCoupons->starts_at = $request->starts_at;
            $discountCoupons->expires_at = $request->expires_at;
            $discountCoupons->save();

            $message = 'Discount Coupon Updated SuccessFully';
            Session::flash('success', $message);

            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(request $request,$id)
    {
        $discountCoupons =  DiscountCoupon::find($id);

        if($discountCoupons == null ){
            Session::flash('error','Record Not Found');
            return response()->json([
                'status' => true
            ]);
        }
        $discountCoupons->delete();


        Session::flash('success','Discount Coupon Deleted Successfully');
            return response()->json([
                'status' => true
            ]);
    }
}
