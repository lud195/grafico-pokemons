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
        <img id="img-opponent" src="" alt="Pokémon rival" />
    </div>

    <a href="/" class="btn-volver">← Volver</a>

    <footer>Hecho con ❤️ y ⚡ estilo Pikachu moderno</footer>


<script>
    const ctx = document.getElementById('combatChart').getContext('2d');
    let combatChart;

    // Diccionario de colores personalizados por Pokémon
    const pokemonColors = {
        pikachu: 'rgba(255, 206, 86, 0.8)',
        charizard: 'rgba(255, 99, 132, 0.8)',
        bulbasaur: 'rgba(75, 192, 192, 0.8)',
        squirtle: 'rgba(54, 162, 235, 0.8)',
        gengar: 'rgba(153, 102, 255, 0.8)',
        lucario: 'rgba(100, 100, 255, 0.8)',
        eevee: 'rgba(255, 179, 71, 0.8)',
        mewtwo: 'rgba(150, 150, 255, 0.8)'
    };

    // Stats base de Pikachu
    const pikachuStats = {
        speed: 90,
        attack: 55,
        defense: 40,
        'special-attack': 50,
        'special-defense': 50,
        hp: 35
    };

    // Capitalizar nombre
    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    // Función principal que actualiza el combate
    async function updateCombat(pokemon) {
        if (!pokemon) {
            if (combatChart) combatChart.destroy();
            document.getElementById('img-opponent').src = '';
            document.getElementById('img-opponent').alt = 'Pokémon rival';
            return;
        }

        // Cambiar imagen del oponente
        const opponentImg = document.getElementById('img-opponent');
        opponentImg.src = `/poke-image/${pokemon}.png`;
        opponentImg.alt = capitalize(pokemon);

        try {
            const response = await fetch(`/stats/${pokemon}`);
            const data = await response.json();

            const labels = data.labels;
            const stats = data.data;

            // Convertimos los stats del oponente a objeto por nombre
            const opponentStats = {};
            labels.forEach((label, i) => {
                opponentStats[label] = stats[i];
            });

            // Destruir gráfico previo si existe
            if (combatChart) combatChart.destroy();

            // Crear nuevo gráfico
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
                            backgroundColor: pokemonColors[pokemon] || 'rgba(150, 150, 150, 0.8)'
                        }
                    ]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    scales: {
                        x: {
                            beginAtZero: true,
                            max: 130
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

    // Evento del <select>
    document.getElementById('pokemon-select').addEventListener('change', function () {
        updateCombat(this.value);
    });
</script>


</body>
</html>
