<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\DiscountCodeController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\PageController;
use App\Http\Controllers\admin\productController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\admin\ProductImageController;
use App\Http\Controllers\admin\ShippingController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShopController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/test', function () {
//     orderEmail(6);
// });

//Frontend route
Route::get('/', [FrontController::class, 'index'])->name('front.home');
Route::get('/shop/{CategorySlug?}/{subCategorySlug?}', [ShopController::class, 'index'])->name('front.shop');
Route::get('/product/{slug}', [ShopController::class, 'product'])->name('front.product');
Route::get('/get-products', [ProductController::class, 'index'])->name('front.home');
Route::get('/cart', [CartController::class, 'cart'])->name('front.cart');
Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('front.addToCart');
Route::post('/update-cart', [CartController::class, 'updateCart'])->name('front.updateCart');
Route::post('/delete-item', [CartController::class, 'deleteItem'])->name('front.deleteItem.Cart');
Route::get('/checkout', [CartController::class, 'checkout'])->name('front.checkout');
Route::post('/process-checkout', [CartController::class, 'processCheckout'])->name('front.processCheckout');
Route::get('/thanks/{orderId}', [CartController::class, 'thankyou'])->name('front.thanks');
Route::post('/get-order-summary', [CartController::class, 'getOrderSummary'])->name('front.getOrderSummary');
Route::post('/apply-discount', [CartController::class, 'applyDiscount'])->name('front.applyDiscount');
Route::post('/remove-discount', [CartController::class, 'RemoveCoupon'])->name('front.RemoveCoupon');
Route::post('/add-to-wishlist', [FrontController::class, 'addToWishlist'])->name('front.addToWishlist');
Route::get('/page/{slug}', [FrontController::class, 'page'])->name('front.page');
Route::get('/contact-us', [FrontController::class, 'contactUs'])->name('front.contactUs');
Route::post('/send-contact-email', [FrontController::class, 'sendContactEmail'])->name('front.sendContactEmail');
// Font Accounts Route
Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('front.forgotPassword');
Route::post('/process-forgot-password', [AuthController::class, 'ProcessForgotPassword'])->name('front.ProcessForgotPassword');
Route::get('/reset-password/{token}', [AuthController::class, 'resetPassword'])->name('front.resetPassword');
Route::post('/process-reset-password', [AuthController::class, 'processResetPassword'])->name('front.processResetPassword');
//SAVE RATING
 Route::post('/save-rating/{productId}', [shopController::class, 'saveRating'])->name('front.saveRating');

//ADMIN PROTECTED ROUTE
Route::group(['prefix' => 'account'], function () {

    Route::group(['middleware' => 'guest'], function () {

        route::post('/authenticate', [AuthController::class, 'authenticate'])->name('account.authenticate');
        route::get('/login', [AuthController::class, 'login'])->name('account.login');
        route::get('/register', [AuthController::class, 'register'])->name('account.register');
        route::post('/process-register', [AuthController::class, 'processRegister'])->name('account.processRegister');
    });

    Route::group(['middleware' => 'auth'], function () {
        route::get('/profile', [AuthController::class, 'profile'])->name('account.profile');
        route::post('/update-profile', [AuthController::class, 'updateProfile'])->name('account.updateProfile');
        route::post('/update-address', [AuthController::class, 'updateAddress'])->name('account.updateAddress');
        route::get('/my-orders', [AuthController::class, 'orders'])->name('account.orders');
        route::get('/order-detail/{orderId}', [AuthController::class, 'orderDetail'])->name('account.orderDetail');
        route::get('/logout', [AuthController::class, 'logout'])->name('account.logout');
        route::get('/my-wishlist', [AuthController::class, 'wishlist'])->name('account.wishlist');
        route::post('/remove-product-from-wishlist', [AuthController::class, 'removeproductfromwishlist'])->name('account.removeproductfromwishlist');
        route::get('/show-change-password', [AuthController::class, 'ShowchangepasswordForm'])->name('account.changepassword');
        route::post('/process-change-password', [AuthController::class, 'Changepassword'])->name('account.Processchangepassword');
    });
});

// Admin Authentication

