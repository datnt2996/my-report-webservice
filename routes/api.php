<?php

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

Route::namespace('\App\Http\Controllers')->group(function () {

	Route::post('/users/login.json', 'UserController@loginUserController');
	Route::post('/users.json', 'UserController@createUserController');
	Route::get('/category.json','CategoryController@get');
	

	Route::middleware('auth.jwt')->group(function () {

		Route::put('/users/{user_id}.json', 'UserController@updateUserController');
		Route::get('/users/{user_id}.json', 'UserController@getUserByIdController');
		Route::get('/users.json', 'UserController@getUsersController');

		Route::get('/users_address.json', 'UserController@getUserAddressesController');
		Route::post('/users_address.json', 'UserController@createUserAddressesController');
		Route::put('/users_address.json', 'UserController@updateUserAddressesController');
		Route::delete('/users_address/{id}.json', 'UserController@deleteUserAddressesController');

		Route::get('/events/{eventId}.json', 'EventController@getEventController');
		Route::get('/events.json', 'EventController@getEventsController');
		Route::post('/events.json', 'EventController@postEventController');
		Route::put('/events/{id}.json', 'EventController@putEventController');
		Route::delete('/events/{id}.json', 'EventController@deleteEventController');

		Route::post('/images/upload.json', 'ImageController@upload');
		Route::delete('/images/{imageId}.json', 'ImageController@delete');

		Route::get('/events/images.json', 'EventImageController@get');
		Route::post('/events/{event_id}/images.json', 'EventImageController@postImageController');
		Route::put('/events/{event_id}/images/{id}.json', 'EventImageController@put');
		Route::delete('/events/{event_id}/images/{id}.json', 'EventImageController@delete');
		Route::post('/events/images.json', 'EventImageController@post');

		//getEventTimeController
		Route::get('/events/{eventID}/times.json', 'EventTimeController@getEventTimeController');
		Route::post('/events/{eventID}/times.json', 'EventTimeController@postEventTimeController');


		Route::post('/events/{eventId}/comments.json', 'CommentController@create');
		Route::get('/events/{eventId}/comments.json', 'CommentController@get');

		Route::post('/events/{eventId}/rate.json', 'RateController@create');
		Route::put('/events/{eventId}/rate/{rateId}.json', 'RateController@update');

		Route::post('/events/{eventId}/enroll.json', 'EnrollController@create');
		
		//notifications
		
		Route::get('/notifications.json', 'NotificationController@getNotification');
	});
});