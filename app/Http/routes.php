<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/dashboard', 'HomeController@index');

    Route::get('/post/{id}', 'PostsController@getPost');

    Route::get('/posts', 'PostsController@index');
    Route::get('/posts/create', 'PostsController@create');

    Route::post('/posts/approveCommentPostback', 'PostsController@approveCommentPostback');
    Route::post('/posts/unapproveCommentPostback', 'PostsController@unapproveCommentPostback');
    Route::post('/posts/deleteCommentPostback', 'PostsController@deleteCommentPostback');
    Route::post('/posts/createPostback/', [
        'uses' => 'PostsController@createPostback', 'as' => 'createPostback'
    ]);
    Route::post('/posts/createCommentPostback/', [
        'uses' => 'PostsController@createCommentPostback', 'as' => 'createCommentPostback'
    ]);


});
