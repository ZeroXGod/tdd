<?php

// Route::resource('threads', 'ThreadsController');
Route::get('threads','ThreadsController@index');
Route::get('threads/create','ThreadsController@create');
Route::get('threads/{channel}/{thread}','ThreadsController@show');
Route::post('threads','ThreadsController@store');
Route::post('/threads/{channel}/{thread}/replies', 'RepliesController@store');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
