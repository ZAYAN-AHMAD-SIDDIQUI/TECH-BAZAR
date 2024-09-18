<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;



class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query();

        if (!empty($request->get('keyword'))) {
            $categories = $categories->where('name', 'like', '%' . $request->get('keyword') . '%');
        }

        $categories = $categories->orderBy('id', 'asc')->paginate(10);
        return view('admin.category.list', compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories',
        ]);

        if ($validator->passes()) {
        $category = new Category();
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->status = $request->status;
        $category->showHome = $request->showHome;
        $category->save();

        if (!empty($request->image_id)) {
             $tempImage = TempImage::find($request->image_id);
            $extArray = explode('.',$tempImage->name);
             $ext = last($extArray);
            $newImageName = $category->id.'-'.time().'.'.$ext;
             $sPath = public_path().'/temp/'.$tempImage->name;
             $dPath = public_path().'/uploads/category/'.$newImageName;
            File::copy($sPath,$dPath);

            // Resize and save the image using Intervention Image

            $dPath = public_path().'/uploads/category/thumb/'.$newImageName;
            $manager = new ImageManager(new Driver());
            $image= $manager->read($sPath);
            $image->cover(450,600);
            $image->save($dPath);
            $category->image = $newImageName;
            $category->save();
        }

        $request->session()->flash('success', 'Category Added Successfully');

        return response()->json([
            'status' => true,
            'message' => 'Category Added Successfully'
        ]);
    }
    else {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ]);
    }
    }
    



    public function edit($categoryId, Request $request) {
        $category = Category::find($categoryId);
        if (empty($category)) {
            return redirect()->route('categories.index');
        }

        return view('admin.category.edit', compact('category'));
    }






    //UPDATE FUNCTION

    public function update($categoryId, Request $request)
    {         

        $category = Category::find($categoryId);
        if (empty($category)) {
            $request->session()->flash('error', 'Category Not Found');

            return response()->json([
                'status' => false,
                'notFound'=>true,
                'message' => 'Category not  found'
            ]);
        }

       
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'. $category->id .',id',
            'status' => 'required|boolean',
        ]);

        

        if ($validator->passes()) {
    
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->status = $request->status;
        $category->showHome = $request->showHome;
        $category->save();
         $oldImage =$category->image;

        if (!empty($request->image_id)) {
             $tempImage = TempImage::find($request->image_id);
            $extArray = explode('.',$tempImage->name);
             $ext = last($extArray);


             $newImageName = $category->id.'-'.time().'.'.$ext;
             $sPath = public_path().'/temp/'.$tempImage->name;
             $dPath = public_path().'/uploads/category/'.$newImageName;
            File::copy($sPath,$dPath);

            // Resize and save the image using Intervention Image

            $dPath = public_path().'/uploads/category/thumb/'.$newImageName;
            $manager = new ImageManager(new Driver());
            $image= $manager->read($sPath);
            $image->cover(450,600);
            $image->save($dPath);            
            $category->image = $newImageName;
            $category->save();


            File::delete(public_path().'/uploads/category/thumb/'.$oldImage);
            File::delete(public_path().'/uploads/category/'.$oldImage);
        }

            $request->session()->flash('success', 'Category Updated Successfully');

            return response()->json([
            'status' => true,
            'message' => 'Category Updated Successfully'
            ]);
    }
    else {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ]);
    }


    }

    public function destroy($categoryId, Request $request)
    {
        $category = Category::find($categoryId);
        if(empty($category)){
            $request->session()->flash('error','Record Not Found ');
            return response([
            'status' => false,
           'message' => 'category Not Found'
            ]);        
         }

        File::delete(public_path().'/uploads/category/thumb/'.$category->image);
        File::delete(public_path().'/uploads/category/'.$category->image);
  
        $category->delete();

        $request->session()->flash('success','Category deleted Successfully');

        return response()->json([
        'status' => true,
        'message' => 'Category deleted Successfully'
        ]);

    }
}
