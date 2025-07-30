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

        $labels = ['hp', 'attack', 'defense', 'special-attack', 'special-defense', 'speed'];
        $stats = [];

        foreach ($labels as $label) {
            foreach ($poke['stats'] as $stat) {
                if ($stat['stat']['name'] === $label) {
                    $stats[] = $stat['base_stat'];
                    break;
                }
            }
        }

        return response()->json([
            'labels' => $labels,
            'data' => $stats,
            'image' => asset("poke-image/{$name}.png"),
            'color' => [
                'bg' => 'rgba(' . rand(100,255) . ',' . rand(100,255) . ',' . rand(100,255) . ', 0.4)',
                'border' => 'rgba(' . rand(100,255) . ',' . rand(100,255) . ',' . rand(100,255) . ', 1)'
            ]
        ]);
    }

    public function combate()
    {
        $pokemonNames = ['lucario', 'charizard', 'bulbasaur', 'gengar', 'squirtle'];
        return view('combate', compact('pokemonNames'));
    }
}
