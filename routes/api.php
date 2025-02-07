<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EnginController;
use App\Http\Controllers\LubrifiantController;
use App\Http\Controllers\PanneController;
use App\Http\Controllers\ParcController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaisiehimController;
use App\Http\Controllers\SaisiehrmController;
use App\Http\Controllers\SaisielubrifiantController;
use App\Http\Controllers\SaisierjeController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\TypelubrifiantController;
use App\Http\Controllers\TypepanneController;
use App\Http\Controllers\TypeparcController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');

Route::get('/', function () {
    $routeCollection = Route::getRoutes();
    return view('welcome', [
        'routeCollection' => $routeCollection
    ]);
});

Route::apiResource('posts', PostController::class);
Route::apiResource('sites', SiteController::class);
Route::apiResource('typeparcs', TypeparcController::class);
Route::apiResource('parcs', ParcController::class);
Route::apiResource('engins', EnginController::class);
Route::apiResource('typepannes', TypepanneController::class);
Route::apiResource('pannes', PanneController::class);
Route::apiResource('typelubrifiants', TypelubrifiantController::class);
Route::apiResource('lubrifiants', LubrifiantController::class);

Route::get('/saisierjes/getRJE', [SaisierjeController::class, 'getRJE']);
Route::apiResource('saisierjes', SaisierjeController::class);

Route::get('/reports/getRJE', [ReportController::class, 'getRJE']);

Route::apiResource('saisiehrms', SaisiehrmController::class);
Route::apiResource('saisiehims', SaisiehimController::class);

Route::apiResource('saisielubrifiants', SaisielubrifiantController::class);
