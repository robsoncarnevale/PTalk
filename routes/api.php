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

// Password
Route::get('/password/first-access/{club_code}/{token}', [ 'uses' => 'PasswordController@FirstAccess', 'as' => 'password.first-access' ]);
Route::post('/password/first-access/{club_code}/{token}', [ 'uses' => 'PasswordController@FirstAccess', 'as' => 'password.first-access.post' ]);
Route::get('/password/forget/{club_code}/{token}', [ 'uses' => 'PasswordController@Forget', 'as' => 'password.forget' ]);
Route::post('/password/forget/{club_code}/{token}', [ 'uses' => 'PasswordController@Forget', 'as' => 'password.forget.post' ]);
// Password tokens
Route::post('/password/forget/get-token/{club_code}/{email}', [ 'uses' => 'PasswordController@GetForgetToken', 'as' => 'password.forget.get-token' ]);
Route::post('/password/first-access/get-token/{club_code}/{email}', [ 'uses' => 'PasswordController@GetFirstAccessToken', 'as' => 'password.first-access.post' ]);

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
    Route::post('/users/members/{user_id}/approval-status/{status}', [ 'uses' => 'MemberUsersController@SetApprovalStatus', 'as' => 'users.members.approval-status.set' ])->where(['user_id' => '[0-9]+']);


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
    Route::put('/cars/models', [ 'uses' => 'CarModelsController@Create', 'as' => 'car.models.create' ]);
    Route::post('/cars/models/{car_model_id}', [ 'uses' => 'CarModelsController@Update', 'as' => 'car.models.update' ]);
    Route::delete('/cars/models/{car_model_id}', [ 'uses' => 'CarModelsController@Delete', 'as' => 'car.models.delete' ]);
    // Car Colors
    Route::get('/cars/colors', [ 'uses' => 'CarColorsController@List', 'as' => 'car.colors.list' ]);
    Route::get('/cars/colors/{car_color_id}', [ 'uses' => 'CarColorsController@Get', 'as' => 'car.colors.get' ])->where(['car_color_id' => '[0-9]+']);
    Route::put('/cars/colors', [ 'uses' => 'CarColorsController@Create', 'as' => 'car.colors.create' ]);
    Route::post('/cars/colors/{car_color_id}', [ 'uses' => 'CarColorsController@Update', 'as' => 'car.colors.update' ]);
    Route::delete('/cars/colors/{car_color_id}', [ 'uses' => 'CarColorsController@Delete', 'as' => 'car.colors.delete' ]);

    // Vehicles
    Route::get('/vehicles', [ 'uses' => 'VehiclesController@List', 'as' => 'vehicles.list' ]);
    Route::get('/vehicles/{vehicle_id}', [ 'uses' => 'VehiclesController@Get', 'as' => 'vehicles.get' ])->where(['vehicle_id' => '[0-9]+']);
    Route::put('/vehicles', [ 'uses' => 'VehiclesController@Create', 'as' => 'vehicles.create' ]);
    Route::post('/vehicles/{vehicle}', [ 'uses' => 'VehiclesController@Update', 'as' => 'vehicles.update' ])->where(['vehicle_id' => '[0-9]+']);
    Route::delete('/vehicles/{vehicle}', [ 'uses' => 'VehiclesController@Delete', 'as' => 'vehicles.delete' ])->where(['vehicle_id' => '[0-9]+']);
    // My vehicles
    Route::get('/vehicles/my', [ 'uses' => 'VehiclesController@ListMyVehicles', 'as' => 'vehicles.my-vehicles.list' ]);
    Route::get('/vehicles/my/{vehicle_id}', [ 'uses' => 'VehiclesController@GetMyVehicle', 'as' => 'vehicles.my-vehicles.get' ])->where(['vehicle_id' => '[0-9]+']);
    Route::put('/vehicles/my', [ 'uses' => 'VehiclesController@CreateMyVehicle', 'as' => 'vehicles.my-vehicles.create' ]);
    Route::post('/vehicles/my/{vehicle}', [ 'uses' => 'VehiclesController@UpdateMyVehicle', 'as' => 'vehicles.my-vehicles.update' ])->where(['vehicle_id' => '[0-9]+']);
    Route::delete('/vehicles/my/{vehicle}', [ 'uses' => 'VehiclesController@DeleteMyVehicle', 'as' => 'vehicles.my-vehicles.delete' ])->where(['vehicle_id' => '[0-9]+']);

    // Events
    Route::get('/events', [ 'uses' => 'EventsController@List', 'as' => 'events.list' ]);
    Route::get('/events/{event}', [ 'uses' => 'EventsController@Get', 'as' => 'events.get' ]);
    Route::put('/events', [ 'uses' => 'EventsController@Create', 'as' => 'events.create' ]);
    Route::post('events/{event}', [ 'uses' => 'EventsController@Update', 'as' => 'events.update' ]);
    Route::delete('/events/{event}', [ 'uses' => 'EventsController@Delete', 'as' => 'events.delete' ]);

    // Status
    Route::get('/club/status', [ 'uses' => 'ClubController@GetStatus', 'as' => 'club.status' ]);
});


