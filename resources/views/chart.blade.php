<!DOCTYPE html>
<html>
<head>
    <title>Estadísticas Pokémon</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    <div style="width: 400px; margin-top: 20px;">
        <canvas id="pokemonChart"></canvas>
    </div>

    <script>
        const ctx = document.getElementById('pokemonChart').getContext('2d');
        let chart;

        document.getElementById('pokemon-select').addEventListener('change', function () {
            const name = this.value;
            if (!name) return;

            fetch(`/stats/${name}`)
                .then(res => res.json())
                .then(result => {
                    if (chart) chart.destroy();

                    chart = new Chart(ctx, {
                        type: 'radar',
                        data: {
                            labels: result.labels,
                            datasets: [{
                                label: name.charAt(0).toUpperCase() + name.slice(1),
                                data: result.data,
                                backgroundColor: result.color.bg,
                                borderColor: result.color.border,
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                r: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                });
        });
    </script>
</body>
</html>

