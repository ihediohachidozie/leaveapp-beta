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

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/home.users', 'HomeController@users');

Route::get('/home.department', 'HomeController@departments');

Route::get('/home.pendingReq', 'HomeController@pendingRequests');

Route::get('/home.approvalReq', 'HomeController@approvalRequests');






Route::resource('department', 'DepartmentController');

Route::resource('category', 'CategoryController');

Route::resource('public_holiday', 'PublicHolidayController');

Route::resource('user', 'UserController');

Route::get('catlist', 'UserController@Category');

Route::resource('profile', 'ProfileController');

Route::get('dept', 'ProfileController@department');

Route::resource('leave', "LeaveController");

Route::get('users', 'LeaveController@getUsers');

Route::get('holidays', 'LeaveController@getHolidays');

Route::get('approvals', 'LeaveController@getApprovals');

Route::get('approvalrequests', 'LeaveController@getUserApprovalRequests');

Route::put('leave.requests/{id}', 'LeaveController@leaveApproval');

Route::get('userleavesummary', 'LeaveController@leaveSummary');

Route::get('alluserleavesum', 'LeaveController@leaveSum');

Route::put('leave.status/{id}', 'LeaveController@statusOpen');

Route::get('leave.search/{id}/{x}', 'LeaveController@filterAppliedLeave');

Route::get('leavesum.search/{id}/{y}', 'LeaveController@filterLeaveSum');

Route::get('apprvoalstaff', 'LeaveController@ApprovalStaffSum');

Route::get('leave.viewuserApprovalReqs', 'LeaveController@getViewApprovalRequests');






Route::resource('comment', 'CommentController');