<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\PaymentController;

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


Route::group(['middleware' => ['cors', 'json.response']], function () {
    Route::post('login', [ApiAuthController::class, 'login'])->name('login.api');
    Route::post('register', [ApiAuthController::class, 'register'])->name('register.api');
});


Route::group(['middleware' => ['cors', 'json.response', 'auth:api']], function () {
    Route::post('logout', [ApiAuthController::class, 'logout'])->name('logout.api');
    Route::post('applyLoan', [LoanController::class, 'applyLoan'])->name('applyLoan.api');
    Route::post('loanView', [LoanController::class, 'loanView'])->name('loanView.api');
    Route::post('singleLoanDetail', [LoanController::class, 'singleLoanDetail'])->name('singleLoanDetail.api');

    Route::post('repayLoan', [PaymentController::class, 'repayLoan'])->name('repayLoan.api');
    
});


Route::middleware('auth:api')->group(function () {
    Route::post('/approveReectLoan', [LoanController::class, 'approveReectLoan'])->middleware('api.admin')->name('approveReectLoan.api');
});