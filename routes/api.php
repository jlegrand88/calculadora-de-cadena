<?php

use App\Http\Controllers\CalculatorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/apitoken', function (Request $request) {
    $token = Auth::user()->tokens[0]->token;
    return ['token' => $token];
})->name('apitoken');;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/sumar', [CalculatorController::class, 'sumar'])->name('sumar');
    Route::get('/calculatorLog', [CalculatorController::class, 'getResultsHistory'])->name('calculatorLog');
});
