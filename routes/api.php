<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\{
    Auth\Admin\LoginController as AdminLoginController,    
    Auth\Admin\LogoutController as AdminLogoutController,    
    Auth\Admin\RegisterController as AdminRegisterController,    
    Auth\Admin\PasswordController as AdminPasswordController,    
    Admin\DashboardController as AdminDashboardController,    
    Auth\LoginController,
    Auth\LogoutController,
    Auth\RegisterController,    
    Auth\PasswordController, 
    AddressController,
    CartController,
    CategoryController,
    CategoryProductController,
    ContactController,
    CouponController,
    DailyDealController,
    WeeklyDealController,
    DeviceController,
    DiscountController,
    DivisionController,
    DistrictController,
    FirebaseNotificationController,
    ImageController,
    InventoryController,
    InviteController,
    OfferController,
    OrderController,
    OrderProductDisputeController,
    PaymentController,
    PhoneController,
    ProductController,
    ProductDealController,
    ProductImageController,
    ProductSearchController,
    ProductTagController,
    ShippingController,
    ShippingTypeController,
    ShipperController,
    SlideController,
    TagController,
    UserController,    
    WishlistController,
    OrderStatusController as AdminOrderStatusController,
    Admin\CategoryProductController as AdminCategoryProductController,
    Admin\ContactController as AdminContactController,
    Admin\DiscountController as AdminDiscountController,
    Admin\DailyDealController as AdminDailyDealController,
    Admin\OfferController as AdminOfferController,
    Admin\OfferProductController as AdminOfferProductController,
    Admin\OrderController as AdminOrderController,
    Admin\OrderReportController as AdminOrderReportController,
    Admin\ProductReportController as AdminProductReportController,
    Admin\SlideController as AdminSlideController,
    Admin\ShippingController as AdminShippingController,
    Admin\ShippingTypeController as AdminShippingTypeController,
    Admin\ShippingChargeController as AdminShippingChargeController,
    Admin\TagController as AdminTagController,
    Admin\TypeController as AdminTypeController,
    Admin\UserController as AdminUserController,
    Admin\UserReportController as AdminUserReportController,
    Admin\WeeklyDealController as AdminWeeklyDealController,
};

