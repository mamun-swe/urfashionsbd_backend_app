<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Auth API's
Route::post('/login', 'Api\Auth\AuthController@Login');
Route::post('/register', 'Api\Auth\AuthController@Register');
Route::get('/me', 'Api\Auth\AuthController@Me');
Route::get('/logout', 'Api\Auth\AuthController@Logout');

// Admin API's
Route::group(['prefix' => 'admin', 'middleware' => ['admin']], function () {
    Route::get('dashboard', 'Api\Admin\DashboardController@index'); 
    Route::get('dashboard/report/{year}', 'Api\Admin\DashboardController@chartReport');

    Route::apiResource('/category', 'Api\Admin\CategoryController');
    Route::apiResource('/product', 'Api\Admin\ProductController');
    Route::post('/product/basic-file/{id}', 'Api\Admin\ProductController@updateBasicFile');
    Route::post('/product/additional-image', 'Api\Admin\ProductController@updateAdditionalFiles');
    Route::delete('/product/additional-image/{id}', 'Api\Admin\ProductController@destroyImage');
    Route::get('/search/{sku}/products', 'Api\Admin\ProductController@searchProduct');

    Route::apiResource('/coupon', 'Api\Admin\CouponController');
    Route::apiResource('slider', 'Api\Admin\SliderController');
    Route::get('subscribers', 'Api\SubscriberController@index');
    Route::get('orders', 'Api\Admin\OrderController@index');
    Route::post('orders/{id}', 'Api\Admin\OrderController@changeStatus');
    Route::get('review', 'Api\VisitorPages\ReviewController@index');
    Route::post('review/{id}', 'Api\VisitorPages\ReviewController@changeStatus');

    // user and admin info
    Route::get('/users', 'Api\Admin\AdminUserController@index');
    Route::post('/users', 'Api\Admin\AdminUserController@store');
    Route::put('/users/{id}', 'Api\Admin\AdminUserController@update');
    Route::delete('/users/{id}', 'Api\Admin\AdminUserController@destroy');
    Route::put('/update/{id}/profile', 'Api\Admin\AdminUserController@adminProfileUpdate');
    Route::post('/update/{id}/password', 'Api\Admin\AdminUserController@updateAdminPassword');
});

// User API's
Route::group(['prefix' => 'user', 'middleware' => ['user']], function () {
    Route::post('/update/password', 'Api\User\ProfileController@updateUserPassword');
    Route::post('/update/account', 'Api\User\ProfileController@updateUserAccountDetails');
    Route::post('/update/{id}/password', 'Api\User\ProfileController@updateUserPassword');
    Route::get('/orders/{id}/{email}', 'Api\User\ProfileController@myOrders');
});

// Website API's
Route::group(['prefix' => 'website'], function () {
    Route::get('', 'Api\VisitorPages\HomePageController@index');
    Route::get('/categories', 'Api\VisitorPages\HomePageController@categoriesWithChildrens');
    Route::get('/category/{id}/products', 'Api\VisitorPages\HomePageController@productsOfCategory');
    Route::get('/product/{id}/show', 'Api\VisitorPages\HomePageController@singleProduct');
    Route::get('/search/{key}', 'Api\VisitorPages\HomePageController@searchProducts');
    Route::get('/coupon/{code}', 'Api\VisitorPages\HomePageController@getCoupon');
    Route::post('/confirmorder', 'Api\VisitorPages\HomePageController@confirmOrder');
    Route::post('/review', 'Api\VisitorPages\ReviewController@create');

    Route::post('/subscribe', 'Api\SubscriberController@create');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
