<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RSController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\userController;
use Illuminate\Support\Facades\Route;

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

Route::get('/login', [userController::class, 'loginIndex']); //to show login page
Route::get('/register', [userController::class, 'signIndex']); //to show login page
Route::post('/register', [userController::class, 'RegisterUser']); //to show login page
Route::get('/forget', [userController::class, 'showForget']);
Route::post('/sendMail', [userController::class, 'sendMail']);
Route::post('/resetPassword', [userController::class, 'resetPassword']);
Route::post('/signIn', [userController::class, 'signIn']);


Route::get('/',  [HomeController::class, 'showHome']);


Route::get('/profile', [userController::class, 'showProfile']);
Route::get('/profile/edit', [userController::class, 'showEditProfile']);
Route::post('/uploadUserImage', [userController::class, 'uploadUserImage']);

Route::post('/changeProfile', [userController::class, 'changeProfile']);

Route::get('/logout', [userController::class, 'logout']);

Route::get('/users/{kind}', [RSController::class, 'showRS']);

Route::get('/users/{kind}/search', [RSController::class, 'userSearch']);
Route::get('/users/{kind}/{id}', [RSController::class, 'showUserDetail']);
Route::get('/users/{kind}/delete/{id}', [RSController::class, 'deleteUser']);
Route::get('/report', [ReportController::class, 'showReport']);
Route::get('/report/interval', [ReportController::class, 'reportInterval']);
Route::get('/register/staff', [StaffController::class, 'RegisterStaff']);
Route::post('/register/new/staff', [StaffController::class, 'RegisterNewStaff']);

Route::get('/announcement', [AnnouncementController::class, 'showAnnouncement']);
Route::get('/user/announcement', [AnnouncementController::class, 'showUAnnouncement']);
Route::get('/customer', [AnnouncementController::class, 'showAnnouncement']);
Route::post('/announcement/send', [AnnouncementController::class, 'sendAnnouncement']);
//Route::get('/submit/{kind}', [TicketController::class, 'showTickets']);

Route::get('/submit/ticket', [TicketController::class, 'showSubmitTicket']);
Route::post('/submitTicket', [TicketController::class, 'submitTicket']);

Route::get('/tickets/{kind}', [TicketController::class, 'showTickets']);
Route::get('/all/tickets', [TicketController::class, 'showUsersTickets']);
Route::get('/tickets/{kind}/show/{id}', [TicketController::class, 'showTicket']);
Route::get('/tickets/{kind}/search', [TicketController::class, 'SearchTickets']);
Route::get('/tickets/{kind}/edit/{id}', [TicketController::class, 'editTicket']);
Route::get('/tickets/star/{rate}/{id}', [TicketController::class, 'updateRate']);
Route::post('/updateTicketStatus', [TicketController::class, 'updateTicket']);

Route::get('/support', [SupportController::class, 'showSupport']);
Route::get('/support/user', [SupportController::class, 'ushowSupport']);