<?php

use App\Http\Controllers\API\V1\ContactUsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\Auth\AuthController;
use App\Http\Controllers\API\V1\Auth\ProfileController;


Route::group([
	'prefix'     => 'v1',
	'middleware' => ['auth:sanctum'],
	'namespace'  => '\App\Http\Controllers\API\V1'
], function ()
{

	if (config('features.api_active')) {
		Route::post('/register', 'Auth\AuthController@register');
		Route::post('/login', 'Auth\AuthController@login');
		Route::post('/password/email', 'Auth\ForgotPasswordController@checkRequest');

		Route::post('/verify-email/verify', [AuthController::class, 'verifyEmail']);
		Route::get('/resend-code', [AuthController::class, 'resendCode']);

		// logged-in users
		Route::group(['middleware' => []], function ()
		{
			Route::get('/logout', 'Auth\AuthController@logout');
			Route::get('/profile', 'Auth\ProfileController@index');

			Route::put('/profile', 'Auth\ProfileController@update');
			Route::post('/avatar', 'Auth\ProfileController@updateAvatar');
			Route::post('/profile/setup01', 'Auth\ProfileController@profileSetup01');
			Route::post('/profile/setup02', 'Auth\ProfileController@profileSetup02');
			Route::post('/profile/setupImages', 'Auth\ProfileController@profileSetupImages');
			Route::post('/profile/setup03', 'Auth\ProfileController@profileSetup03');
			Route::post('/profile/setup04', 'Auth\ProfileController@profileSetup04');
			Route::get('/profile/support', 'Auth\ProfileController@getSupportData');
			Route::post('/profile/complete', 'Auth\ProfileController@profileComplete');
			Route::get('/profile/show', 'Auth\ProfileController@show');

			Route::post('/push-token/update', 'Auth\AuthController@pushTokenUpdate');
			
			Route::post('/password/edit', 'Auth\ResetPasswordController@updatePassword');
			Route::delete('/profile', [ProfileController::class, 'delete']);
			
			// home
			Route::get('/home', 'HomeAPIController@index');
			Route::post('/home/accept', 'HomeAPIController@accept');
			Route::get('/match-all', 'HomeAPIController@getMatchProfile');
			Route::post('/unmatch', 'HomeAPIController@unmatchProfile');
			
			// chat
			Route::post('/chat', 'ChatAPIController@store');
			Route::post('/chat/read', 'ChatAPIController@chatRead');
			Route::get('/chat/users', 'ChatAPIController@chatUsers');
			Route::get('/chat/list', 'ChatAPIController@chatLists');
			Route::post('/chat/report', 'ChatAPIController@makeReport');
			Route::post('/chat/block', 'ChatAPIController@chatBlock');
			Route::post('/chat/settings', 'ChatAPIController@updateSetting');
			Route::delete('/chat', 'ChatAPIController@destroy');
			// Route::delete('/chat/room', 'ChatAPIController@destroyRoom');
			
			// call
			Route::get('/call/token', 'CallAPIController@getToken');
			Route::post('/call/answer', 'CallAPIController@answer');

			//contact us
			Route::post('/contact-us', [ContactUsController::class, 'sendMail']);
		});
	}


});