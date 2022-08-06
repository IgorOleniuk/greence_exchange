<?php

use App\Http\Controllers\TransactionsController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/transactions', [TransactionsController::class, 'openTransactions']);
Route::post('/create-transaction', [TransactionsController::class, 'createTransaction']);
Route::post('/apply-transaction', [TransactionsController::class, 'applyTransaction']);
Route::get('/system-fees', [TransactionsController::class, 'systemFees']);
