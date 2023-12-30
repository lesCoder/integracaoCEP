<?php

use App\Http\Controllers\CepController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render(
        'Home',
        [
            'title' => 'Oi oi Oi'
        ]
    );
});

Route::get('/', [CepController::class, 'home']);
Route::get('/busca-cep', [CepController::class, 'show']);
Route::post('/consulta-cep', [CepController::class, 'findCep']);
Route::get('/test', [CepController::class, 'obterCoordenadasPorEndereco']);
Route::post('/geodata', [CepController::class, 'getLatLng']);
