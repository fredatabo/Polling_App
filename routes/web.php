<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;

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

Route::get('/', function () {
    return view('welcome');
});
// route to get states
Route::get('/states', [DataController::class, 'getStates']);

// route to get local governments

Route::get('/lga/{state_id}', [DataController::class, 'getLGA']);

// get wards 

Route::get('/wards/{lga_id}', [DataController::class, 'getWards']);

// get polling_units  getPollingUnits

Route::get('/polling/units/{ward_id}', [DataController::class, 'getPollingUnits']);
//getResultFromPollingUnit

Route::get('/polling/unit/result/{id}', [DataController::class, 'getResultFromPollingUnit']);

// get LGA results

//Route::get('/lgaResults', [DataController::class, 'getLGAResult']);

//Route::get('/lgaResults', [DataController::class, 'getLGAResult']);

Route::get('/addNewResults', [DataController::class, 'AddResult']);

// route to add polling unit new result

Route::get('/polling/unit/newresult/{id}', [DataController::class, 'addResultPage']);

//route to save election result
Route::post('/saveResult', [DataController::class, 'addElectionResult']);

//route to show LGA result page

Route::get('/lgaResults', [DataController::class, 'showLGAResultPage']);
//showLGAresults

Route::get('/lgaResults', [DataController::class, 'showLGAResultPage']);

// route to get total LGA result
Route::get('/showLGAresults/{id}', [DataController::class, 'showLGAResultPageTotal']);