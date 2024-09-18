<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductRating;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null)
    {

        $categorySelected = '';
        $subCategorySelected = '';

        $brandsArray = [];


        //-----//
        $categories = category::orderby('name', 'ASC')
            ->with('sub_category')
            ->where('status', 1)->get();

        $brands = Brand::orderby('name', 'ASC')
            ->where('status', 1)->get();
        //----//

        $products = Product::Where('status', 1);
        //Apply Filter
        if (!empty($categorySlug)) {
            $category = Category::Where('slug', $categorySlug)->first();
            $products = $products->where('category_id', $category->id);
            $categorySelected = $category->id;
        }

        if (!empty($subCategorySlug)) {
            $subCategory = SubCategory::Where('slug', $subCategorySlug)->first();
            $products = $products->where('sub_category_id', $subCategory->id);
            $subCategorySelected = $subCategory->id;
        }

        if (!empty($request->get('brand'))) {
            $brandsArray = explode(',', $request->get('brand'));
            $products = $products->whereIn('brand_id', $brandsArray);
        }

        if ($request->get('price_max') != '' && $request->get('price_min') != '') {
            if ($request->get('price_max') == 500) {
                $products = $products->whereBetween('price', [intval($request->get('price_min')), 2000]);
            } else {
                $products = $products->whereBetween('price', [intval($request->get('price_min')), intval($request->get('price_max'))]);
            }
        }

        if (!empty($request->get('search'))) {
            $products = $products->where('title', 'like', '%' . $request->get('search') . '%');
        }
        //--//

        if ($request->get('sort') != '') {
            if ($request->get('sort') == 'latest') {
                $products = $products->orderby('id', 'DESC');
            } else if (($request->get('sort') == 'price_asc')) {
                $products = $products->orderby('price', 'ASC');
            } else if (($request->get('sort') == 'price_desc')) {
                $products = $products->orderby('price', 'DESC');
            } else {
                $products = $products->orderby('id', 'DESC');
            }
        }



        $products = $products->paginate(9);
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['products'] = $products;
        $data['categorySelected'] = $categorySelected;
        $data['subCategorySelected'] = $subCategorySelected;
        $data['brandsArray'] =  $brandsArray;
        $data['priceMax'] = (intval($request->get('price_max')) ==  0) ? 1000 : $request->get('price_max');
        $data['priceMin'] = intval($request->get('price_min'));
        $data['sort'] = $request->get('sort');

        foreach ($products as $product) {
            $product->short_title = implode(' ', array_slice(explode(' ', $product->title), 0, 3));
        }


        return view('front.shop', $data);
    }

    public function product($slug)
    {
        $product = Product::where('slug', $slug)
        ->withCount('product_ratings')
        ->withSum('product_ratings','rating')
        ->with('product_images','product_ratings')->first();
        if ($product == null) {
            abort(404);
        }

        //fetch related products
        $relatedProducts = [];
        if ($product->related_products != '') {
            $productArray = explode(',', $product->related_products);
            $relatedProducts =  Product::whereIn('id', $productArray)->where('status', 1)->get();
        }

        // Average Rating Calculated
        $avgRating ="0.00";
        $avgRatingPer='0.00';
        if( $product->product_ratings_count > 0){
            $avgRating = number_format(($product->product_ratings_sum_rating/$product->product_ratings_count),1);
            $avgRatingPer = ($avgRating*100)/5;
        }
 
        $data['product'] = $product;
        $data['relatedProducts'] = $relatedProducts;
        $data['avgRating'] = $avgRating;
        $data['avgRatingPer'] = $avgRatingPer;
        return view('front.product', $data);
    }



    public function saveRating(Request  $request, $id )
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:4',
            'email' => 'required|email',
            'comment' => 'required|min:5',
            'rating' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

$count= ProductRating::where('email',$request->email)->count();
if($count > 0){
    Session::flash('error','You Already Rated This Product');
    return response()->json([
        'status' => true,
    ]);
}

        $productRating = new ProductRating;
        $productRating->product_id =$id;
        $productRating->username =$request->name;
        $productRating->email =$request->email;
        $productRating->comment =$request->comment;
        $productRating->rating =$request->rating;
        $productRating->status = 0;
        $productRating->save();

 Session::flash('success','Thanks For Your Rating');
        return response()->json([
            'status' => true,
            'message' =>'Thanks For Your Rating',
        ]);
    }
}