// Mobile routes
Route::prefix('mobile')->group(function(){
    // Access
    Route::post('/access/get-code', [ 'uses' => 'Mobile\AuthController@RequestAccessCode', 'as' => 'mobile.access.code.get' ]);
    Route::post('/access/authorize/{code}', [ 'uses' => 'Mobile\AuthController@AccessWithCode', 'as' => 'mobile.access.code.authorize' ]);

    // Car Brands
    Route::get('/cars/brands', [ 'uses' => 'Mobile\CarBrandsController@List', 'as' => 'mobile.car.brands.list' ]);
    Route::get('/cars/brands/{car_brand_id}', [ 'uses' => 'Mobile\CarBrandsController@Get', 'as' => 'mobile.car.brands.get' ])->where(['car_brand_id' => '[0-9]+']);
    // Car Models
    Route::get('/cars/models', [ 'uses' => 'Mobile\CarModelsController@List', 'as' => 'mobile.car.models.list' ]);
    Route::get('/cars/models/{car_model_id}', [ 'uses' => 'Mobile\CarModelsController@Get', 'as' => 'mobile.car.models.get' ])->where(['car_model_id' => '[0-9]+']);
    Route::get('/cars/models/all', [ 'uses' => 'Mobile\CarModelsController@ListAllModelsWithCarBrands', 'as' => 'mobile.car.models.all' ])->where(['car_model_id' => '[0-9]+']);
    // Car Colors
    Route::get('/cars/colors', [ 'uses' => 'Mobile\CarColorsController@List', 'as' => 'mobile.car.colors.list' ]);
    Route::get('/cars/colors/{car_color_id}', [ 'uses' => 'Mobile\CarColorsController@Get', 'as' => 'mobile.car.colors.get' ])->where(['car_color_id' => '[0-9]+']);

    // Get code to continue refer member
    Route::get('/members/refer/get-code', [ 'uses' => 'Mobile\MembersController@GetCodeToContinueRequest', 'as' => 'mobile.members.refer.code' ]);
    Route::post('/members/refer/continue', [ 'uses' => 'Mobile\MembersController@ContinueReference', 'as' => 'mobile.members.refer.continue' ]);

    Route::group([ 'middleware' => ['authorized.mobile', 'check.user-status.mobile'] ], function(){
        Route::get('/me', [ 'uses' => 'Mobile\MembersController@Me', 'as' => 'mobile.users.me' ]);
        Route::post('/profile/update', [ 'uses' => 'Mobile\MembersController@UpdateProfile', 'as' => 'mobile.profile.update' ]);

        // Vehicles
        Route::get('/vehicles', [ 'uses' => 'Mobile\VehiclesController@List', 'as' => 'mobile.vehicles.list' ]);
        Route::get('/vehicles/{vehicle_id}', [ 'uses' => 'Mobile\VehiclesController@Get', 'as' => 'mobile.vehicles.get' ])->where(['vehicle_id' => '[0-9]+']);
        // My vehicles
        Route::get('/vehicles/my', [ 'uses' => 'Mobile\VehiclesController@ListMyVehicles', 'as' => 'mobile.vehicles.my-vehicles.list' ]);
        Route::get('/vehicles/my/{vehicle_id}', [ 'uses' => 'Mobile\VehiclesController@GetMyVehicle', 'as' => 'mobile.vehicles.my-vehicles.get' ])->where(['vehicle_id' => '[0-9]+']);
        Route::put('/vehicles/my', [ 'uses' => 'Mobile\VehiclesController@CreateMyVehicle', 'as' => 'mobile.vehicles.my-vehicles.create' ]);
        Route::post('/vehicles/my/{vehicle}', [ 'uses' => 'Mobile\VehiclesController@UpdateMyVehicle', 'as' => 'mobile.vehicles.my-vehicles.update' ])->where(['vehicle_id' => '[0-9]+']);
        Route::delete('/vehicles/my/{vehicle}', [ 'uses' => 'Mobile\VehiclesController@DeleteMyVehicle', 'as' => 'mobile.vehicles.my-vehicles.delete' ])->where(['vehicle_id' => '[0-9]+']);

        // Events
        Route::get('/events', [ 'uses' => 'Mobile\EventsController@List', 'as' => 'mobile.events.list' ]);
        Route::get('/events/{event}', [ 'uses' => 'Mobile\EventsController@Get', 'as' => 'mobile.events.get' ]);

        // Refer member
        Route::post('/members/refer', [ 'uses' => 'Mobile\MembersController@CreateFromReference', 'as' => 'mobile.members.refer' ]);
    });
});