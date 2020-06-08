<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});
Route::get('/add-employee', function (){
    return view('employee.create');
});

// Employees routes
Route::get('/list-employees', 'EmployeeController@ListEmployees');
Route::get('/edit-details/{id}', 'EmployeeController@EditEmployee');
Route::get('/delete-employee/{id}', 'EmployeeController@deleteEmployee');
Route::get('/employee/{id}', 'EmployeeController@getEmployee');
Route::post('/create-employee', 'EmployeeController@createEmployee');
Route::post('/update-employee/{id}', 'EmployeeController@updateEmployee');
