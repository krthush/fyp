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

Route::get('/home', function () {
    return view('welcome');
});

Auth::routes();

// Use the built in auth middleware to allow only logged in users
Route::group(['middleware' => ['auth']], function() {
    // Routes that need auth:

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

	// super admin routes
	Route::get('/superadmin','SuperAdminController@show')->name('superadmin');
	Route::get('/superadmin/export-users','SuperAdminController@exportUsers')->name('export-users');
	Route::get('/superadmin/export-projects','SuperAdminController@exportProjects')->name('export-projects');
	Route::get('/superadmin/export-selected-project-users','SuperAdminController@exportSelectedProjectUsers')->name('export-selected-project-users');
	Route::get('/superadmin/toggle-project-viewing','SuperAdminController@toggleProjectViewing')->name('toggle-project-viewing');
	Route::get('/superadmin/toggle-project-selection','SuperAdminController@toggleProjectSelection')->name('toggle-project-selection');
	Route::get('/superadmin/toggle-project-first-matching','SuperAdminController@toggleProjectFirstMatching')->name('toggle-project-first-matching');
	Route::get('/superadmin/toggle-project-all-matching','SuperAdminController@toggleProjectAllMatching')->name('toggle-project-all-matching');
});

