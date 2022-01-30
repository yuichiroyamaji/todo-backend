<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Api','middleware' => ['auth.basic', 'cors', 'logs']], function() {

    // Userルート
    Route::get('/users', 'UserController@index')->name('user.index');

    // Todoタスクルート
    Route::apiResource('/todo', 'ToDoController')
            ->only(['index', 'store', 'update', 'destroy'])
            ->parameters(['todo' => 'task_id']);

});
