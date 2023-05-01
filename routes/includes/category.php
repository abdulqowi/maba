<?php

use Illuminate\Support\Facades\Route;

Route::post('/category/create', 'CategoryController@store');
Route::post('/category/edit/{id}', 'CategoryController@update');
Route::delete('/category/{id}', 'CategoryController@destroy');