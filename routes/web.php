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

Route::get('register', 'Auth\RegisterController@index')->name('register');

Route::get('home', 'HomeController@index')->name('home');

Route::get('home.users', 'HomeController@users');

Route::get('home.department', 'HomeController@departments');

Route::get('home.pendingReq', 'HomeController@pendingRequests');

Route::get('home.approvalReq', 'HomeController@approvalRequests');







Route::resource('department', 'DepartmentController');

Route::resource('category', 'CategoryController');

Route::resource('public_holiday', 'PublicHolidayController');

Route::resource('user', 'UserController');

Route::get('catlist', 'UserController@Category');

Route::resource('profile', 'ProfileController');

Route::get('dept', 'ProfileController@department');

Route::resource('leave', "LeaveController");

Route::get('allusers', 'LeaveController@getAllUsers');

Route::get('users', 'LeaveController@getUsers');


Route::get('holidays', 'LeaveController@getHolidays');

Route::get('approvals', 'LeaveController@getApprovals');

Route::get('approvalrequests', 'LeaveController@getUserApprovalRequests');

Route::put('leave.requests/{id}', 'LeaveController@leaveApproval');

Route::get('userleavesummary', 'LeaveController@leaveSummary');

Route::get('alluserleavesum', 'LeaveController@leaveSum');

Route::put('leave.status/{id}', 'LeaveController@statusOpen');

Route::get('leave.search/{id}/{x}', 'LeaveController@filterAppliedLeave');

Route::get('leave.searchyear/{x}', 'LeaveController@filterAppliedLeaveByYear');

Route::get('leave.searchuser/{id}', 'LeaveController@filterAppliedLeaveByUser');

Route::get('leavesum.search/{id}/{y}', 'LeaveController@filterLeaveSum');

Route::get('leavesum.searchyear/{y}', 'LeaveController@filterLeaveSumByYear');

Route::get('leavesum.searchuser/{id}', 'LeaveController@filterLeaveSumByUser');

Route::get('apprvoalstaff', 'LeaveController@ApprovalStaffSum');

Route::get('leave.viewuserApprovalReqs', 'LeaveController@getViewApprovalRequests');






Route::resource('comment', 'CommentController');


Route::get('/{vue_capture?}', function () {
return view('home');
})->where('vue_capture', '[\/\w\.-]*');