<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Payments\MPESAController;
use App\Http\Controllers\Services\NHIFStudentController;
use App\Http\Controllers\Communication\BeemSMSController;
use App\Http\Controllers\Services\NIDAController;
use App\Http\Controllers\Payments\AirtelControoler;
use App\Http\Controllers\Payments\BeemPaymentController;



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

// MPESA DARAJA API
Route::post('validation',[MPESAController::class,'validation']);
Route::post('confirmation',[MPESAController::class,'confirmation']);
Route::post('b2cCallback',[MPESAController::class,'b2cCallback']);
Route::post('trasaction-status/result_url',[MPESAController::class,'trasactionStatusResponse']);


// NHIF STUDENT API v3
Route::group(['prefix'=>'nhif'], function(){
    Route::post('auth',[NHIFStudentController::class,'agentAuthentication']);
    Route::get('details',[NHIFStudentController::class,'registrationDetails']);
    Route::get('verify',[NHIFStudentController::class,'verifyMemberCardStatus']);
    Route::get('payment-status',[NHIFStudentController::class,'paymentStatus']);
});


// BEEM  SMS API 
Route::group(['prefix'=>'beem'], function(){
    Route::post('send',[BeemSMSController::class,'sendMessage']);
    Route::get('balance',[BeemSMSController::class,'checkBalance']);
    Route::get('derively-report',[BeemSMSController::class,'derivelyReport']);
    Route::post('request-sendername',[BeemSMSController::class,'requestSenderName']);
    Route::post('add-template',[BeemSMSController::class,'addSMSTemplates']);
    Route::post('edit-template',[BeemSMSController::class,'editSMSTemplates']);
    Route::delete('delete-template',[BeemSMSController::class,'deleteSMSTemplates']);
});

// BEEM  PAYMENT API 
Route::group(['prefix'=>'beem'], function(){
    Route::post('checkout',[BeemPaymentController::class,'checkOut']);
});

// NIDA  API 
Route::group(['prefix'=>'nida'], function(){
    Route::post('send',[NIDAController::class,'getPersonInformation']);
});


// Airtel Api
Route::group(['prefix'=>'airtel'], function(){
    Route::post('token',[AirtelControoler::class,'getAccessToken']);
});
