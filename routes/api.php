<?php

use Illuminate\Support\Facades\Route;

require_once('includes/auth.php');

Route::get('/category', 'CategoryController@index');
Route::get('/category/{id}', 'CategoryController@show');
Route::get('/product','ProductController@index'); 
Route::get('/product/{id}','ProductController@show');
Route::group(
    ['middleware' => 'auth:api'],
    function () {
        Route::post('/user/edit', 'UserController@update');
        Route::post('/user/editpassword', 'UserController@changePassword');
        Route::get('/user/{id}', 'UserController@show');
        Route::middleware('admin')->group(function () {
            require_once('includes/user.php');
            require_once('includes/category.php');
            require_once('includes/product.php');
        });
    }
);
