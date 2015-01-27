<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

Route::get('/user', function()
{
	echo "This is the 'user' route.";
});

Route::model('tasks', 'Task');
Route::model('projects', 'Project');
Route::model('customers', 'Customer');
Route::model('addresses', 'Address');
Route::model('products', 'Product');
Route::model('items', 'Item');
Route::model('orders', 'Order');

Route::bind('tasks', function($value, $route) {
	return Task::whereSlug($value)->first();
});

Route::bind('projects', function($value, $route) {
	return Project::whereSlug($value)->first();
});

/*
Route::bind('customers', function($value, $route) {
    return Customer::find($value)->first();
});
*/

Route::resource('projects', 'ProjectsController');

//Route::resource('tasks', 'TasksController');
Route::resource('projects.tasks', 'TasksController');

//Route::resource('customers/profile', 'CustomersController@profile');
Route::get('profile', 'CustomersController@profile')->before('auth');
Route::post('profile', 'CustomersController@profile');
Route::get('logout', 'CustomersController@logout')->before('auth');
Route::get('login', 'CustomersController@login')->before('guest');
Route::post('login', 'CustomersController@login');
Route::resource('customers', 'CustomersController');
//Route::resource('customer', 'CustomersController');

Route::resource('customers.addresses', 'AddressesController');

// If user includes 4-digit year, call the 'index' method (instead of the 'show' method).
Route::get('products/{id}', 'ProductsController@index')->where('id', '^20[01][0-9]$');
Route::post('products/addtocart', 'ProductsController@addToCart');
Route::post('cart/add', array( 'as' => 'cart-add', 'uses' => 'ProductsController@addToCart'));
Route::get('cart/empty', array( 'as' => 'cart-empty', 'uses' => 'ProductsController@emptyCart'));
Route::get('cart/remove/{id}', array( 'as' => 'cart-remove', 'uses' => 'ProductsController@removeFromCart'));
Route::get('cart/show-cart', array( 'as' => 'show-cart', 'uses' => 'ProductsController@showCart'));
Route::resource('products', 'ProductsController');

Route::get('items/download', array( 'as' => 'download', 'uses' => 'ItemsController@download' ));
//Route::post('items/search', array('as' => 'search', 'uses' => 'ItemsController@search'));
Route::get('items/search', array('as' => 'search', 'uses' => 'ItemsController@search'));
Route::resource('items', 'ItemsController');

Route::get('orders.checkout', array( 'as' => 'checkout', 'uses' => 'OrdersController@checkout'));
Route::resource('orders', 'OrdersController');
