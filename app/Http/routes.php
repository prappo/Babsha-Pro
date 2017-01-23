<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('/', function () {
    return view('welcome');
});
Route::get('/prappo', 'Prappo@index');
Route::get('/payment/{userID}', 'PayPalController@paymentRequest');
Route::get('/success/payment/{userId}', 'PayPalController@success');
Route::get('/cancel/payment', 'PayPalController@cancel');
Route::get('/payment', 'PayPalController@index');
Route::get('/checkout/{user}', 'Checkout@index');
Route::get('/info/update/{id}', 'Customer@infoUpdate');
Route::post('/info/update', 'Customer@updateInfo');
Route::any('/hook', 'Hook@index');
Route::get('/policy/bot', 'Policy@bot');
Route::get('/policy/bot/legal', 'Policy@botLegal');

Route::get('/public/order/{orderId}', 'Customer@viewOrderPublic');

Route::auth();

Route::get('/home', 'HomeController@index');
Route::get('/addproduct', 'Products@addProductIndex');
Route::post('/addproduct', 'Products@addProduct');
Route::get('/addcategory', 'Category@addCategoryIndex');
Route::post('/addcategory', 'Category@addCategory');
Route::get('/showproducts', 'Products@showProducts');
Route::get('/showcategory', 'Category@viewCategoryIndex');
Route::post('/product/status', 'Products@statusUpdate');
Route::post('/iup', 'Send@iup');
Route::post('/delete/product', 'Products@deleteProducts');
Route::post('/update/product', 'Products@editProducts');
Route::get('/update/product/{id}', 'Products@updateProduct');
Route::get('/customers', 'Customer@index');
Route::get('/orders', 'Orders@orderList');
Route::get('/order/{orderId}', 'Orders@viewOrder');

Route::post('/send/message', 'Orders@sendMessage');
Route::post('/send/request', 'Orders@requestUpdateAddress');
Route::post('/send/confirmation', 'Orders@orderConfirm');
Route::post('/remove/product', 'Orders@removeProduct');
Route::post('/delete/order', 'Orders@deleteOrder');
Route::post('/cancel/order', 'Orders@cancelOrder');
Route::post('/category/delete', 'Category@delete');
Route::post('/category/edit', 'Category@edit');
Route::get('/orders/history', 'Orders@orderHistory');
Route::get('/earning/history', 'Orders@earningHistory');
Route::get('/earning/history/paypal', 'PayPalController@paypalHistory');
Route::get('/settings', 'Settings@index');
Route::get('/settings/site', 'Settings@siteSettings');
Route::post('/settings/site', 'Settings@updateSiteSettings');
Route::post('/settings', 'Settings@update');
Route::post('/update/translate', 'Settings@updateTranslation');
Route::get('/translate/settings', 'Settings@translation');
Route::get('/bot/settings', 'Settings@bot');
Route::post('/bot/settings', 'Settings@botUpdate');
Route::get('/profile', 'Profile@index');
Route::post('/profile', 'Profile@update');
Route::post('/notify', 'Customer@notify');
Route::post('/customer/delete', 'Customer@delCustomer');
Route::post('/customer/bot', 'Customer@botAction');
Route::get('/fbconnect', 'Settings@fbConnect');
Route::get('/user/list', 'User@userList');
Route::get('/user/{id}', 'User@userEdit');
Route::post('/user/add', 'User@userAdd');
Route::post('/user/edit', 'User@edit');
Route::post('/user/del', 'User@del');
Route::post('/send/to/user', 'Send@toUser');
Route::get('/bot', 'Bot@index');
Route::post('/bot/add', 'Bot@addReply');
Route::post('/bot/del', 'Bot@delReply');
Route::get('/notifications', 'Notification@index');
Route::post('/notifications/delete', 'Notification@delete');
Route::get('/showproducts/woo', 'WooController@viewProducts');
Route::get('/woo/update/product/{id}', 'WooController@updateProduct');
Route::post('/woo/delete/product', 'WooController@deleteProduct');
Route::post('/woo/update/product', 'WooController@updateWooProduct');
Route::get('/woo/addcategory','WooController@addCategoryIndex');
Route::get('/woo/showcategory','WooController@viewCategory');
Route::post('/woo/addcategory','WooController@addCategory');
Route::post('/woo/category/edit','WooController@editCategory');
Route::post('/woo/category/delete','WooController@deleteCategory');
Route::get('/bot/subscribe/{pageId}','Prappo@subscribe');


