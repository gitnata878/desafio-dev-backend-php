<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormularioController;

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



Route::post('/formularios/{id_formulario}/preenchimentos', [FormularioController::class, 'store']);
Route::get('/formularios/{id_formulario}/preenchimentos', [FormularioController::class, 'index']);

Route::get('/api-docs.json', function () {
    return response()->file(storage_path('api-docs/api-docs.json'));
});
