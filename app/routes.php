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

//Route::get('addresses.create', 'AddressesController@create');
Route::resource('customers.addresses', 'AddressesController');