// Auth Admin ...
Route::prefix('admin')->group(function () {    
    Route::post('/login', AdminLoginController::class)->name('admin.login');
    Route::post('/logout', AdminLogoutController::class)->name('admin.logout');
    Route::post('/register', AdminRegisterController::class)->name('admin.register');
        
    Route::group(['middleware' => ['auth:sanctum', 'auth:admin']], function() {

        Route::get('/dashboard', AdminDashboardController::class);
        Route::get('/password', [AdminPasswordController::class, 'check'])->name('check.admin.password');
        Route::post('/password', [AdminPasswordController::class, 'update'])->name('update.admin.password');
        
        // Order Controller
        Route::get('/orders/{status}', [AdminOrderController::class, 'ordersBy']);
        Route::get('/orders/{order}/products', [AdminOrderController::class, 'productsBy']);
        Route::get('/orders/{order}/logs', [AdminOrderController::class, 'orderLogsBy']);
        Route::patch('/orders/update-status/{order}', [AdminOrderController::class, 'updateOderStatus']);

        Route::apiResource('/orders', AdminOrderController::class);        

        /// Order Status
        Route::apiResource('/order/statuses', AdminOrderStatusController::class);
        
        //Shippers
        Route::apiResource('/shippers', ShipperController::class);

        //Slides
        Route::apiResource('/slides', AdminSlideController::class);

        // Tags...
        Route::apiResource('/tags', AdminTagController::class);
        Route::get('/tags/categories/{id}', [AdminTagController::class, 'tagsByCategory']);
        //Report
        Route::get('/reports/users/registered/{period}', [AdminUserReportController::class, 'usersRegisteredFor']);

        Route::get('/reports/orders/received/{period}', [AdminOrderReportController::class, 'ordersReceivedFor']);
        
        Route::get('/reports/orders/hourely/received/{period}', [AdminOrderReportController::class, 'ordersReceivedHourelyFor']);

        Route::get('/reports/categories/products-sales/{period}', [AdminProductReportController::class, 'byCategory']);

        Route::get('/categories/{category}/products', [AdminCategoryProductController::class, 'index']);

        //offers
        Route::get('/offers/types/{type}', [AdminOfferController::class, 'offersBy']);
        
        Route::apiResource('offers', AdminOfferController::class);

        //offer products
        Route::delete('offers/{offer}/products/{product:id}', [AdminOfferProductController::class, 'destroyProduct']);
        Route::patch('offers/{offer}/products/{product:id}', [AdminOfferProductController::class, 'updateProduct']);

        Route::apiResource('offer/products', AdminOfferProductController::class);

        // Discounts 
        Route::apiResource('/discounts', AdminDiscountController::class);

        //DailyDeal
        Route::apiResource('/daily/deals', AdminDailyDealController::class);

        //WeeklyDeal
        Route::apiResource('/weekly/deals', AdminWeeklyDealController::class);
        
        //Types
        Route::apiResource('types', AdminTypeController::class);

        // //Shipping Cities
        Route::apiResource('shippings', AdminShippingController::class);
        Route::patch('/shippings/types/{shipping}', [AdminShippingTypeController::class, 'update']);
        Route::patch('/shippings/charges/{shipping}', [AdminShippingChargeController::class, 'update']);
        

        //Categories Icon/Image
        // Route::delete('/categories/{category}/icons/{name}', [CategoryController::class, 'destroyIcon']);
        Route::delete('/categories/{category}/icon', [CategoryController::class, 'destroyIcon']);
        // Route::delete('/categories/{category}/images/{name}', [CategoryController::class, 'destroyImage']);
        Route::delete('/categories/{category}/image', [CategoryController::class, 'destroyImage']);

        //Invites
        // Route::apiResource('/invites', InviteController::class);
        // Route::get('/invites', [
        //     InviteController::class, 'index'])->middleware(['type:super-admin'])->name('invites');
        Route::get('/invites', [
            InviteController::class, 'index'])->middleware(['type:super-admin'])->name('invites');
        Route::post('/invite', [InviteController::class, 'process'])->middleware(['type:super-admin'])->name('process');
        Route::delete('/invites/{invite}', [
            InviteController::class, 'destroy'])->middleware(['type:super-admin']);
        // Route::get('accept/{token}', [InviteController::class, 'accept'])->name('accept');

        Route::get('/divisions', DivisionController::class);
        Route::get('/districts', DistrictController::class);

        //contacts
        Route::apiResource('/contacts', AdminContactController::class);
    });
        
    Route::get('/invite/{token}', [
            InviteController::class, 'show']);
    
    Route::get('accept/{token}', [InviteController::class, 'accept'])->name('accept');
    // User ...
    Route::get('/user', AdminUserController::class)->middleware(['auth:sanctum'])->name('admin.user');
});
    
    //DailyDeal
    Route::apiResource('daily/deals', DailyDealController::class);

    //WeeklyDeal
    Route::apiResource('/weekly/deals', WeeklyDealController::class);
    
    //offers
    Route::apiResource('/offers', OfferController::class);
    
    //FCM Notifications
    Route::post('app/notifications', [FirebaseNotificationController::class, 'send_push_notification']);
    
    //Devices
    Route::apiResource('devices', DeviceController::class);



// Auth ...
Route::post('/login', LoginController::class)->name('login');
Route::post('/register', RegisterController::class)->name('register');
Route::post('/logout', LogoutController::class)->name('logout');

