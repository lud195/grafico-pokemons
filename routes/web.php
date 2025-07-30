<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokemonController;

Route::get('/', [PokemonController::class, 'index']); // Página principal con select
Route::get('/stats/{name}', [PokemonController::class, 'getStats']); // Devuelve stats en JSON
Route::get('/combate', function () {
    return view('combate'); // Página para el combate
});

Route::get('/combate', [PokemonController::class, 'combate']);