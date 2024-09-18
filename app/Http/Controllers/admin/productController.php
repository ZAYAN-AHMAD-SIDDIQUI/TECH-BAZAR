<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\brand;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Models\ProductImage;
use App\Models\ProductRating;
use App\Models\TempImage;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Session;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class productController extends Controller
{

    public function index(Request $request)
    {
        $products = Product::latest('id')->with('product_images');
        if ($request->get('keyword') != "") {
            $products = $products->where('title', 'like', '%' . $request->keyword . '%');
        }
        $products = $products->paginate();
        //dd($products);
        $data['products'] = $products;
        return view('admin.products.list', $data);
    }

    public function create()
    {
        $data = [];
        $categories = category::orderBy('name', 'ASc')->get();
        $brands = Brand::orderBy('name', 'ASc')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        return view('admin.products.create', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',

        ];
        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $product = new Product();
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->is_featured = $request->is_featured;
            $product->brand_id = $request->brand;
            $product->shipping_returns = $request->shipping_returns;
            $product->short_description = $request->short_description;
            $product->related_products  = (!empty($request->related_products)) ? implode(',', $request->related_products) : '';
            $product->save();

            //save Gallery Images

            if (!empty($request->image_array)) {
                foreach ($request->image_array as $temp_image_id) {

                    $tempImageInfo = TempImage::find($temp_image_id);
                    $extArray = explode('.', $tempImageInfo->name);
                    $ext = last($extArray); // like jpg, png

                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = 'NULL';
                    $productImage->save();

                    $imageName = $product->id . '-' . $productImage->id . '-' . time() . '.' . $ext;
                    $productImage->image = $imageName;
                    $productImage->save();

                    //Generate Thumbnails

                    //large Image
                    $sourcePath = public_path() . '/temp/' . $tempImageInfo->name;
                    $destPath = public_path() . '/uploads/product/large/' . $imageName;
                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($sourcePath);
                    $image->scaleDown(1400);
                    $image->save($destPath);
                    //Small Image
                    $destPath = public_path() . '/uploads/product/small/' . $imageName;
                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($sourcePath);
                    $image->cover(300, 300);
                    $image->save($destPath);
                }
            }

            $request->session()->flash('success', 'Product Added Successfully');

            return response()->json([
                'status' => true,
                'message' => 'Product Added Successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function edit($id, Request $request)
    {
        // Find the product by ID
        $product = Product::find($id);

        // Check if the product exists
        if (empty($product)) {
            return redirect()->route("products.index")->with('error', 'Product Not Found');
        }
        // Fetch product Image
        $productImages = ProductImage::where('product_id', $product->id)->get();

        // Get subcategories for the product's category
        $subCategories = SubCategory::where('category_id', $product->category_id)->get();

        //fetch related products
        $relatedProducts = [];
        if ($product->related_products != '') {
            $productArray = explode(',', $product->related_products);
            $relatedProducts =  Product::whereIn('id', $productArray)->with('product_images')->get();
        }

        // Prepare data for the view
        $data = [];
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['product'] = $product;
        $data['subCategories'] = $subCategories;
        $data['productImages'] = $productImages;
        $data['relatedProducts'] = $relatedProducts;
        // Return the view with the data
        return view('admin.products.edit', $data);
    }


    public function update($id, request $request)
    {
        $product = Product::find($id);
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,' . $product->id . ',id',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku,' . $product->id . ',id',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',

        ];
        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {

            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->is_featured = $request->is_featured;
            $product->brand_id = $request->brand;
            $product->shipping_returns = $request->shipping_returns;
            $product->short_description = $request->short_description;
            $product->related_products  = (!empty($request->related_products)) ? implode(',', $request->related_products) : '';
            $product->save();



            $request->session()->flash('success', 'Product Updated Successfully');

            return response()->json([
                'status' => true,
                'message' => 'Product Updated Successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id, request $request)
    {
        $product = Product::find($id);


        if (empty($product)) {
            $request->session()->flash('error', 'Product Not Found');
            return response()->json([
                'status' => false,
                'notFound' => true

            ]);
        }

        $productImages = ProductImage::where('product_id', $id)->get();

        if (!empty($productImages)) {
            foreach ($productImages as $productImage) {
                File::delete(public_path('uploads/product/large/' . $productImage->image));
                File::delete(public_path('uploads/product/small/' . $productImage->image));
            }

            ProductImage::where('product_id', $id)->delete();
        }

        $product->delete();

        $request->session()->flash('success', 'Product Deleted Successfully');

        return response()->json([
            'status' => true,
            'message' => "Product Deleted Successfully"
        ]);
    }

    public function getProducts(Request $request)
    {
        $tempProduct = [];
        if ($request->term != '') {
            $products = Product::where('title', 'like', '%' . $request->term . '%')->get();

            if ($products != null) {
                foreach ($products as $product) {
                    $tempProduct[] = array('id' => $product->id, 'text' => $product->title);
                }
            }
        }

        return response()->json([
            'tags' => $tempProduct,
            'status' => true
        ]);
    }

    public function productRatings( request $request )
    {
        
        $ratings = ProductRating::select('product_ratings.*', 'products.title as productTitle')->orderby('product_ratings.created_at', 'DESC');
        $ratings =   $ratings->leftJoin('products', 'products.id', 'product_ratings.product_id');
        if ($request->get('keyword') != "") {
            $ratings = $ratings->orwhere('products.title', 'like', '%' . $request->keyword . '%');
            $ratings = $ratings->orwhere('product_ratings.username', 'like', '%' . $request->keyword . '%');
        }
        $ratings =   $ratings->paginate(10);
        return View('admin.products.ratings', [
            'ratings' => $ratings,
        ]);
    }

    public function changeRatingStatus(Request $request)
    {
        $productRating = ProductRating::find($request->id);
        if (!$productRating) {
            return response()->json([
                'status' => false,
                'message' => 'Product rating not found',
            ]);
        }
        $productRating->status = $request->status;
        $productRating->save();
        Session::flash('success', 'Status changed successfully');
        return response()->json([
            'status' => true,
        ]);
    }
    
}
