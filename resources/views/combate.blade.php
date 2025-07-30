<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Combate Pokémon: Pikachu vs Otro</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #FFDE2E 0%, #F4B400 100%);
            color: #333;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }
        h1 {
            color: #3b4cca;
            margin-bottom: 20px;
            user-select: none;
        }
        label {
            font-weight: 600;
            font-size: 1.2rem;
            margin-bottom: 10px;
            user-select: none;
        }
        select {
            padding: 10px 15px;
            font-size: 1rem;
            border-radius: 15px;
            border: none;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(255, 222, 46, 0.5);
            transition: box-shadow 0.3s ease;
            margin-bottom: 40px;
        }
        select:hover, select:focus {
            box-shadow: 0 8px 25px rgba(255, 222, 46, 0.8);
            background: #fffde7;
            outline: none;
        }
        #chart-container {
            max-width: 700px;
            width: 100%;
            background: rgba(255, 255, 255, 0.85);
            padding: 30px;
            border-radius: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        #pokemon-images {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 50px;
        }
        #pokemon-images img {
            width: 150px;
            border-radius: 20px;
            box-shadow: 0 0 30px #FFDE2E;
            transition: transform 0.3s ease;
            user-select: none;
        }
        #pokemon-images img:hover {
            transform: scale(1.1);
        }
        footer {
            margin-top: 50px;
            font-size: 0.9rem;
            color: #555;
            user-select: none;
        }

        .btn-volver {
    display: inline-block;
    margin: 20px;
    padding: 10px 20px;
    background-color: #ffcb05;
    color: #2a75bb;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
    font-size: 16px;
    border: 2px solid #2a75bb;
    transition: background-color 0.3s, color 0.3s;
}
.btn-volver:hover {
    background-color: #2a75bb;
    color: white;
}
    </style>
</head>
<body>

    <h1>Combate Pokémon: Pikachu vs Otro</h1>

    <label for="pokemon-select">Selecciona un Pokémon para combatir contra Pikachu:</label>
    <select id="pokemon-select">
        <option value="">-- Elegí uno --</option>
        @foreach ($pokemonNames as $name)
            <option value="{{ $name }}">{{ ucfirst($name) }}</option>
        @endforeach
    </select>

    <div id="chart-container">
        <canvas id="combatChart" width="600" height="400"></canvas>
    </div>

    <div id="pokemon-images">
        <img id="img-pikachu" src="/poke-image/pikachu.png" alt="Pikachu" />
        <img id="img-opponent" src=""  />
    </div>

    <a href="/" class="btn-volver">← Volver</a>

    <footer>Hecho con ❤️ y ⚡ estilo Pikachu moderno</footer>


    <script>
    const ctx = document.getElementById('combatChart').getContext('2d');
    let combatChart;

    // Colores modernos y vibrantes iguales a los de la gráfica principal
    const pokemonColors = {
        pikachu: 'rgba(255, 222, 46, 0.5)',
        charizard: 'rgba(255, 140, 0, 0.5)',
        bulbasaur: 'rgba(120, 200, 80, 0.5)',
        squirtle: 'rgba(100, 149, 237, 0.5)',
        gengar: 'rgba(147, 112, 219, 0.5)',
        lucario: 'rgba(0, 153, 204, 0.5)',
        charmander: 'rgba(255, 99, 71, 0.5)',
        eevee: 'rgba(255, 179, 71, 0.5)',
        mewtwo: 'rgba(150, 150, 255, 0.5)'
    };

    const pikachuStats = {
        speed: 90,
        attack: 55,
        defense: 40,
        'special-attack': 50,
        'special-defense': 50,
        hp: 35
    };

    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    async function updateCombat(pokemon) {
        if (!pokemon) {
            if (combatChart) combatChart.destroy();
            document.getElementById('img-opponent').src = '';
            document.getElementById('img-opponent').alt = 'Pokémon rival';
            return;
        }

        const opponentImg = document.getElementById('img-opponent');
        opponentImg.src = `/poke-image/${pokemon}.png`;
        opponentImg.alt = capitalize(pokemon);

        try {
            const response = await fetch(`/stats/${pokemon}`);
            const data = await response.json();

            const labels = data.labels;
            const stats = data.data;

            const opponentStats = {};
            labels.forEach((label, i) => {
                opponentStats[label.toLowerCase()] = stats[i];
            });

            if (combatChart) combatChart.destroy();

            combatChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Velocidad', 'Ataque', 'Defensa', 'Ataque Especial', 'Defensa Especial', 'HP'],
                    datasets: [
                        {
                            label: 'Pikachu',
                            data: [
                                pikachuStats['speed'],
                                pikachuStats['attack'],
                                pikachuStats['defense'],
                                pikachuStats['special-attack'],
                                pikachuStats['special-defense'],
                                pikachuStats['hp']
                            ],
                            backgroundColor: pokemonColors['pikachu']
                        },
                        {
                            label: capitalize(pokemon),
                            data: [
                                opponentStats['speed'],
                                opponentStats['attack'],
                                opponentStats['defense'],
                                opponentStats['special-attack'],
                                opponentStats['special-defense'],
                                opponentStats['hp']
                            ],
                            backgroundColor: pokemonColors[pokemon] || 'rgba(180, 180, 180, 0.5)'
                        }
                    ]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    scales: {
                        x: {
                            beginAtZero: true,
                            max: 200
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            }
                        },
                        tooltip: {
                            enabled: true
                        }
                    }
                }
            });

        } catch (error) {
            console.error('Error al obtener stats:', error);
        }
    }

    document.getElementById('pokemon-select').addEventListener('change', function () {
        updateCombat(this.value);
    });
</script>


</body>
</html>
