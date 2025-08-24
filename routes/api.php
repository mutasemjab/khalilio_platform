<?php

namespace App\Http\Controllers\Api\v1\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\User\AuthController;
use App\Http\Controllers\Api\v1\User\OrderController;
use App\Http\Controllers\Api\v1\User\DeliveryController;
use App\Http\Controllers\Api\v1\User\FavouriteController;
use App\Http\Controllers\Api\v1\User\CategoryController;
use App\Http\Controllers\Api\v1\User\CouponController;
use App\Http\Controllers\Api\v1\User\CartController;
use App\Http\Controllers\Api\v1\User\ProductReviewController;
use App\Http\Controllers\Api\v1\User\UserAddressController;
use App\Http\Controllers\Api\v1\User\PageController;
use App\Http\Controllers\Api\v1\User\ForgotPasswordController;
use App\Http\Controllers\Api\v1\User\BrandController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route unAuth
Route::group(['prefix' => 'v1/user'], function () {


    Route::get('/pages/{type}', [PageController::class,'index']);

    Route::get('/business_type', [AuthController::class, 'get_business_type']); // Done
    Route::get('/banners', [BannerController::class, 'index']); // Done
    Route::get('/brands', [BrandController::class, 'index']); // Done
    Route::get('/brands/{id}/products', [BrandController::class, 'getBrandProduct']); // Done

    //---------------- Auth --------------------//
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    // Mobile app password reset flow
    Route::post('/password/request-reset', [ForgotPasswordController::class, 'requestReset']);
    Route::post('/password/verify-code', [ForgotPasswordController::class, 'verifyCode']);
    Route::post('/password/reset', [ForgotPasswordController::class, 'resetPassword']);

    //Route unAuth
    Route::get('/latest',  [ProductController::class, 'latest']); // Done

    Route::get('/offers', [ProductController::class, 'offers']); // Done


    //----------------- Products ------------------------------//
    Route::get('/products', [ProductController::class, 'index']); // Done
    Route::get('/products/{id}', [ProductController::class, 'productDetails']); // Done

    //Category product
    Route::get('/categories/{id}/products',  [CategoryController::class, 'getProducts']); // Done
    Route::get('/categories', [CategoryController::class, 'index']); // Done







    // Auth Route
    Route::group(['middleware' => ['auth:user-api']], function () {

        Route::post('/mobile_verified', [AuthController::class, 'mobileVerified']);
        Route::post('/update_profile', [AuthController::class, 'updateProfile']);
        Route::post('/change_user_type', [AuthController::class, 'change_user_type']);
        Route::post('/delete_account', [AuthController::class, 'deleteAccount']);
        Route::get('/user_profile', [AuthController::class, 'userProfile']);

        //Notification
        Route::get('/notifications', [AuthController::class, 'notifications']);

        //--------------- Favourite ------------------------//
        Route::get('/favourites', [FavouriteController::class, 'index']); // Done
        Route::post('/favourites', [FavouriteController::class, 'store']); // Done

        //--------------- Coupon ------------------------//
        Route::post('/applyCoupon', [CouponController::class, 'applyCoupon']); // Done


        Route::get('/delivery', [DeliveryController::class, 'index']); // Done

        //-------------------- Address ------------------------//
        Route::get('/addresses', [UserAddressController::class, 'index']); // Done
        Route::post('/addresses', [UserAddressController::class, 'store']); // Done
        Route::post('/addresses/{address_id}', [UserAddressController::class, 'update']); // Done
        Route::delete('/addresses/{id}', [UserAddressController::class, 'destroy']); // Done
        //----------- Product Review ----------------------//
        Route::post('/product-reviews', [ProductReviewController::class, 'store']); // Done


        //----------------- Cart -------------------------------//
        Route::get('/carts', [CartController::class, 'index']); // Done
        Route::post('/carts', [CartController::class, 'store']); // Done
        Route::post('/carts/{id}', [CartController::class, 'update']); // Done
        Route::delete('/carts/{id}', [CartController::class, 'destroy']); // Done
        Route::delete('cart', [CartController::class, 'destroyAll']);

        //---------------------- Order -----------------------//
        Route::get('/orders', [OrderController::class, 'index']); // Done
        Route::get('/orders/{id}', [OrderController::class, 'show']);
        Route::post('/orders/{id}', [OrderController::class, 'update']);
        Route::post('/orders', [OrderController::class, 'store']); // Done
        Route::get('/orders/{id}/cancel', [OrderController::class, 'cancel_order']);
        Route::post('/orders/{id}/refund', [OrderController::class, 'refund']);
        Route::get('buy-again', [OrderController::class, 'buyAgain']);

    });
});
