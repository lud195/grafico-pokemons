<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Estadísticas Pokémon</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Reset básico */
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #FFDE2E 0%, #F4B400 100%);
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            padding: 40px 20px;
        }

        h1 {
            font-weight: 900;
            font-size: 3rem;
            color: #3b4cca; /* Azul Pokémon */
            text-shadow: 2px 2px 5px rgba(0,0,0,0.15);
            margin-bottom: 30px;
            letter-spacing: 2px;
            user-select: none;
        }

        label {
            font-weight: 600;
            font-size: 1.2rem;
            color: #2a2a2a;
            margin-bottom: 10px;
            display: block;
            user-select: none;
        }

        select {
            width: 220px;
            padding: 10px 15px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 15px;
            border: none;
            outline: none;
            cursor: pointer;
            background: #fff;
            box-shadow: 0 5px 15px rgba(255, 222, 46, 0.5);
            transition: all 0.3s ease;
        }

        select:hover,
        select:focus {
            box-shadow: 0 8px 25px rgba(255, 222, 46, 0.8);
            background: #fffde7;
        }

        .container {
            display: flex;
            align-items: center;
            gap: 50px;
            margin-top: 40px;
            max-width: 1000px;
            width: 100%;
            background: rgba(255, 255, 255, 0.85);
            padding: 30px;
            border-radius: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        #pokemonChart {
            flex: 1;
            max-width: 480px;
            max-height: 480px;
        }

        #pokemon-image {
            width: 380px;
            height: auto;
            border-radius: 30px;
            box-shadow:
                0 0 40px 12px #FFDE2E,
                0 0 80px 20px #F4B400,
                0 0 15px 5px #3b4cca;
            transition: transform 0.4s ease;
            user-select: none;
        }

        #pokemon-image:hover {
            transform: scale(1.05);
            box-shadow:
                0 0 60px 15px #FFDE2E,
                0 0 100px 30px #F4B400,
                0 0 30px 10px #3b4cca;
        }

        /* Texto de créditos / pie si quieres */
        footer {
            margin-top: 60px;
            font-size: 0.9rem;
            color: #555;
            user-select: none;
        }
    </style>
</head>
<body>

    <h1>Estadísticas base por Pokémon</h1>

    <label for="pokemon-select">Seleccioná un Pokémon:</label>
    <select id="pokemon-select">
        <option value="">-- Elegí uno --</option>
        @foreach ($pokemonNames as $name)
            <option value="{{ $name }}">{{ ucfirst($name) }}</option>
        @endforeach
    </select>

    <div class="container">
        <canvas id="pokemonChart"></canvas>
        <img id="pokemon-image" src="" alt="Imagen del Pokémon" />
    </div>

    <footer>Hecho con ❤️ y ⚡ estilo Pikachu moderno</footer>

    <script>
        const ctx = document.getElementById('pokemonChart').getContext('2d');
        let chart;

        // Paleta estilo Pikachu con tonos brillantes pero modernos
        const pikachuColors = {
            bg: 'rgba(255, 222, 46, 0.5)',    // amarillo suave
            border: 'rgba(255, 184, 0, 1)',   // amarillo fuerte/naranja
        };

        document.getElementById('pokemon-select').addEventListener('change', async function () {
            const name = this.value;
            if (!name) return;

            const statsResponse = await fetch(`/stats/${name}`);
            const statsResult = await statsResponse.json();

            if (chart) chart.destroy();

            chart = new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: statsResult.labels,
                    datasets: [{
                        label: name.charAt(0).toUpperCase() + name.slice(1),
                        data: statsResult.data,
                        backgroundColor: pikachuColors.bg,
                        borderColor: pikachuColors.border,
                        borderWidth: 3,
                        pointBackgroundColor: pikachuColors.border,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            angleLines: {
                                color: '#f7d53d88',
                            },
                            grid: {
                                color: '#f7d53d66',
                            },
                            pointLabels: {
                                color: '#ccaa00',
                                font: { size: 14, weight: '600' }
                            },
                            ticks: {
                                backdropColor: 'rgba(255,255,255,0.7)',
                                color: '#bb9900',
                                stepSize: 20,
                                beginAtZero: true,
                                max: 200
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: '#b37700',
                                font: { weight: '700', size: 16 }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#ffe64d',
                            titleColor: '#663d00',
                            bodyColor: '#663d00',
                            cornerRadius: 8,
                        }
                    }
                }
            });

            const img = document.getElementById('pokemon-image');
            img.src = `/poke-image/${name}.png`;
            img.alt = `Imagen de ${name}`;
        });
    </script>
</body>
</html>
