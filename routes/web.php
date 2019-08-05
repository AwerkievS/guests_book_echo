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

use Illuminate\Routing\Router;

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::group(['prefix' => 'records'], function (Router $router) {
    $router->post('new', 'RecordController@new')->name('new-record');
    $router->post('{id}', 'RecordController@update')->name('update-record')->middleware('EditRecordGuard');
    $router->delete('{id}', 'RecordController@delete')->name('delete-record');
    $router->get('csv', 'RecordController@csv')->name('csv-record');
}
);