// Auth User
Route::group(['middleware' => ['auth:sanctum']], function() {

    // User ...
    Route::get('/user', UserController::class)->name('user');

    Route::get('/user/password', [PasswordController::class, 'check'])->name('check.user.password');
    Route::post('/user/password', [PasswordController::class, 'update'])->name('update.user.password');

    Route::post('/user/phone', [PhoneController::class, 'update']);

    //User Address
    Route::apiResource('/user/addresses', AddressController::class);

    // Route::get('/user/addresses', [AddressController::class, 'index']);

    Route::get('/user/active/addresses', [AddressController::class, 'addresses']);
    Route::get('/user/shipping-address', [AddressController::class, 'shippingAddress']);
    
    // Order Controller
    Route::apiResource('/orders', OrderController::class);
    
    // Wishlist
    Route::apiResource('/wishlists', WishlistController::class);
    
    
    //ShippingTypes
    Route::get('/shipping-types', [ShippingTypeController::class, 'shippingTypesByCity']);
    
    // Shipping Cities
    Route::get('/shipping-cities', ShippingController::class);
});

// Route::apiResource('/wishlists', WishlistController::class);

// Orders
Route::get('/orders/{uuid}/invoice', [OrderController::class, 'invoice']);

Route::get('/orders/{uuid}/products', [OrderController::class, 'productsBy']);
// // User ...
// Route::get('/user', UserController::class)->middleware(['auth:sanctum'])->name('user');

// //User Address
// Route::get('/user/addresses', AddressController::class)->middleware(['auth:sanctum']);

//OrderProducts
Route::post('/orders/{order}/cancel', [OrderProductDisputeController::class, 'cancel']);
Route::post('/orders/{order}/cancel-items', [OrderProductDisputeController::class, 'cancelItems']);

//payment
Route::apiResource('/payments', PaymentController::class);

// Route::post('/payments/initiate', [PaymentController::class, 'payment']);
Route::post('/payments/success', [PaymentController::class, 'success']);
Route::post('/payments/fail', [PaymentController::class, 'fail']);
Route::post('/payments/cancel', [PaymentController::class, 'cancel']);
Route::post('/payments/ipn', [PaymentController::class, 'ipn']);

//Coupon
Route::apiResource('/coupons',  CouponController::class);

// Discounts 
Route::apiResource('/discounts', DiscountController::class);

//Category
Route::apiResource('categories', CategoryController::class);

//Inventory
Route::apiResource('inventories', InventoryController::class);

//Product
Route::apiResource('products', ProductController::class);

Route::post('products/coupon', [ProductController::class, 'apply'])->middleware(['auth:sanctum']);

Route::post('products/toggle-wishlist', [ProductController::class, 'toggleWishlist'])->middleware(['auth:sanctum']);

Route::get('/categories/{category}/products', [CategoryProductController::class, 'index'])->name('category/products.index');

//tagProducts
Route::get('/tags/{tag}/products', [TagController::class, 'show']);
// Route::get('products/deals/daily', ProductDealController::class);

// Products Tags
Route::get('/products/{product}/tags', [ProductTagController::class, 'index']);//->middleware(['auth:sanctum', 'verified'])->name('products.images.store');

// Products Photos...
Route::get('/products/{product}/images', [ProductImageController::class, 'show']);//->middleware(['auth:sanctum', 'verified'])->name('products.images.store');
Route::post('/products/{product}/images', [ProductImageController::class, 'store'])->middleware(['auth:sanctum', 'verified'])->name('products.images.store');

Route::put('/products/images/{image:id}', [ProductImageController::class, 'update'])->name('products.images.update');

Route::delete('/products/{product}/images/{image:id}', [ProductImageController::class, 'destroy'])->name('products.images.destroy');

//Cart
Route::apiResource('carts', CartController::class);

//Image
Route::apiResource('images', ImageController::class);

//search
Route::get('/search/items', [ProductSearchController::class, 'index']);

//Slides
Route::apiResource('/slides', SlideController::class);

//Contact
Route::post('/contacts', ContactController::class);

