<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mapa - AgronomiQ</title>

    <!-- OpenLayers CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v8.2.0/ol.css">
    
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
            overflow: hidden;
        }

        /* Container do mapa ocupa toda a tela */
        #map {
            width: 100vw;
            height: 100vh;
        }

        /* Componente de coordenadas do mouse - fixo no canto inferior esquerdo */
        .mouse-position {
            position: fixed;
            bottom: 20px;
            left: 20px;
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 12px 18px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            color: #333;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            min-width: 280px;
            border-left: 3px solid #2196F3;
            font-family: 'Courier New', monospace;
        }

        .mouse-position strong {
            color: #2196F3;
            margin-right: 4px;
        }

        /* Context menu (popup) com espaçamento maior */
        .ol-popup {
            position: absolute;
            background-color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #ccc;
            bottom: 12px;
            left: -50px;
            min-width: 280px;
        }

        .ol-popup:after, .ol-popup:before {
            top: 100%;
            border: solid transparent;
            content: " ";
            height: 0;
            width: 0;
            position: absolute;
            pointer-events: none;
        }

        .ol-popup:after {
            border-top-color: white;
            border-width: 10px;
            left: 48px;
            margin-left: -10px;
        }

        .ol-popup:before {
            border-top-color: #ccc;
            border-width: 11px;
            left: 48px;
            margin-left: -11px;
        }

        .ol-popup-closer {
            text-decoration: none;
            position: absolute;
            top: 8px;
            right: 8px;
            color: #999;
            font-size: 20px;
        }

        .ol-popup-closer:hover {
            color: #333;
        }

        .popup-content {
            margin: 10px 0;
        }

        .popup-content p {
            margin: 12px 0;
            font-size: 14px;
            color: #555;
            line-height: 1.6;
        }

        .popup-content strong {
            color: #333;
            font-weight: 600;
        }

        .popup-content i {
            margin-right: 8px;
            color: #4CAF50;
        }

        /* Botões flutuantes de zoom */
        .zoom-controls {
            position: fixed;
            top: 20px;
            left: 20px;
            display: flex;
            flex-direction: column;
            gap: 2px;
            z-index: 1000;
            background: white;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .zoom-btn {
            width: 40px;
            height: 40px;
            background-color: white;
            border: none;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            color: #333;
        }

        .zoom-btn:last-child {
            border-bottom: none;
        }

        .zoom-btn:hover {
            background-color: #f5f5f5;
            color: #2196F3;
        }

        .zoom-btn:active {
            background-color: #e0e0e0;
        }

        .zoom-btn i {
            font-size: 18px;
            font-weight: bold;
        }

        /* Botão de alternar mapa de fundo */
        .toggle-layer-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 18px;
            background-color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            font-size: 13px;
            font-weight: 600;
            color: #333;
            transition: all 0.2s ease;
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .toggle-layer-btn:hover {
            background-color: #f5f5f5;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.25);
            transform: translateY(-1px);
        }

        .toggle-layer-btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .toggle-layer-btn i {
            font-size: 16px;
            color: #2196F3;
        }

        /* Botões de navegação */
        .nav-buttons {
            position: fixed;
            top: 80px;
            left: 20px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            z-index: 1000;
        }

        .nav-btn {
            padding: 10px 16px;
            background-color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            font-size: 13px;
            font-weight: 600;
            color: #333;
            transition: all 0.2s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 120px;
        }

        .nav-btn:hover {
            background-color: #f5f5f5;
            transform: translateX(2px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.25);
        }

        .nav-btn.home:hover {
            color: #FF9800;
        }

        .nav-btn.chart:hover {
            color: #4CAF50;
        }

        .nav-btn i {
            font-size: 16px;
        }

        /* Escala do mapa - movida para a direita */
        .ol-scale-line {
            left: auto !important;
            right: 20px !important;
            bottom: 20px !important;
        }
    </style>
</head>
<body>
    <!-- Botões de navegação -->
    <div class="nav-buttons">
        <a href="/" class="nav-btn home">
            <i class="fas fa-home"></i>
            <span>Início</span>
        </a>
        <a href="/grafico" class="nav-btn chart">
            <i class="fas fa-chart-bar"></i>
            <span>Gráfico</span>
        </a>
    </div>

    <!-- Controles de Zoom -->
    <div class="zoom-controls">
        <button class="zoom-btn" onclick="ZoomInOut('in')" title="Zoom In">
            <i class="fas fa-plus"></i>
        </button>
        <button class="zoom-btn" onclick="ZoomInOut('out')" title="Zoom Out">
            <i class="fas fa-minus"></i>
        </button>
    </div>

    <!-- Botão para alternar mapa de fundo -->
    <button class="toggle-layer-btn" onclick="ToggleRaster()" id="toggleBtn">
        <i class="fas fa-layer-group"></i>
        <span id="layerName">OpenStreetMap</span>
    </button>

    <!-- Container do mapa -->
    <div id="map"></div>

    <!-- Componente de coordenadas do mouse -->
    <div class="mouse-position" id="mouse-position"></div>

    <!-- Popup para context menu -->
    <div id="popup" class="ol-popup">
        <a href="#" id="popup-closer" class="ol-popup-closer">×</a>
        <div id="popup-content" class="popup-content"></div>
    </div>

    <!-- OpenLayers JS -->
    <script src="https://cdn.jsdelivr.net/npm/ol@v8.2.0/dist/ol.js"></script>

    <script>
        /**
         * Variáveis globais do mapa
         * @type {ol.Map} map - Instância do mapa OpenLayers
         * @type {ol.layer.Tile} osmLayer - Camada OpenStreetMap
         * @type {ol.layer.Tile} satelliteLayer - Camada Google Satellite
         * @type {boolean} isOSM - Flag para controlar qual camada está ativa
         */
        let map;
        let osmLayer;
        let satelliteLayer;
        let isOSM = true;

        /**
         * Inicializa o mapa com OpenLayers
         * Configura as camadas base (OSM e Google Satellite),
         * controles de mouse e popup de context menu
         */
        function initMap() {
            try {
                console.log('Iniciando configuração do mapa...');
                
                // Camada OpenStreetMap
                osmLayer = new ol.layer.Tile({
                    source: new ol.source.OSM(),
                    visible: true
                });
                console.log('Camada OSM criada');

                // Camada Google Satellite
                satelliteLayer = new ol.layer.Tile({
                    source: new ol.source.XYZ({
                        url: 'https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',
                        attributions: '© Google'
                    }),
                    visible: false
                });
                console.log('Camada Satellite criada');

                // Configuração do mapa
                map = new ol.Map({
                    target: 'map',
                    layers: [osmLayer, satelliteLayer],
                    view: new ol.View({
                        center: ol.proj.fromLonLat([-46.6333, -23.5505]), // São Paulo
                        zoom: 10
                    })
                });
                
                // Adicionar barra de escala
                const scaleControl = new ol.control.ScaleLine();
                map.addControl(scaleControl);
                
                console.log('Mapa criado com sucesso!');

            // Atualizar coordenadas do mouse
            map.on('pointermove', function(evt) {
                const coords = ol.proj.toLonLat(evt.coordinate);
                const lon = coords[0].toFixed(6);
                const lat = coords[1].toFixed(6);
                document.getElementById('mouse-position').innerHTML = 
                    `<strong>Lat:</strong> ${lat} | <strong>Lon:</strong> ${lon}`;
            });

                // Configurar popup de context menu
                setupPopup();
                
            } catch (error) {
                console.error('Erro ao inicializar o mapa:', error);
                document.getElementById('map').innerHTML = '<div style="display: flex; align-items: center; justify-content: center; height: 100%; color: red; font-size: 18px; padding: 20px; text-align: center;">Erro ao carregar o mapa: ' + error.message + '</div>';
            }
        }

        /**
         * Configura o popup de context menu que aparece ao clicar com botão direito
         * Exibe latitude, longitude e data/hora do clique
         */
        function setupPopup() {
            const container = document.getElementById('popup');
            const content = document.getElementById('popup-content');
            const closer = document.getElementById('popup-closer');

            // Criar overlay para o popup
            const overlay = new ol.Overlay({
                element: container,
                autoPan: {
                    animation: {
                        duration: 250
                    }
                }
            });

            map.addOverlay(overlay);

            // Fechar popup ao clicar no X
            closer.onclick = function() {
                overlay.setPosition(undefined);
                closer.blur();
                return false;
            };

            // Abrir popup ao clicar com botão direito
            map.getViewport().addEventListener('contextmenu', function(evt) {
                evt.preventDefault();
                
                const pixel = map.getEventPixel(evt);
                const coordinate = map.getCoordinateFromPixel(pixel);
                const coords = ol.proj.toLonLat(coordinate);
                
                const lat = coords[1].toFixed(6);
                const lon = coords[0].toFixed(6);
                
                // Obter data/hora atual formatada
                const now = new Date();
                const dateTime = formatDateTime(now);
                
                // Montar conteúdo do popup com espaçamento maior
                content.innerHTML = `
                    <p><i class="fas fa-map-marker-alt"></i><strong>Latitude:</strong> ${lat}</p>
                    <p><i class="fas fa-map-marker-alt"></i><strong>Longitude:</strong> ${lon}</p>
                    <p><i class="fas fa-calendar-alt"></i><strong>Data/Hora:</strong> ${dateTime}</p>
                `;
                
                overlay.setPosition(coordinate);
            });
        }

        /**
         * Formata data/hora no padrão DD/MM/YYYY HH:mm:ss
         * @param {Date} date - Objeto Date a ser formatado
         * @returns {string} Data formatada
         */
        function formatDateTime(date) {
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const seconds = String(date.getSeconds()).padStart(2, '0');
            
            return `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
        }

        /**
         * Controla o zoom in/out do mapa
         * @param {string} action - 'in' para zoom in, 'out' para zoom out
         */
        function ZoomInOut(action) {
            const view = map.getView();
            const currentZoom = view.getZoom();
            
            if (action === 'in') {
                view.animate({
                    zoom: currentZoom + 1,
                    duration: 300
                });
            } else if (action === 'out') {
                view.animate({
                    zoom: currentZoom - 1,
                    duration: 300
                });
            }
        }

        /**
         * Alterna entre as camadas de mapa (OpenStreetMap e Google Satellite)
         * Atualiza o texto do botão para indicar qual camada está ativa
         */
        function ToggleRaster() {
            isOSM = !isOSM;
            
            osmLayer.setVisible(isOSM);
            satelliteLayer.setVisible(!isOSM);
            
            const layerNameElement = document.getElementById('layerName');
            layerNameElement.textContent = isOSM ? 'OpenStreetMap' : 'Google Satellite';
        }

        // Inicializar o mapa quando a página carregar
        window.onload = function() {
            // Verificar se OpenLayers foi carregado
            if (typeof ol === 'undefined') {
                console.error('OpenLayers não foi carregado! Verifique a conexão com a internet.');
                document.getElementById('map').innerHTML = '<div style="display: flex; align-items: center; justify-content: center; height: 100%; color: red; font-size: 20px;">Erro: OpenLayers não carregou. Verifique sua conexão com a internet.</div>';
                return;
            }
            
            console.log('OpenLayers carregado com sucesso!');
            initMap();
        };
    </script>
</body>
</html>
