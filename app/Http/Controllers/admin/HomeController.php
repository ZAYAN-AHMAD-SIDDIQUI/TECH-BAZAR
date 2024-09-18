<?php

namespace App\Http\Controllers\admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\File;
use App\Models\Product;
use App\Models\TempImage;
use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {

      $totalOrders =  Order::where('status','!=' ,'cancelled')->count();
    //   $totalUsers =  User::where('status',1)->count();
      $totalUsers =  User::where('role', 1)->count();
      $totalProducts =  Product::count();

      $totalRevenue =  Order::where('status','!=' ,'cancelled')->sum('grand_total');


    //   this month revenue
   $startOfMonth = Carbon::now()->startOfMonth()->format('-m-d');
   $currentDate = Carbon::now()->format('Y-m-d');



   $monthRevenue =  Order::where('status','!=' ,'cancelled')
   ->whereDate('created_at','>=', $startOfMonth )
   ->whereDate('created_at','<=', $currentDate )
   ->sum('grand_total');

// Last Month Revenue

$lastMonthDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
$lastMonthEndDate = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
$lastMonthName =  Carbon::now()->subMonth()->startOfMonth()->format('M');

$LastmonthRevenue =  Order::where('status','!=' ,'cancelled')
->whereDate('created_at','>=', $lastMonthDate )
->whereDate('created_at','<=',$lastMonthEndDate )
->sum('grand_total');

// last 30 Days sales

$lastthirtydaystartdate = Carbon::now()->subDays(30)->format('Y-m-d');

$LastThirtydayssales =  Order::where('status','!=' ,'cancelled')
->whereDate('created_at','>=', $lastthirtydaystartdate )
->whereDate('created_at','<=',$currentDate )
->sum('grand_total');


// delete temp images 
 $daybeforeToday =  Carbon::now()->subDays(1)->format('Y-m-d  H:i:s' );
$tempImages = TempImage::Where ('created_at','<=', $daybeforeToday)->get();


foreach ($tempImages as  $tempImage) {
 $path = public_path('/temp/'.$tempImage->name);
 $thumbPath = public_path('/temp/thumb/'.$tempImage->name);

// delete  main Images
if(File::exists($path)){
File::delete($path);
}

// delete thumb Images
if(File::exists($thumbPath)){
  File::delete($thumbPath);
  }
  
  Tempimage::where('id', $tempImage->id)->delete();

}

        return view('admin.dashboard',[
            'totalOrders'=> $totalOrders,
            'totalProducts'=> $totalProducts,
            'totalUsers'=> $totalUsers,
            'totalRevenue'=> $totalRevenue,
            'monthRevenue'=> $monthRevenue,
            'LastmonthRevenue'=> $LastmonthRevenue,
            'LastThirtydayssales'=> $LastThirtydayssales,
            'lastMonthName'=> $lastMonthName,
        ]);
        //$admin = Auth::guard('admin')->user();
        //echo 'Welcome ' . $admin->name . ' <a href="' . route('admin.logout') . '">Logout</a>';
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login')->with('success', 'Successfully logged out');
    }
}
