<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Default Route: Test API [GET Method]
Route::get('/', [ 'uses' => 'TestController@MakeTest', 'as' => 'test.maketest' ]);

// Login
Route::post('/login', [ 'uses' => 'AuthController@Login', 'as' => 'auth.login' ]);

// Test API
Route::get('/test', [ 'uses' => 'TestController@MakeTest', 'as' => 'test.maketest.get' ]);
Route::post('/test', [ 'uses' => 'TestController@MakeTest', 'as' => 'test.maketest.post' ]);

Route::group([ 'middleware' => 'authorized' ], function(){
    // Logout
    Route::get('/logout', [ 'uses' => 'AuthController@Logout', 'as' => 'auth.logout' ]);

    // User
    Route::get('/users/me', [ 'uses' => 'AdminUsersController@Me', 'as' => 'users.me' ]);
    // Admin User
    Route::get('/users/administrators', [ 'uses' => 'AdminUsersController@List', 'as' => 'users.administrators.list' ]);
    Route::get('/users/administrators/{user_id}', [ 'uses' => 'AdminUsersController@Get', 'as' => 'users.administrators.get' ]);
    Route::put('/users/administrators', [ 'uses' => 'AdminUsersController@Create', 'as' => 'users.administrators.create' ]);
    Route::post('users/administrators/{user_id}', [ 'uses' => 'AdminUsersController@Update', 'as' => 'users.administrators.update' ]);
    Route::delete('/users/administrators/{user_id}', [ 'uses' => 'AdminUsersController@Delete', 'as' => 'users.administrators.delete' ]);

    // Privileges Groups
    Route::get('/privileges/groups', [ 'uses' => 'PrivilegesController@ListGroups', 'as' => 'privileges.groups.list' ]);
    Route::get('/privileges/groups/{privilege_group_id}', [ 'uses' => 'PrivilegesController@GetGroup', 'as' => 'privileges.groups.get' ]);

    // Status
    Route::get('/club/status', [ 'uses' => 'ClubController@GetStatus', 'as' => 'club.status' ]);
});