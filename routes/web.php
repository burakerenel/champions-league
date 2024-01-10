<?php

use App\Http\Controllers\LeagueController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [LeagueController::class,'tournamentTeams'])->name('league.tournamentTeams');
Route::post('/generate-fixtures', [LeagueController::class,'generateFixtures'])->name('league.generateFixtures');
Route::get('/generated-fixtures', [LeagueController::class,'generatedFixtures'])->name('league.generatedFixtures');
Route::get('/simulation', [LeagueController::class,'simulation'])->name('league.simulation');

Route::post('/play-next-week', [LeagueController::class,'playNextWeek'])->name('league.playNextWeek');
Route::post('/play-all-weeks', [LeagueController::class,'playAllWeeks'])->name('league.playAllWeeks');
Route::post('/reset-data', [LeagueController::class,'resetData'])->name('league.resetData');
