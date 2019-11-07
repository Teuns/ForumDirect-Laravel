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

Auth::routes();

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index');

Route::get('/', 'IndexController@index')->name('index');

Route::get('/subforums/show/{id}-{slug}', 'SubforumsController@show');

Route::get('/users', 'UsersController@index');

Route::get('/direct/inbox', 'DirectController@inbox');

Route::get('/direct/outbox', 'DirectController@outbox')->name('outbox');

Route::get('/direct/view/{id}', 'DirectController@view');

Route::post('/direct/reply/{id}', 'DirectController@reply');

Route::match(array('GET', 'POST'),'/direct/create', 'DirectController@create');

Route::get('/api/v1/users/name/{username}', 'ApiController@getUserNameCorrectly');

Route::get('/api/v1/users/roles/{username}', 'ApiController@getUserRoleByName');

Route::get('/api/v1/users/id/{username}', 'ApiController@getUserIdByName');

Route::get('/api/v1/users/id', 'ApiController@getUserId');

Route::post('/api/v1/report', 'ApiController@report');

Route::post('api/v1/like', 'ApiController@like');

Route::get('/api/v1/posts/recent', 'ApiController@getRecentPosts');

Route::get('/modcp', 'ModController@index');

Route::get('/modcp/posts', 'ModController@posts');

Route::match(array('GET', 'POST'),'/modcp/edit-post/{id}', 'ModController@editPost');

Route::get('/modcp/threads', 'ModController@threads');

Route::match(array('GET', 'POST'),'/modcp/edit-thread/{id}', 'ModController@editThread');

Route::get('/modcp/report/{id}', 'ModController@report');

Route::get('/modcp/report/delete/{id}', 'ModController@deleteReport');

Route::get('/modcp/posts/delete/{id}', 'ModController@deletePost');

Route::get('/modcp/threads/delete/{id}', 'ModController@deleteThread');

Route::match(array('GET', 'POST'),'/modcp/warnUser', 'ModController@warnUser');

Route::get('/modcp/ban/{id}', 'ModController@banUser');

Route::get('/modcp/unban/{id}', 'ModController@unbanUser');

Route::get('/admincp', 'AdminController@index');

Route::get('/admincp/users', 'AdminController@users');

Route::match(array('GET', 'POST'),'/admincp/users/edit/{id}', 'AdminController@editAccount');

Route::get('/admincp/forums', 'AdminController@forums')->name('admincp_forums');

Route::match(array('GET', 'POST'),'/admincp/forums/edit/{id}', 'AdminController@editForum');

Route::match(array('GET', 'POST'),'/admincp/forums/create/', 'AdminController@createForum');

Route::get('/admincp/forums/delete/{id}', 'AdminController@deleteForum');

Route::match(array('GET', 'POST'),'/admincp/subforums/create', 'AdminController@createSubforum');

Route::get('/admincp/subforums/delete/{id}', 'AdminController@deleteSubforum');

Route::match(array('GET', 'POST'),'/admincp/subforums/edit/{id}', 'AdminController@editSubforum');

Route::match(array('GET', 'POST'),'/users/edit-account', 'UsersController@editAccount');

Route::match(array('GET', 'POST'),'/users/upload-avatar', 'UsersController@uploadAvatar');

Route::get('/users/{name}', 'UsersController@profile');

Route::get('messages', 'ChatsController@fetchMessages');
Route::post('messages', 'ChatsController@sendMessage');

Route::group(['prefix' => 'threads'], function () {
    Route::get('/drafts', 'ThreadController@drafts')
        ->name('list_drafts_threads')
        ->middleware('auth');
    Route::get('/show/{id}-{slug}', 'ThreadController@show')
        ->name('show_thread');
    Route::get('/create/{id}', 'ThreadController@create')
        ->name('create_thread')
        ->middleware('can:create-thread');
    Route::post('/create/{id}', 'ThreadController@store')
        ->name('store_thread')
        ->middleware('can:create-thread');
    Route::get('/edit/{thread}', 'ThreadController@edit')
        ->name('edit_thread')
        ->middleware('can:update-thread,thread,can:edit-thread');
    Route::post('/edit/{thread}', 'ThreadController@update')
        ->name('update_thread')
        ->middleware('can:update-thread,thread');
    Route::get('/publish/{thread}', 'ThreadController@publish')
        ->name('publish_thread')
        ->middleware('can:publish-thread');
});

Route::group(['prefix' => 'posts'], function () {
    Route::get('/drafts', 'PostController@drafts')
        ->name('list_drafts_posts')
        ->middleware('auth');
    Route::get('/create/{id}', 'PostController@create')
        ->name('create_post')
        ->middleware('can:create-post');
    Route::post('/create/{id}', 'PostController@store')
        ->name('store_post')
        ->middleware('can:create-post');
    Route::get('/edit/{post}', 'PostController@edit')
        ->name('edit_post')
        ->middleware('can:update-post,post');
    Route::post('/edit/{post}', 'PostController@update')
        ->name('update_post')
        ->middleware('can:update-post,post');
    Route::get('/publish/{post}', 'PostController@publish')
        ->name('publish_post')
        ->middleware('can:publish-post');
});