Route::group(['prefix' => 'admin'], function () {

    Route::group(['middleware' => 'admin.guest'], function () {
        Route::get('/login', [AdminLoginController::class, "index"])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, "authenticate"])->name('admin.authenticate');
    });

    Route::group(['middleware' => 'admin.auth'], function () {
        Route::get('/dashboard', [HomeController::class, "index"])->name('admin.dashboard');
        Route::get('/logout', [HomeController::class, "logout"])->name('admin.logout');

        //Category Routes
        Route::get('/categories', [CategoryController::class, "index"])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, "create"])->name('categories.create');
        Route::post('/categories', [CategoryController::class, "store"])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, "edit"])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, "update"])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, "destroy"])->name('categories.delete');


        //Sub_Categories Route

        Route::get('/sub-categories/create', [SubCategoryController::class, "create"])->name('sub-categories.create');
        Route::post('/sub-categories', [SubCategoryController::class, "store"])->name('sub-categories.store');
        Route::get('/sub-categories', [SubCategoryController::class, "index"])->name('sub-categories.index');
        Route::get('/sub-categories/{subCategory}/edit', [SubCategoryController::class, "newedit"])->name('sub-categories.edit');
        Route::put('/sub-categories/{subCategory}', [SubCategoryController::class, "update"])->name('sub-categories.update');
        Route::delete('/sub-categories/{subCategory}', [SubCategoryController::class, "destroy"])->name('sub-categories.delete');


        //Brands Route

        Route::get('/brands', [BrandController::class, "index"])->name('brands.index');
        Route::get('/brand/create', [BrandController::class, "create"])->name('brands.create');
        Route::post('/brands', [BrandController::class, "store"])->name('brands.store');
        Route::get('/brands/{brand}/edit', [BrandController::class, "edit"])->name('brands.edit');
        Route::put('/brands/{brand}', [BrandController::class, "update"])->name('brands.update');
        Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('brands.delete');


        //Product
        Route::get('/products', [productController::class, "index"])->name('products.index');
        Route::get('/products/create', [productController::class, "create"])->name('products.create');
        Route::post('/products', [productController::class, "store"])->name('products.store');
        Route::get('/product-subcategories', [ProductSubCategoryController::class, "index"])->name('product-subcategories.index');
        Route::get('/products/{product}/edit', [ProductController::class, "edit"])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, "update"])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.delete');
        Route::get('/get-products', [ProductController::class, 'getProducts'])->name('products.getProducts');
        Route::get('/ratings', [productController::class, "productRatings"])->name('products.productRatings');
        Route::get('/change-rating-status', [productController::class, "changeRatingStatus"])->name('products.changeRatingStatus');




        // Product Images Routes
        Route::post('/product-images/update', [ProductImageController::class, "update"])->name('product-images.update');
        Route::delete('/product-images', [ProductImageController::class, "destroy"])->name('product-images.destroy');


        //shipping routes
        Route::get('/shipping/create', [ShippingController::class,'create'])->name('shipping.create');
        Route::post('/shipping', [ShippingController::class, 'store'])->name('shipping.store');
        Route::get('/shipping/{id}', [ShippingController::class, 'edit'])->name('shipping.edit');
        Route::put('/shipping/{id}', [ShippingController::class,'update'])->name('shipping.update');
        Route::delete('/shipping/{id}', [shippingController::class, 'destroy'])->name('shipping.delete');

       

        // Discount Coupons route

          
         Route::get('/coupons', [DiscountCodeController::class, "index"])->name('coupons.index');
         Route::get('/coupons/create', [DiscountCodeController::class, "create"])->name('coupons.create');
         Route::post('/coupons', [DiscountCodeController::class, "store"])->name('coupons.store');
         Route::get('/coupons/{coupon}/edit', [DiscountCodeController::class, "edit"])->name('coupons.edit');
         Route::put('/coupons/{coupons}', [DiscountCodeController::class, "update"])->name('coupons.update');
         Route::delete('/coupons/{coupon}', [DiscountCodeController::class, 'destroy'])->name('coupons.delete');
  
          // Orders Route
          Route::get('/orders', [OrderController::class, "index"])->name('orders.index');
          Route::get('/orders/{id}', [OrderController::class, "detail"])->name('orders.detail');
          Route::post('/orders/change-status/{id}', [OrderController::class, "changeOrderStatus"])->name('orders.changeOrderStatus');
          Route::post('/order/send-email/{id}', [OrderController::class, "sendInvoiceEmail"])->name('orders.sendInvoiceEmail');



        // User Route

        Route::get('/users', [UserController::class, "index"])->name('users.index');
        Route::get('/users/create', [UserController::class, "create"])->name('users.create');
        Route::post('/users', [UserController::class, "store"])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, "edit"])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, "update"])->name('users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.delete');

        // Pages routes


        Route::get('/pages', [PageController::class, "index"])->name('pages.index');
        Route::get('/pages/create', [PageController::class, "create"])->name('pages.create');
        Route::post('/pages', [Pagecontroller::class, "store"])->name('pages.store');
        Route::get('/pages/{page}/edit', [Pagecontroller::class, "edit"])->name('pages.edit');
        Route::put('/pages/{page}', [PageController::class, "update"])->name('pages.update');
        Route::delete('/pages/{page}', [PageController::class,'destroy'])->name('pages.delete');



        // Password change settings Routes
        Route::get('/change-password', [SettingController::class, "showChangePasswordForm"])->name('admin.showChangePasswordForm');
        Route::post('/process-change-password', [SettingController::class, "processChangePassword"])->name('admin.processChangePassword');




        //temp-images.create

        Route::post('/upload-temp-image', [TempImagesController::class, "create"])->name('temp-images.create');

        //Generate slug route 

        Route::get('/getSlug', function (Request $request) {
            $slug = '';
            if (!empty($request->title)) {
                $slug = Str::slug($request->title);
            }
            return response()->json([
                'status' => true,
                'slug' => $slug
            ]);
        })->name('getSlug');
    });
});
