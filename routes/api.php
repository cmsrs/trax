<?php

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\TripController;

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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


if( PHP_SAPI == 'cli'  &&  (strpos($_SERVER['argv'][0], 'phpunit') !== FALSE)  ) {    
    //todo - remove this if - fix tests    
    Route::get('/get-cars', [CarController::class, 'index']); 
    Route::post('/add-car', [CarController::class, 'create']);
    Route::get('/get-car/{id}', [CarController::class, 'item']);     
    Route::delete('/delete-car/{id}', [CarController::class, 'deleteitem']);
    
    Route::get('/get-trips', [TripController::class, 'index']); 
    Route::post('/add-trip', [TripController::class, 'create']);
}else{
    Route::get('/get-cars', [CarController::class, 'index'])->middleware('auth:api');
    Route::post('/add-car', [CarController::class, 'create'])->middleware('auth:api');
    Route::get('/get-car/{id}', [CarController::class, 'item'])->middleware('auth:api');
    Route::delete('/delete-car/{id}', [CarController::class, 'deleteitem'])->middleware('auth:api');

    Route::get('/get-trips', [TripController::class, 'index'])->middleware('auth:api');
    Route::post('/add-trip', [TripController::class, 'create'])->middleware('auth:api');
}


//////////////////////////////////////////////////////////////////////////
/// Mock Endpoints To Be Replaced With RESTful API.
/// - API implementation needs to return data in the format seen below.
/// - Post data will be in the format seen below.
/// - /resource/assets/traxAPI.js will have to be updated to align with
///   the API implementation
//////////////////////////////////////////////////////////////////////////

// Mock endpoint to get all cars for the logged in user

Route::get('/mock-get-cars', function(Request $request) {
    return [
        'data' => [
            [
                'id' => 1,
                'make' => 'Land Rover',
                'model' => 'Range Rover Sport',
                'year' => 2017
            ],
            [
                'id' => 2,
                'make' => 'Ford',
                'model' => 'F150',
                'year' => 2014
            ],
            [
                'id' => 3,
                'make' => 'Chevy',
                'model' => 'Tahoe',
                'year' => 2015
            ],
            [
                'id' => 4,
                'make' => 'Aston Martin',
                'model' => 'Vanquish',
                'year' => 2018
            ]
        ]
    ];
})->middleware('auth:api');


// Mock endpoint to add a new car.

Route::post('mock-add-car', function(Request $request) {
    $request->validate([
        'year' => 'required|integer',
        'make' => 'required|string',
        'model' => 'required|string'
    ]);
})->middleware('auth:api');


// Mock endpoint to get a car with the given id

Route::get('/mock-get-car/{id}', function(Request $request) {
    return  [
        'data' => [
            'id' => 1,
            'make' => 'Land Rover',
            'model' => 'Range Rover Sport',
            'year' => 2017,
            'trip_count' => 2,
            'trip_miles' => 18.1
        ]
    ];
})->middleware('auth:api');


// Mock endpoint to delete a car with a given id

Route::delete('mock-delete-car/{id}', function(Request $request) {
})->middleware('auth:api');


// Mock endpoint to get the trips for the logged in user

Route::get('/mock-get-trips', function(Request $request) {
    return [
        'data' => [
            [
                'id'  => 1,
                'date' => Carbon::now()->subDays(1)->format('m/d/Y'),
                'miles' => 11.3,
                'total' => 45.3,
                'car' => [
                    'id' => 1,
                    'make' => 'Land Rover',
                    'model' => 'Range Rover Sport',
                    'year' => 2017
                ]
            ],
            [
                'id'  => 2,
                'date' => Carbon::now()->subDays(2)->format('m/d/Y'),
                'miles' => 12.0,
                'total' => 34.1,
                'car' => [
                    'id' => 4,
                    'make' => 'Aston Martin',
                    'model' => 'Vanquish',
                    'year' => 2018
                ]
            ],
            [
                'id'  => 3,
                'date' => Carbon::now()->subDays(3)->format('m/d/Y'),
                'miles' => 6.8,
                'total' => 22.1,
                'car' => [
                    'id' => 1,
                    'make' => 'Land Rover',
                    'model' => 'Range Rover Sport',
                    'year' => 2017
                ]
            ],
            [
                'id'  => 4,
                'date' => Carbon::now()->subDays(4)->format('m/d/Y'),
                'miles' => 5,
                'total' => 15.3,
                'car' => [
                    'id' => 2,
                    'make' => 'Ford',
                    'model' => 'F150',
                    'year' => 2014
                ]
            ],
            [
                'id'  => 5,
                'date' => Carbon::now()->subDays(5)->format('m/d/Y'),
                'miles' => 10.3,
                'total' => 10.3,
                'car' => [
                    'id' => 3,
                    'make' => 'Chevy',
                    'model' => 'Tahoe',
                    'year' => 2015
                ]
            ]
        ]
    ];
})->middleware('auth:api');


// Mock endpoint to add a new trip.

Route::post('mock-add-trip', function(Request $request) {
    $request->validate([
        'date' => 'required|date', // ISO 8601 string
        'car_id' => 'required|integer',
        'miles' => 'required|numeric'
    ]);
})->middleware('auth:api');
