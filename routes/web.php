<?php



use App\Http\Livewire\Admin\AdminAddProductAttributeComponent;
use App\Http\Livewire\Admin\AdminEditProductAttributeComponent;
use App\Http\Livewire\Admin\AdminProductAttributeComponent;
use App\Http\Livewire\HomeComponent;
use App\Http\Livewire\ShopComponent;
use App\Http\Livewire\CartComponent;
use App\Http\Livewire\CheckoutComponent;
use App\Http\Livewire\DetailsComponent;
use App\Http\Livewire\CategoryComponent;
use App\Http\Livewire\SearchComponent;
use App\Http\Livewire\ContactComponent;

use App\Http\Livewire\User\UserEditProfileComponent;
use Illuminate\Support\Facades\Route;


use App\Http\Livewire\Admin\AdminDashboardComponent;
use App\Http\Livewire\Admin\AdminCategoryComponent;
use App\Http\Livewire\Admin\AdminAddCategoryComponent;
use App\Http\Livewire\Admin\AdminEditCategoryComponent;
use App\Http\Livewire\Admin\AdminProductComponent;
use App\Http\Livewire\Admin\AdminAddProductComponent;
use App\Http\Livewire\Admin\AdminEditProductComponent;
use App\Http\Livewire\Admin\AdminHomeSliderComponent;
use App\Http\Livewire\Admin\AdminAddHomeSliderComponent;
use App\Http\Livewire\Admin\AdminEditHomeSliderComponent;
use App\Http\Livewire\Admin\AdminHomeCategoryComponent;
use App\Http\Livewire\Admin\AdminCouponsComponent;
use App\Http\Livewire\Admin\AdminAddCouponComponent;
use App\Http\Livewire\Admin\AdminEditCouponComponent;
use App\Http\Livewire\Admin\AdminSaleComponent;
use App\Http\Livewire\Admin\AdminOrderComponent;
use App\Http\Livewire\Admin\AdminOrderDetailsComponent;
use App\Http\Livewire\Admin\AdminSetting;
use App\Http\Livewire\AdminContactComponent;

use App\Http\Livewire\WishlistComponent;
use App\Http\Livewire\ThankyouComponnent;


//
use App\Http\Livewire\User\UserDashboardComponent;
use App\Http\Livewire\User\UserOrdersComponent;
use App\Http\Livewire\User\UserOrderDetailsComponent;
use App\Http\Livewire\User\UserReviewComponent;
use App\Http\Livewire\User\UserChangePasswordComponent;
use App\Http\Livewire\User\UserProfileComponent;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/',HomeComponent::class);
Route::get('/shop',ShopComponent::class)->name('shop');
Route::get('/cart',CartComponent::class)->name('product.cart');
Route::get('/checkout',CheckoutComponent::class)->name('checkout');
Route::get('/product-category/{category_slug}',CategoryComponent::class)->name('product.category');
Route::get('/product/{slug}',DetailsComponent::class)->name('product.details');
Route::get('/search',SearchComponent::class)->name('product.search');
Route::get('/contact-us',ContactComponent::class)->name('contact');

Route::get('/thank-you',ThankyouComponnent::class)->name('thankyou');

// Route for Admin
Route::middleware([
    'auth:sanctum',
    'authadmin',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

    Route::get('/admin/dashboard',AdminDashboardComponent::class)->name('admin.dashboard');
    Route::get('/admin/categories',AdminCategoryComponent::class)->name('admin.categories');
    Route::get('/admin/category/add',AdminAddCategoryComponent::class)->name('admin.addcategory');
    Route::get('/admin/category/edit/{category_slug}/{scategory_slug?}',AdminEditCategoryComponent::class)->name('admin.editcategory');

    Route::get('/wishlist',WishlistComponent::class)->name('product.wishlist');
    Route::get('/admin/home-categories', AdminHomeCategoryComponent::class)->name('admin.homecategories');
    //

    Route::get('/admin/contact',AdminContactComponent::class)->name('admin.contacts');
    Route::get('/admin/setting',AdminSetting::class)->name('admin.settings');
    // Product
    Route::get('/admin/products',AdminProductComponent::class)->name('admin.products');
    Route::get('/admin/product/add',AdminAddProductComponent::class)->name('admin.addproduct');
    Route::get('/admin/product/edit/{product_slug}',AdminEditProductComponent::class)->name('admin.editproduct');
    // HomeSlider
    Route::get('/admin/slider',AdminHomeSliderComponent::class)->name('admin.homeslider');
    Route::get('/admin/slider/add',AdminAddHomeSliderComponent::class)->name('admin.addhomeslider');
    Route::get('/admin/slider/edit/{slide_id}',AdminEditHomeSliderComponent::class)->name('admin.edithomeslider');

    // Coupon
    Route::get('/admin/coupons',AdminCouponsComponent::class)->name('admin.coupons');
    Route::get('/admin/coupon/add',AdminAddCouponComponent::class)->name('admin.addcoupon');
    Route::get('/admin/coupon/edit/{coupon_id}',AdminEditCouponComponent::class)->name('admin.editcoupon');
    // Sales
    Route::get('/admin/sale',AdminSaleComponent::class)->name('admin.onsale');
    //Order
    Route::get('/admin/orders',AdminOrderComponent::class)->name('admin.orders');
    Route::get('/admin/orders/{order_id}',AdminOrderDetailsComponent::class)->name('admin.orderdetails');
    //Product Attribute
    Route::get('/admin/product_attributes',AdminProductAttributeComponent::class)->name('admin.product_attributes');
    Route::get('/admin/product_attribute/add',AdminAddProductAttributeComponent::class)->name('admin.addproduct_attributes');
    Route::get('/admin/product_attribute/edit/{product_attribute_name}',AdminEditProductAttributeComponent::class)->name('admin.editproduct_attributes');
    //



});
// For User
Route::middleware(['auth:sanctum','verified'])->group(function(){


    Route::get('/user/dashboard',UserDashboardComponent::class)->name('user.dashboard');

    // Order
    Route::get('/user/orders',UserOrdersComponent::class)->name('user.orders');
    Route::get('/user/orders/{order_id}',UserOrderDetailsComponent::class)->name('user.orderdetails');
    // Review
    Route::get('/user/review/{order_item_id}',UserReviewComponent::class)->name('user.review');

    // Change Password
    Route::get('/user/change-password',UserChangePasswordComponent::class)->name('user.changepassword');
    // Profile
    Route::get('/user/profile',UserProfileComponent::class)->name('user.profile');
    Route::get('/user/edit',UserEditProfileComponent::class)->name('user.profile_edit');
    });
