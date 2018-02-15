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
    return view('welcome');
});

Route::get('/test', function () {
    $name = "BLAW";
    $folders = DB::table('folders')->where('name', $name)->first();
    echo $folders->id;
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('bidang')->group(function () {
  Route::get('/', 'BidangController@index')->name('bidang.index');
  Route::get('/store', 'BidangController@create')->name('bidang.store');
  Route::post('/store', 'BidangController@store')->name('bidang.store.submit');
  Route::get('edit/{id}', 'BidangController@show')->name('bidang.edit');
  Route::put('saveEdit/{id}', 'BidangController@update')->name('bidang.edit.submit');
  Route::delete('destroy/{id}', 'BidangController@destroy')->name('bidang.destroy');
  Route::delete('destroyAll/{id}', 'BidangController@destroyAll')->name('bidang.destroyAll');
});

Route::prefix('folder')->group(function () {
  Route::get('/', 'FolderController@index')->name('folder.index');
  Route::get('/store', 'FolderController@create')->name('folder.store');
  Route::post('/store', 'FolderController@store')->name('folder.store.submit');
  Route::get('edit/{id}', 'FolderController@show')->name('folder.edit');
  Route::put('saveEdit/{id}', 'FolderController@update')->name('folder.edit.submit');
  Route::delete('destroy/{id}', 'FolderController@destroy')->name('folder.destroy');
  Route::delete('destroyAll/{id}', 'FolderController@destroyAll')->name('folder.destroyAll');
});

Route::prefix('file')->group(function () {
  Route::get('/', 'FileController@index')->name('file.index');
  Route::get('/store', 'FileController@create')->name('file.store');
  Route::post('/store', 'FileController@store')->name('file.store.submit');
  Route::get('/edit/{id}', 'FileController@show')->name('file.edit');
  Route::put('/saveEdit/{id}', 'FileController@update')->name('file.edit.submit');
  Route::delete('/destroy/{id}', 'FileController@destroy')->name('file.destroy');
});
