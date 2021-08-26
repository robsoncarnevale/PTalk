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

// Services
Route::get('/service/cep/find/{cep}', [ 'uses' => 'ServicesController@GetAddressByCep', 'as' => 'services.cep.find' ]);


Route::group([ 'middleware' => 'authorized' ], function(){
    // Logout
    Route::get('/logout', [ 'uses' => 'AuthController@Logout', 'as' => 'auth.logout' ]);

    // My Profile
    Route::get('/users/me', [ 'uses' => 'UsersController@Me', 'as' => 'users.me' ]);
    Route::post('/users/me', [ 'uses' => 'UsersController@UpdateMyProfile', 'as' => 'users.me.update' ]);
    Route::get('/users/me/address', [ 'uses' => 'UserAddressController@ListMyAddresses', 'as' => 'users.me.address' ]);
    Route::put('/users/me/address', [ 'uses' => 'UserAddressController@CreateMyAddress', 'as' => 'users.me.address.create' ]);
    Route::post('users/me/address/{address}', [ 'uses' => 'UserAddressController@UpdateMyAddress', 'as' => 'users.me.address.update' ]);
    // User
    Route::get('/users/all', [ 'uses' => 'UsersController@ListAll', 'as' => 'users.members.view-all' ]);
    Route::get('/users/profile/{user_id}', [ 'uses' => 'UsersController@ViewProfile', 'as' => 'users.profile.view' ])->where(['user_id' => '[0-9]+']);
    Route::get('/users/history/{user_id}', [ 'uses' => 'UsersController@GetHistory', 'as' => 'users.history' ])->where(['user_id' => '[0-9]+']);
    Route::get('/users/members/history-approval', [ 'uses' => 'MemberUsersController@HistoryApproval', 'as' => 'users.members.history-approval' ]);
    Route::post('/users/change-type/{user}/{type}', [ 'uses' => 'UsersController@ChangeType', 'as' => 'users.change-type']);
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
    // Members Classes
    Route::get('/users/classes', [ 'uses' => 'MembersClassesController@List', 'as' => 'users.classes.list' ]);
    Route::get('/users/classes/{member_class_id}', [ 'uses' => 'MembersClassesController@Get', 'as' => 'users.classes.get' ])->where(['member_class_id' => '[0-9]+']);
    Route::put('/users/classes', [ 'uses' => 'MembersClassesController@Create', 'as' => 'users.classes.create' ]);
    Route::post('users/classes/{member_class_id}', [ 'uses' => 'MembersClassesController@Update', 'as' => 'users.classes.update' ])->where(['member_class_id' => '[0-9]+']);
    Route::delete('/users/classes/{member_class_id}', [ 'uses' => 'MembersClassesController@Delete', 'as' => 'users.classes.delete' ])->where(['member_class_id' => '[0-9]+']);
    // Users Address
    Route::get('/users/{user}/address', [ 'uses' => 'UserAddressController@List', 'as' => 'users.address.list' ]);
    Route::get('/users/{user}/address/{address}', [ 'uses' => 'UserAddressController@Get', 'as' => 'users.address.get' ]);
    Route::put('/users/{user}/address', [ 'uses' => 'UserAddressController@Create', 'as' => 'users.address.create' ]);
    Route::post('users/{user}/address/{address}', [ 'uses' => 'UserAddressController@Update', 'as' => 'users.address.update' ]);
    Route::delete('/users/{user}/classes/{address}', [ 'uses' => 'UserAddressController@Delete', 'as' => 'users.address.delete' ]);
    // My Users Address
    Route::get('/users/address/my', [ 'uses' => 'UserAddressController@ListMyAddress', 'as' => 'users.address.list.my' ]);
    Route::get('/users/address/my/{address}', [ 'uses' => 'UserAddressController@GetMyAddress', 'as' => 'users.address.get.my' ]);
    Route::put('/users/address/my', [ 'uses' => 'UserAddressController@CreateMyAddress', 'as' => 'users.address.create.my' ]);
    Route::post('users/address/{address}', [ 'uses' => 'UserAddressController@UpdateMyAddress', 'as' => 'users.address.update.my' ]);
    Route::delete('/users/classes/{address}', [ 'uses' => 'UserAddressController@DeleteMyddress', 'as' => 'users.address.delete.my' ]);


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
    Route::put('/vehicles/{vehicle}/photo', [ 'uses' => 'VehiclesController@UploadVehiclePhoto', 'as' => 'vehicles.photo.upload' ]);
    Route::delete('/vehicles/{vehicle}/photo/{vehicle_photo}', [ 'uses' => 'VehiclesController@DeteleVehiclePhoto', 'as' => 'vehicles.photo.delete' ]);
    Route::put('/photo-without-vehicle', [ 'uses' => 'VehiclesController@UploadPhotoWithoutVehicle', 'as' => 'vehicles.photo.upload-without-vehicle' ]);
    // My vehicles
    Route::get('/vehicles/my', [ 'uses' => 'VehiclesController@ListMyVehicles', 'as' => 'vehicles.my-vehicles.list' ]);
    Route::get('/vehicles/my/{vehicle_id}', [ 'uses' => 'VehiclesController@GetMyVehicle', 'as' => 'vehicles.my-vehicles.get' ])->where(['vehicle_id' => '[0-9]+']);
    Route::put('/vehicles/my', [ 'uses' => 'VehiclesController@CreateMyVehicle', 'as' => 'vehicles.my-vehicles.create' ]);
    Route::post('/vehicles/my/{vehicle}', [ 'uses' => 'VehiclesController@UpdateMyVehicle', 'as' => 'vehicles.my-vehicles.update' ])->where(['vehicle_id' => '[0-9]+']);
    Route::delete('/vehicles/my/{vehicle}', [ 'uses' => 'VehiclesController@DeleteMyVehicle', 'as' => 'vehicles.my-vehicles.delete' ])->where(['vehicle_id' => '[0-9]+']);
    Route::put('/vehicles/my/{vehicle}/photo', [ 'uses' => 'VehiclesController@UploadMyVehiclePhoto', 'as' => 'vehicles.my-vehicles.photo.upload' ]);
    Route::delete('/vehicles/my/{vehicle}/photo/{vehicle_photo}', [ 'uses' => 'VehiclesController@DeteleMyVehiclePhoto', 'as' => 'vehicles.my-vehicles.photo.delete' ]);

    // Events
    Route::get('/events', [ 'uses' => 'EventsController@List', 'as' => 'events.list' ]);
    Route::get('/events/{event}', [ 'uses' => 'EventsController@Get', 'as' => 'events.get' ]);
    Route::put('/events', [ 'uses' => 'EventsController@Create', 'as' => 'events.create' ]);
    Route::post('events/{event}', [ 'uses' => 'EventsController@Update', 'as' => 'events.update' ]);
    Route::delete('/events/{event}', [ 'uses' => 'EventsController@Delete', 'as' => 'events.delete' ]);
    Route::post('events/{event}/start', [ 'uses' => 'EventsController@Start', 'as' => 'events.start' ]);
    Route::post('events/{event}/cancel', [ 'uses' => 'EventsController@Cancel', 'as' => 'events.cancel' ]);
    Route::post('events/{event}/subscribe', [ 'uses' => 'EventsController@Subscribe', 'as' => 'events.subscribe' ]);
    Route::post('events/{event}/unsubscribe', [ 'uses' => 'EventsController@Unsubscribe', 'as' => 'events.unsubscribe' ]);
    Route::get('events/{event}/members', [ 'uses' => 'EventsController@Members', 'as' => 'events.members' ]);
    // Events Address
    Route::put('/events/{event}/address', [ 'uses' => 'EventAddressController@Create', 'as' => 'events.address.create' ]);
    Route::post('events/{event}/address/{address}', [ 'uses' => 'EventAddressController@Update', 'as' => 'events.address.update' ]);
    Route::delete('/events/{event}/{address}', [ 'uses' => 'EventAddressController@Delete', 'as' => 'events.address.delete' ]);

    // Digital Bank Account
    Route::get('/bank-account/list', [ 'uses' => 'BankAccountController@List', 'as' => 'bankaccount.list' ]);
    Route::get('/bank-account/extract/{bank_account}', [ 'uses' => 'BankAccountController@Extract', 'as' => 'bankaccount.extract' ]);
    Route::get('/bank-account/my/extract', [ 'uses' => 'BankAccountController@ExtractMyAccount', 'as' => 'bankaccount.my.extract' ]);
    Route::get('/bank-account/find/{bank_account}', [ 'uses' => 'BankAccountController@Find', 'as' => 'bankaccount.find' ])->where(['bank_account' => '[0-9]+']);
    Route::put('/bank-account/launch/{bank_account}/debit', [ 'uses' => 'BankAccountController@LaunchDebit', 'as' => 'bankaccount.launch.debit' ])->where(['bank_account' => '[0-9]+']);
    Route::put('/bank-account/launch/{bank_account}/credit', [ 'uses' => 'BankAccountController@LaunchCredit', 'as' => 'bankaccount.launch.credit' ])->where(['bank_account' => '[0-9]+']);

    // Club Bank Account
    Route::get('/club/bank-account/extract', [ 'uses' => 'ClubBankAccountController@ExtractAccount', 'as' => 'club.bankaccount.extract' ]);
    Route::put('/club/bank-account/launch/debit', [ 'uses' => 'ClubBankAccountController@LaunchDebit', 'as' => 'club.bankaccount.launch.debit' ])->where(['bank_account' => '[0-9]+']);
    Route::put('/club/bank-account/launch/credit', [ 'uses' => 'ClubBankAccountController@LaunchCredit', 'as' => 'club.bankaccount.launch.credit' ])->where(['bank_account' => '[0-9]+']);
    Route::get('/club/bank-account/data', [ 'uses' => 'ClubBankAccountController@GetData', 'as' => 'club.bankaccount.data' ])->where(['bank_account' => '[0-9]+']);


    // Blacklist
    Route::get('/blacklist', [ 'uses' => 'BlacklistController@List', 'as' => 'blacklist.list' ]);
    Route::get('/blacklist/{blacklist_id}', [ 'uses' => 'BlacklistController@Get', 'as' => 'blacklist.get' ])->where(['blacklist_id' => '[0-9]+']);
    Route::put('/blacklist', [ 'uses' => 'BlacklistController@Create', 'as' => 'blacklist.create' ]);
    Route::post('/blacklist/{blacklist}', [ 'uses' => 'BlacklistController@Update', 'as' => 'blacklist.update' ]);

    // Config
    Route::get('/config', [ 'uses' => 'ConfigController@GetData', 'as' => 'config.data' ]);
    Route::post('/config', [ 'uses' => 'ConfigController@Save', 'as' => 'config.save' ]);

    // Club
    Route::get('/club/status', [ 'uses' => 'ClubController@GetStatus', 'as' => 'club.status' ]);
    Route::get('/club/data', [ 'uses' => 'ClubController@GetData', 'as' => 'club.data' ]);
    Route::get('/club/available-data', [ 'uses' => 'ClubController@GetAvailableData', 'as' => 'club.available-data' ]);
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
    Route::post('/members/participation-request', [ 'uses' => 'Mobile\MembersController@RequestParticipation', 'as' => 'mobile.members.participation-request' ]);

    // Services
    Route::get('/service/cep/find/{cep}', [ 'uses' => 'Mobile\ServicesController@GetAddressByCep', 'as' => 'mobile.services.cep.find' ]);

    Route::group([ 'middleware' => ['authorized.mobile', 'check.user-status.mobile'] ], function(){
        // Profile
        Route::get('/me', [ 'uses' => 'Mobile\MembersController@Me', 'as' => 'mobile.users.me' ]);
        // Route::post('/users/me', [ 'uses' => 'Mobile\MembersController@UpdateMyProfile', 'as' => 'mobile.users.me.update' ]);
        Route::get('/users/me/address', [ 'uses' => 'Mobile\UserAddressController@ListMyAddresses', 'as' => 'mobile.users.me.address' ]);
        Route::put('/users/me/address', [ 'uses' => 'Mobile\UserAddressController@CreateMyAddress', 'as' => 'mobile.users.me.address.create' ]);
        // Route::post('users/me/address/{address}', [ 'uses' => 'Mobile\UserAddressController@UpdateMyAddress', 'as' => 'users.me.address.update' ]);
        Route::post('users/me/address', [ 'uses' => 'Mobile\UserAddressController@UpdateMyAddress', 'as' => 'mobile.users.me.address.update' ]);
        Route::post('/profile/update', [ 'uses' => 'Mobile\MembersController@UpdateProfile', 'as' => 'mobile.profile.update' ]);

        // Users Address
        Route::get('/users/{user}/address', [ 'uses' => 'Mobile\UserAddressController@List', 'as' => 'mobile.users.address.list' ]);
        Route::get('/users/{user}/address/{address}', [ 'uses' => 'Mobile\UserAddressController@Get', 'as' => 'mobile.users.address.get' ]);
        Route::put('/users/{user}/address', [ 'uses' => 'Mobile\UserAddressController@Create', 'as' => 'mobile.users.address.create' ]);
        Route::post('users/{user}/address/{address}', [ 'uses' => 'Mobile\UserAddressController@Update', 'as' => 'mobile.users.address.update' ]);
        Route::delete('/users/{user}/classes/{address}', [ 'uses' => 'Mobile\UserAddressController@Delete', 'as' => 'mobile.users.address.delete' ]);
        // My User Address
        // Route::get('/users/address/my', [ 'uses' => 'Mobile\UserAddressController@ListMyAddress', 'as' => 'mobile.users.address.list.my' ]);
        // Route::get('/users/address/my/{address}', [ 'uses' => 'Mobile\UserAddressController@GetMyAddress', 'as' => 'mobile.users.address.get.my' ]);
        // Route::put('/users/address/my', [ 'uses' => 'Mobile\UserAddressController@CreateMyAddress', 'as' => 'mobile.users.address.create.my' ]);
        // Route::post('users/address/{address}', [ 'uses' => 'Mobile\UserAddressController@UpdateMyAddress', 'as' => 'mobile.users.address.update.my' ]);
        // Route::delete('/users/classes/{address}', [ 'uses' => 'Mobile\UserAddressController@DeleteMyddress', 'as' => 'mobile.users.address.delete.my' ]);

        
        // Vehicles
        Route::get('/vehicles', [ 'uses' => 'Mobile\VehiclesController@List', 'as' => 'mobile.vehicles.list' ]);
        Route::get('/vehicles/{vehicle_id}', [ 'uses' => 'Mobile\VehiclesController@Get', 'as' => 'mobile.vehicles.get' ])->where(['vehicle_id' => '[0-9]+']);
        // My vehicles
        Route::get('/vehicles/my', [ 'uses' => 'Mobile\VehiclesController@ListMyVehicles', 'as' => 'mobile.vehicles.my-vehicles.list' ]);
        Route::get('/vehicles/my/{vehicle_id}', [ 'uses' => 'Mobile\VehiclesController@GetMyVehicle', 'as' => 'mobile.vehicles.my-vehicles.get' ])->where(['vehicle_id' => '[0-9]+']);
        Route::put('/vehicles/my', [ 'uses' => 'Mobile\VehiclesController@CreateMyVehicle', 'as' => 'mobile.vehicles.my-vehicles.create' ]);
        Route::post('/vehicles/my/{vehicle}', [ 'uses' => 'Mobile\VehiclesController@UpdateMyVehicle', 'as' => 'mobile.vehicles.my-vehicles.update' ])->where(['vehicle_id' => '[0-9]+']);
        Route::delete('/vehicles/my/{vehicle}', [ 'uses' => 'Mobile\VehiclesController@DeleteMyVehicle', 'as' => 'mobile.vehicles.my-vehicles.delete' ])->where(['vehicle_id' => '[0-9]+']);
        Route::put('/vehicles/my/{vehicle}/photo', [ 'uses' => 'VehiclesController@UploadMyVehiclePhoto', 'as' => 'mobile.vehicles.my-vehicles.photo.upload' ]);
        Route::delete('/vehicles/my/{vehicle}/photo/{vehicle_photo}', [ 'uses' => 'VehiclesController@DeteleMyVehiclePhoto', 'as' => 'mobile.vehicles.my-vehicles.photo.delete' ]);

        // Events
        Route::get('/events', [ 'uses' => 'Mobile\EventsController@List', 'as' => 'mobile.events.list' ]);
        Route::get('/events/{event}', [ 'uses' => 'Mobile\EventsController@Get', 'as' => 'mobile.events.get' ]);
        Route::post('events/{event}/subscribe', [ 'uses' => 'Mobile\EventsController@Subscribe', 'as' => 'mobile.events.subscribe' ]);
        Route::post('events/{event}/unsubscribe', [ 'uses' => 'Mobile\EventsController@Unsubscribe', 'as' => 'mobile.events.subscribe' ]);

        // Digital Bank Account
        Route::get('/bank-account/my/extract', [ 'uses' => 'Mobile\BankAccountController@ExtractMyAccount', 'as' => 'bankaccount.my.extract' ]);

        // Refer member
        Route::post('/members/refer', [ 'uses' => 'Mobile\MembersController@CreateFromReference', 'as' => 'mobile.members.refer' ]);
    });
});