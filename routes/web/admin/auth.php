<?php

use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ForgotPasswordController;
use App\Http\Controllers\Admin\Voyager\UsersController;

   Route::controller(CustomerController::class)->group(function () {
      Route::get('/signup/{local}', 'signup');
      Route::get('/signupError/{local}', 'signupError');

    Route::get('/signupVerifyEmail/{local}', 'signupVerifyEmail');
    Route::get('/signupConfirmation/{local}', 'signupConfirmation');
   });

   Route::controller(ForgotPasswordController::class)->group(function () {
      Route::get('/forgot-password/{local}','forgotPassword');
      Route::post('/reset-password/{local}','resetPassword');
      Route::get('/change-password/{id}/{local}', 'changePassword');
      Route::post('/update-password/{id}/{local}', 'updatePassword');
   });
   
   Route::get('/change_password/{$id}',[UsersController::class,'changePassword'])->name('change.password');
   