<?php

use App\Http\Controllers\SupportController;
use App\Http\Controllers\TicketController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });




Route::post('/sendMessageListToController', [SupportController::class, 'sendMessageListToController']);
Route::post('/usendMessageListToController', [SupportController::class, 'usendMessageListToController']);

Route::post('/checkAddress', [TicketController::class, 'checkAddress']);