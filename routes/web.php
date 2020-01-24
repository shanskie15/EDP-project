<?php

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

Route::get('/', function () {
    return redirect('/home');
});

Route::get('/sales','HomeController@sales')->name('sales');

Auth::routes();

// Loans
Route::prefix('loans')->group(function(){
    Route::post('/order','LoansController@loanOrder')->name('loan');
    Route::post('/pay','LoansController@payLoan')->name('pay.loan');
    Route::get('/payment/{id}','LoansController@create_payment');
    Route::get('/','LoansController@index');
    Route::delete('/delete/{id}','LoansController@delete');
});



// Payments
Route::prefix('payments')->group(function(){
    Route::get('/order/{id}','PaymentsController@createOrder');
    Route::get('/{id}/order','PaymentsController@orderPayments');
    Route::post('/order','PaymentsController@payOrder')->name('pay.order');
});

// Orders
Route::prefix('orders')->group(function(){
    Route::delete('/soft/{id}','OrdersController@delete');
    Route::get('/products/{id}','OrdersController@products')->name('order.products'); // products
    Route::get('/cart/{id}','OrdersController@cart')->name('order.cart'); // order cart
    Route::post('/ship','OrdersController@ship')->name('order.ship'); // ship order
    Route::post('/add_to_cart','OrdersController@addToCart')->name('order.addcart'); // add to cart
    Route::post('/cart/quantity','OrdersController@editQuantity')->name('cart.quantity'); // increase / decrease item
    Route::delete('/item/{id}','OrdersController@itemDestroy'); // delete item
});
Route::resource('orders','OrdersController');
// Clients
Route::resource('clients','ClientsController');
Route::prefix('clients')->group(function(){
    Route::delete('/soft/{id}','ClientsController@delete');
});
// Divisions
Route::resource('divisions','DivisionsController');
Route::prefix('divisions')->group(function(){
    Route::delete('/soft/{id}','DivisionsController@delete');
});
// Employees
Route::resource('employees','EmployeesController');
Route::prefix('employees')->group(function(){
    Route::delete('/soft/{id}','EmployeesController@delete');
});
// Products
Route::resource('products','ProductsController');
Route::prefix('products')->group(function(){
    Route::delete('/soft/{id}','ProductsController@delete');
    Route::post('/add_items','ProductsController@addItems')->name('products.add_items');
});


Route::get('/home', 'HomeController@index')->name('home');
