<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductImage;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductImageController extends Controller
{
    public function update(Request $request)
    {
        $image = $request->image;
        $ext = $image->getClientOriginalExtension();
        $sourcePath = $image->getPathName();

        $productImage = new ProductImage();
        $productImage->product_id = $request->product_id;
        $productImage->image = 'NULL';
        $productImage->save();

        $imageName = $request->product_id . '-' . $productImage->id . '-' . time() . '.' . $ext;
        $productImage->image = $imageName;
        $productImage->save();

        //large Image
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

        return response()->json([
            'message' => 'Image Saved Successfully',
            'image_id' => $productImage->id,
            'ImagePath' => asset('uploads/product/small/' . $productImage->image),
            'status'  =>   true
        ]);
    }

    public function destroy(request $request)
    {
        $productImage = ProductImage::find($request->id);

        if (empty($productImage)) {
            return response()->json([
                'message' => 'Image Not Found',
                'status'  =>   false
            ]);
        }

        //delete images from folder

        File::delete(public_path('uploads/product/large/'.$productImage->image));
        File::delete(public_path('uploads/product/small/'.$productImage->image));

        $productImage->delete();

        return response()->json([
            'message' => 'Image Deleted Successfully',
            'status'  =>   true
        ]);
    }
}
