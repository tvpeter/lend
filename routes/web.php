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

Auth::routes(['register' => false, 'verify' => false]);

Route::get('/auth/callback', 'Auth\LoginController@callback');

Route::get('auth/login', 'Auth\LoginController@showAdminLogin');

Route::post('auth/login', 'Auth\LoginController@login')->name('auth.admin-login');

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', 'HomeController@index')->name('home');

    //Loans Resource
    Route::resource('loans', 'LoanController');

    //topup resource
    Route::resource('top-ups', 'TopUpController')->only(['store']);

    //sign offer letter
    Route::group(['prefix' => 'loans'], function () {

        Route::get('{loan}/offer-letter', 'OfferLetterController@show')->name('view-offer-letter');

        Route::post('{loan}/offer-letter', 'OfferLetterController@sign')->name('sign-offer-letter');

        Route::get('{loan}/resend-otp', 'OfferLetterController@resend_otp')->name('resend-otp');
    });

    Route::group(['middleware' => 'role:superadmin'], function () {

        //role resources
        Route::resource('roles', 'RoleController')->except(['destroy']);

        //permission resources
        Route::resource('permissions', 'PermissionController');

        //user resources
        Route::resource('users', 'UserController')->except(['destroy']);

    });

    Route::group(['middleware' => 'permission:line-manager-approval|hr-approval|cpo-approval'], function () {

        //approval resource
        Route::resource('loan-approvals', 'LoanApprovalController')->only(['index', 'show', 'update']);

    });

    Route::resource('reports', 'ReportController')->middleware('permission:report');

    //notifications

    //mark all notifications as read
    Route::get('/read-notifications', 'NotificationController@readNotifications')->name('read-notifications');

    //mark a notification as read
    Route::get('/read-notification/{notification}', 'NotificationController@readNotification')->name('read-notification');
});
