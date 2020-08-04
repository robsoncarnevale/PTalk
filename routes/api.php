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
    Route::get('/users/me', [ 'uses' => 'UsersController@Me', 'as' => 'users.me' ]);
    Route::get('/users/all', [ 'uses' => 'UsersController@ListAll', 'as' => 'users.members.view-all' ]);
    Route::get('/users/profile/{user_id}', [ 'uses' => 'UsersController@ViewProfile', 'as' => 'users.profile.view' ])->where(['user_id' => '[0-9]+']);
    // Admin User
    Route::get('/users/administrators', [ 'uses' => 'AdminUsersController@List', 'as' => 'users.administrators.list' ]);
    Route::get('/users/administrators/{user_id}', [ 'uses' => 'AdminUsersController@Get', 'as' => 'users.administrators.get' ])->where(['user_id' => '[0-9]+']);
    Route::put('/users/administrators', [ 'uses' => 'AdminUsersController@Create', 'as' => 'users.administrators.create' ]);
    Route::post('users/administrators/{user_id}', [ 'uses' => 'AdminUsersController@Update', 'as' => 'users.administrators.update' ])->where(['user_id' => '[0-9]+']);
    Route::delete('/users/administrators/{user_id}', [ 'uses' => 'AdminUsersController@Delete', 'as' => 'users.administrators.delete' ])->where(['user_id' => '[0-9]+']);
    // Member User
    Route::get('/users/members', [ 'uses' => 'MemberUsersController@List', 'as' => 'users.members.list' ]);
    Route::get('/users/members/{user_id}', [ 'uses' => 'MemberUsersController@Get', 'as' => 'users.members.get' ])->where(['user_id' => '[0-9]+']);
    Route::put('/users/members', [ 'uses' => 'MemberUsersController@Create', 'as' => 'users.members.create' ]);
    Route::post('users/members/{user_id}', [ 'uses' => 'MemberUsersController@Update', 'as' => 'users.members.update' ])->where(['user_id' => '[0-9]+']);
    Route::delete('/users/members/{user_id}', [ 'uses' => 'MemberUsersController@Delete', 'as' => 'users.members.delete' ])->where(['user_id' => '[0-9]+']);
    Route::get('/users/members/waiting-approval', [ 'uses' => 'MemberUsersController@WaitingApproval', 'as' => 'users.members.waiting-approval' ]);


    // Privileges Groups
    Route::get('/privileges/groups', [ 'uses' => 'PrivilegesController@ListGroups', 'as' => 'privileges.groups.list' ]);
    Route::get('/privileges/groups/{privilege_group_id}', [ 'uses' => 'PrivilegesController@GetGroup', 'as' => 'privileges.groups.get' ])->where(['privilege_group_id' => '[0-9]+']);
    Route::get('/privileges/groups/administrators', [ 'uses' => 'PrivilegesController@ListGroupsAdmins', 'as' => 'privileges.groups.admins.list' ]);
    Route::get('/privileges/groups/members', [ 'uses' => 'PrivilegesController@ListGroupsMembers', 'as' => 'privileges.groups.members.list' ]);

    // Car Brands
    Route::get('/cars/brands', [ 'uses' => 'CarBrandsController@List', 'as' => 'car.brands.list' ]);
    Route::get('/cars/brands/{car_brand_id}', [ 'uses' => 'CarBrandsController@Get', 'as' => 'car.brands.get' ])->where(['car_brand_id' => '[0-9]+']);
    // Car Models
    Route::get('/cars/models', [ 'uses' => 'CarModelsController@List', 'as' => 'car.models.list' ]);
    Route::get('/cars/models/{car_model_id}', [ 'uses' => 'CarModelsController@Get', 'as' => 'car.models.get' ])->where(['car_model_id' => '[0-9]+']);
    Route::get('/cars/models/all', [ 'uses' => 'CarModelsController@ListAllModelsWithCarBrands', 'as' => 'car.models.all' ])->where(['car_model_id' => '[0-9]+']);
    // Car Colors
    Route::get('/cars/colors', [ 'uses' => 'CarColorsController@List', 'as' => 'car.colors.list' ]);
    Route::get('/cars/colors/{car_color_id}', [ 'uses' => 'CarColorsController@Get', 'as' => 'car.colors.get' ])->where(['car_color_id' => '[0-9]+']);

    // Vehicles
    Route::get('/vehicles', [ 'uses' => 'VehiclesController@List', 'as' => 'vehicles.list' ]);
    Route::get('/vehicles/{vehicle_id}', [ 'uses' => 'VehiclesController@Get', 'as' => 'vehicles.get' ])->where(['vehicle_id' => '[0-9]+']);
    Route::get('/vehicles/my', [ 'uses' => 'VehiclesController@ListMyVehicles', 'as' => 'vehicles.my-vehicles.list' ]);
    Route::get('/vehicles/my/{vehicle_id}', [ 'uses' => 'VehiclesController@GetMyVehicle', 'as' => 'vehicles.my-vehicles.get' ])->where(['vehicle_id' => '[0-9]+']);
    Route::get('/vehicles/my/create', [ 'uses' => 'VehiclesController@CreateMyVehicle', 'as' => 'vehicles.my-vehicles.create' ]);
    Route::get('/vehicles/my/update/{vehicle_id}', [ 'uses' => 'VehiclesController@UpdateMyVehicle', 'as' => 'vehicles.my-vehicles.update' ])->where(['vehicle_id' => '[0-9]+']);


    // Status
    Route::get('/club/status', [ 'uses' => 'ClubController@GetStatus', 'as' => 'club.status' ]);
});


// Mobile routes
Route::prefix('mobile')->group(function(){
    Route::post('/access/get-code', [ 'uses' => 'Mobile\AuthController@RequestAccessCode', 'as' => 'mobile.access.code.get' ]);
    Route::post('/access/authorize/{code}', [ 'uses' => 'Mobile\AuthController@AccessWithCode', 'as' => 'mobile.access.code.authorize' ]);

    Route::group([ 'middleware' => 'authorized.mobile' ], function(){
        Route::get('/me', [ 'uses' => 'Mobile\MembersController@Me', 'as' => 'mobile.users.me' ]);
        Route::post('/profile/update', [ 'uses' => 'Mobile\MembersController@UpdateProfile', 'as' => 'mobile.profile.update' ]);

        // Car Brands
        Route::get('/cars/brands', [ 'uses' => 'Mobile\CarBrandsController@List', 'as' => 'car.brands.list' ]);
        Route::get('/cars/brands/{car_brand_id}', [ 'uses' => 'Mobile\CarBrandsController@Get', 'as' => 'car.brands.get' ])->where(['car_brand_id' => '[0-9]+']);
        // Car Models
        Route::get('/cars/models', [ 'uses' => 'CarModelsController@List', 'as' => 'car.models.list' ]);
        Route::get('/cars/models/{car_model_id}', [ 'uses' => 'CarModelsController@Get', 'as' => 'car.models.get' ])->where(['car_model_id' => '[0-9]+']);
        Route::get('/cars/models/all', [ 'uses' => 'CarModelsController@ListAllModelsWithCarBrands', 'as' => 'car.models.all' ])->where(['car_model_id' => '[0-9]+']);
        // Car Colors
        Route::get('/cars/colors', [ 'uses' => 'CarColorsController@List', 'as' => 'car.colors.list' ]);
        Route::get('/cars/colors/{car_color_id}', [ 'uses' => 'CarColorsController@Get', 'as' => 'car.colors.get' ])->where(['car_color_id' => '[0-9]+']);
    });
});