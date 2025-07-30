<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class PokemonController extends Controller
{
    public function index()
    {
        $pokemonNames = ['pikachu', 'charizard', 'bulbasaur', 'squirtle', 'gengar', 'lucario'];
        return view('chart', compact('pokemonNames'));
    }
    
    public function getStats($name)
    {
        $poke = Http::get("https://pokeapi.co/api/v2/pokemon/{$name}")->json();
    
        $labels = ['HP', 'Ataque', 'Defensa', 'Ataque Esp.', 'Defensa Esp.', 'Velocidad'];
        $stats = [];
    
        foreach ($poke['stats'] as $stat) {
            $stats[] = $stat['base_stat'];
        }
    
        return response()->json([
            'labels' => $labels,
            'data' => $stats,
            'color' => [
                'bg' => 'rgba(' . rand(100,255) . ',' . rand(100,255) . ',' . rand(100,255) . ', 0.4)',
                'border' => 'rgba(' . rand(100,255) . ',' . rand(100,255) . ',' . rand(100,255) . ', 1)'
            ]
        ]);
    }
    

}

