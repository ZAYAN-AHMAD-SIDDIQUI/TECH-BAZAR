<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Validator;
use Mockery\Matcher\Subset;

class SubCategoryController extends Controller
{

   public function index(Request $request)
   {
      $subCategories = SubCategory::select('sub_categories.*', 'categories.name as categoryName')
         ->latest('sub_categories.id')->leftJoin('categories', 'categories.id', 'sub_categories.category_id');

      if (!empty($request->get('keyword'))) {
         $subCategories = $subCategories->where('sub_categories.name', 'like', '%' . $request->get('keyword') . '%');
         $subCategories = $subCategories->where('categories.name', 'like', '%' . $request->get('keyword') . '%');
      }
      $subCategories = $subCategories->paginate(10);
      return view('admin.sub_category.list', compact('subCategories'));
   }


   public function create()
   {
      $categories = category::orderBy('name', 'ASC')->get();
      $data['categories'] = $categories;
      return view('admin.sub_category.create', $data);
   }


   public function store(Request $request)
   {
      $validator = Validator::make($request->all(), [
         'name' => 'required',
         'slug' => 'required|unique:sub_categories',
         'category' => 'required',
         'status' => 'required|boolean',
      ]);
      if ($validator->passes()) {

         $subCategory = new SubCategory();
         $subCategory->name = $request->name;
         $subCategory->slug = $request->slug;
         $subCategory->category_id = $request->category;
         $subCategory->status = $request->status;
         $subCategory->showHome = $request->showHome;
         $subCategory->save();

         $request->session()->flash('success', 'Subcategory Added Successfully');

         return response([
            'status' => true,
            'message' => 'Subcategory Added Successfully',
         ]);
      } else {
         return response([
            'status' => false,
            'errors' => $validator->errors()
         ]);
      }
   }

   public function newedit($id ,Request $request){
      $subCategory = SubCategory::find($id);
      if(empty($subCategory)){
         $request->session()->flash('error','Record Not Found');
         return redirect()->route('sub-categories.index');
      }

      $categories = category::orderBy('name', 'ASC')->get();
      $data['categories'] = $categories;
      $data['subCategory'] = $subCategory;
      return view('admin.sub_category.edit',$data);
   }

   public function update($id ,Request $request){

      $subCategory = SubCategory::find($id);
      if(empty($subCategory)){
         $request->session()->flash('error','Record Not Found');
         // return redirect()->route('sub-categories.index');
         return response([
            'status' => false,
            'notFound' => true,
         ]);
      }

      $validator = Validator::make($request->all(), [
         'name' => 'required',
        // 'slug' => 'required|unique:sub_categories',
         'slug' => 'required|unique:sub_categories,slug,'.$subCategory->id.',id',
         'category' => 'required',
         'status' => 'required|boolean',
      ]);
      if ($validator->passes()) {

         $subCategory->name = $request->name;
         $subCategory->slug = $request->slug;
         $subCategory->category_id = $request->category;
         $subCategory->status = $request->status;
         $subCategory->showHome = $request->showHome;
         $subCategory->save();

         $request->session()->flash('success', 'Subcategory updated Successfully');

         return response([
            'status' => true,
            'message' => 'Subcategory updated Successfully',
         ]);
      } else {
         return response([
            'status' => false,
            'errors' => $validator->errors()
         ]);
      }
   } 
   public function destroy($id, Request $request)
   {
       
      $subCategory = SubCategory::find($id);
      if(empty($subCategory)){
          $request->session()->flash('error', 'Record Not Found ');
          return response([
          'status' => false,
          'notFound' => true
          ]);       
       }
      $subCategory->delete();
      $request->session()->flash('success', 'SubCategory deleted Successfully');

      return response([
      'status' => true,
      'message' => 'SubCategory deleted Successfully'
      ]);
   
      

}
}