<?php

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

Route::get('/', function () {
    return view('welcome');
});

// Rota de fallback para API
Route::fallback(function () {
    return response()->json([
        'message' => 'Rota não encontrada. Verifique o URL e o método HTTP.',
    ], 404);
}); 