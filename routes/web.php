<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Authenticate;

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

Route::get('/', [\App\Http\Controllers\MainController::class, 'index']);
Route::get('/accounts', [\App\Http\Controllers\Tables\AccountController::class, 'show'])->name('accounts');
Route::post('/accounts', [\App\Http\Controllers\Tables\AccountController::class, 'add']);
Route::put('/accounts', [\App\Http\Controllers\Tables\AccountController::class, 'update']);

Route::get('/transactions', [\App\Http\Controllers\Tables\TransactionController::class, 'show'])->name('transactions');
Route::post('/transactions', [\App\Http\Controllers\Tables\TransactionController::class, 'add']);
Route::put('/transactions', [\App\Http\Controllers\Tables\TransactionController::class, 'update']);

Route::get('/transfers', [\App\Http\Controllers\Tables\TransferController::class, 'show'])->name('transfers');
Route::post('/transfers', [\App\Http\Controllers\Tables\TransferController::class, 'add']);
Route::put('/transfers', [\App\Http\Controllers\Tables\TransferController::class, 'update']);

Auth::routes();
