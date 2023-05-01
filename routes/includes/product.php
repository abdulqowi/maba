<?php

use Illuminate\Support\Facades\Route;

Route::post('/product/create','ProductController@store');
Route::post('/product/edit/{id}', 'ProductController@update');
Route::delete('/product/{id}', 'ProductController@destroy');