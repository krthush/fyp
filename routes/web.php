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

Auth::routes();

// project routes
Route::get('/dashboard', 'ProjectController@dashboard')->name('dashboard');
Route::get('/projects', 'ProjectController@projects')->name('projects');
Route::get('/projects/{project}','ProjectController@show')->name('project');
Route::post('/projects/new','ProjectController@store')->name('new-project');
Route::delete('/projects/delete','ProjectController@destroy')->name('delete-project');
Route::patch('/projects/update/{project}/','ProjectController@update')->name('update-project');
Route::get('projects/match/{project}/{student_id}','ProjectController@match')->name('match-project');
Route::get('projects/unmatch/{project}/{student_id}','ProjectController@unmatch')->name('unmatch-project');

// like routes
Route::get('projects/like/{project}','LikeController@like')->name('like-project');
Route::get('/projects/rankup/{project}/','LikeController@rankup')->name('rankup-project');
Route::get('/projects/rankdown/{project}/','LikeController@rankdown')->name('rankdown-project');
Route::patch('/projects/reorder','LikeController@reorder')->name('reorder-projects');

// search routes
Route::get('/search','ProjectController@search')->name('search-projects');