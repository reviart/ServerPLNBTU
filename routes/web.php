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

/*Route::get('/', function () {
    return view('welcome');
});*/
Route::get('/', 'PublicController@index')->name('public.file');

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');//profile page

Route::prefix('public')->group(function () {
  Route::get('file', 'PublicController@index')->name('public.file');
  Route::post('find', 'PublicController@find')->name('public.file.find');
  Route::get('download/{id}', 'PublicController@download')->name('public.file.download');
});

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
  Route::post('find', 'FolderController@find')->name('folder.find');
  Route::get('store', 'FolderController@create')->name('folder.store');
  Route::post('store', 'FolderController@store')->name('folder.store.submit');
  Route::get('edit/{id}', 'FolderController@show')->name('folder.edit');
  Route::put('saveEdit/{id}', 'FolderController@update')->name('folder.edit.submit');
  Route::delete('destroy/{id}', 'FolderController@destroy')->name('folder.destroy');
});

Route::prefix('file')->group(function () {
  Route::get('/', 'FileController@index')->name('file.index');
  Route::get('detail/{id}', 'FileController@detail')->name('file.detail');
  Route::post('find', 'FileController@find')->name('file.find');
  Route::get('store', 'FileController@create')->name('file.store');
  Route::post('store', 'FileController@store')->name('file.store.submit');
  Route::get('edit/{id}', 'FileController@show')->name('file.edit');
  Route::put('saveEdit/{id}', 'FileController@update')->name('file.edit.submit');
  Route::delete('destroy/{id}', 'FileController@destroy')->name('file.destroy');
  Route::get('download/{id}', 'FileController@download')->name('file.download');
});
