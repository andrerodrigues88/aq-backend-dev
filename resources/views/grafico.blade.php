<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gráfico - AgronomiQ</title>

    <!-- Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            color: white;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Botões de navegação */
        .nav-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .nav-btn {
            padding: 12px 24px;
            background-color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: #333;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .nav-btn.home {
            background-color: #FF9800;
            color: white;
        }

        .nav-btn.map {
            background-color: #4CAF50;
            color: white;
        }

        /* Card do gráfico */
        .chart-card {
            background-color: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .chart-title {
            font-size: 1.8rem;
            color: #333;
            font-weight: 700;
        }

        /* Botões de controle do gráfico */
        .chart-controls {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .control-btn {
            padding: 10px 20px;
            border: 2px solid #ddd;
            background-color: white;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: #666;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .control-btn:hover {
            border-color: #667eea;
            color: #667eea;
            transform: scale(1.05);
        }

        .control-btn.active {
            background-color: #667eea;
            border-color: #667eea;
            color: white;
        }

        .control-btn i {
            font-size: 16px;
        }

        /* Container do canvas */
        .chart-container {
            position: relative;
            height: 500px;
            margin-top: 20px;
        }

        /* Legenda customizada */
        .chart-legend {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #666;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 4px;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }

            .chart-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .chart-container {
                height: 350px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1><i class="fas fa-chart-line"></i> Análise de Dados Climáticos</h1>
            <p>Visualização de Temperatura e Precipitação</p>
        </div>

        <!-- Botões de Navegação -->
        <div class="nav-buttons">
            <a href="/" class="nav-btn home">
                <i class="fas fa-home"></i> Início
            </a>
            <a href="/mapa" class="nav-btn map">
                <i class="fas fa-map"></i> Mapa
            </a>
        </div>

        <!-- Card do Gráfico -->
        <div class="chart-card">
            <div class="chart-header">
                <h2 class="chart-title">Dados Mensais</h2>
                
                <!-- Controles do Gráfico -->
                <div class="chart-controls">
                    <button class="control-btn active" id="btnBoth" onclick="toggleDataset('both')">
                        <i class="fas fa-chart-bar"></i> Ambos
                    </button>
                    <button class="control-btn" id="btnTemp" onclick="toggleDataset('temperature')">
                        <i class="fas fa-temperature-high"></i> Temperatura
                    </button>
                    <button class="control-btn" id="btnPrec" onclick="toggleDataset('precipitation')">
                        <i class="fas fa-cloud-rain"></i> Precipitação
                    </button>
                </div>
            </div>

            <!-- Container do Canvas -->
            <div class="chart-container">
                <canvas id="myChart"></canvas>
            </div>

            <!-- Legenda -->
            <div class="chart-legend">
                <div class="legend-item">
                    <div class="legend-color" style="background-color: rgb(255, 99, 132);"></div>
                    <span>Temperatura (°C)</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: rgb(54, 162, 235);"></div>
                    <span>Precipitação (mm)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        /**
         * Variáveis globais do gráfico
         * @type {Chart} chart - Instância do Chart.js
         * @type {string} currentView - Controla qual dataset está visível ('both', 'temperature', 'precipitation')
         */
        let chart;
        let currentView = 'both';

        /**
         * Dados de temperatura mensal (em °C)
         * Representa as temperaturas médias ao longo de 12 meses
         */
        const temperatureData = [22, 24, 23, 21, 18, 16, 15, 17, 19, 20, 21, 23];

        /**
         * Dados de precipitação mensal (em mm)
         * Representa a precipitação acumulada ao longo de 12 meses
         */
        const precipitationData = [180, 160, 140, 90, 70, 50, 45, 55, 80, 110, 130, 170];

        /**
         * Labels dos meses do ano
         */
        const labels = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

        /**
         * Inicializa o gráfico com Chart.js
         * Configura dois datasets: temperatura (linha) e precipitação (barras)
         */
        function initChart() {
            const ctx = document.getElementById('myChart').getContext('2d');
            
            chart = new Chart(ctx, {
                type: 'bar', // Tipo base (será sobrescrito por dataset)
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Temperatura (°C)',
                            data: temperatureData,
                            type: 'line', // Gráfico de linhas para temperatura
                            borderColor: 'rgb(255, 99, 132)',
                            backgroundColor: 'rgba(255, 99, 132, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4, // Suavização da linha
                            yAxisID: 'y',
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            pointBackgroundColor: 'rgb(255, 99, 132)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2
                        },
                        {
                            label: 'Precipitação (mm)',
                            data: precipitationData,
                            type: 'bar', // Gráfico de barras para precipitação
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgb(54, 162, 235)',
                            borderWidth: 2,
                            yAxisID: 'y1',
                            borderRadius: 8,
                            borderSkipped: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            display: false // Legenda customizada no HTML
                        },
                        title: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            borderColor: 'rgba(255, 255, 255, 0.3)',
                            borderWidth: 1
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Temperatura (°C)',
                                color: 'rgb(255, 99, 132)',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            },
                            ticks: {
                                color: 'rgb(255, 99, 132)'
                            },
                            grid: {
                                color: 'rgba(255, 99, 132, 0.1)'
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Precipitação (mm)',
                                color: 'rgb(54, 162, 235)',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            },
                            ticks: {
                                color: 'rgb(54, 162, 235)'
                            },
                            grid: {
                                drawOnChartArea: false
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 12,
                                    weight: '600'
                                }
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        /**
         * Alterna a visualização dos datasets no gráfico
         * @param {string} view - Tipo de visualização ('both', 'temperature', 'precipitation')
         * 
         * Funcionalidade:
         * - 'both': Exibe temperatura e precipitação juntos
         * - 'temperature': Exibe apenas temperatura
         * - 'precipitation': Exibe apenas precipitação
         */
        function toggleDataset(view) {
            currentView = view;
            
            // Atualizar botões ativos
            document.querySelectorAll('.control-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            if (view === 'both') {
                document.getElementById('btnBoth').classList.add('active');
                chart.data.datasets[0].hidden = false; // Mostrar temperatura
                chart.data.datasets[1].hidden = false; // Mostrar precipitação
            } else if (view === 'temperature') {
                document.getElementById('btnTemp').classList.add('active');
                chart.data.datasets[0].hidden = false; // Mostrar temperatura
                chart.data.datasets[1].hidden = true;  // Ocultar precipitação
            } else if (view === 'precipitation') {
                document.getElementById('btnPrec').classList.add('active');
                chart.data.datasets[0].hidden = true;  // Ocultar temperatura
                chart.data.datasets[1].hidden = false; // Mostrar precipitação
            }
            
            // Atualizar o gráfico com animação
            chart.update('active');
        }

        // Inicializar o gráfico quando a página carregar
        window.onload = initChart;
    </script>
</body>
</html>
