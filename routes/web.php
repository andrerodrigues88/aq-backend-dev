<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aqui são registradas as rotas web da aplicação.
| Estas rotas são carregadas pelo RouteServiceProvider dentro de um grupo
| que contém o middleware "web".
|
| Rotas disponíveis:
| - GET / : Página inicial com cartões de navegação
| - GET /mapa : Mapa interativo com OpenLayers
| - GET /grafico : Visualização de gráficos com Chart.js
|
*/

/**
 * Rota da Página Inicial
 * 
 * Exibe a página inicial da aplicação com dois cartões de navegação:
 * - Cartão "Mapa": Redireciona para /mapa
 * - Cartão "Gráfico": Redireciona para /grafico
 * 
 * @return \Illuminate\View\View
 */
Route::get('/', function () {
    return view('welcome');
});

/**
 * Rota do Mapa Interativo
 * 
 * Exibe um mapa interativo utilizando OpenLayers com as seguintes funcionalidades:
 * - Visualização de coordenadas do mouse em tempo real
 * - Context menu (popup) com latitude, longitude e data/hora
 * - Botões de zoom in/out
 * - Alternância entre OpenStreetMap e Google Satellite
 * - Navegação contínua para outras páginas
 * 
 * Tecnologias: OpenLayers 8.2.0, Font Awesome 6.4.0
 * 
 * @return \Illuminate\View\View
 */
Route::get('/mapa', function () {
    return view('mapa');
});

/**
 * Rota de Visualização de Gráficos
 * 
 * Exibe gráficos interativos de dados climáticos utilizando Chart.js:
 * - Gráfico de linhas para temperatura mensal
 * - Gráfico de barras para precipitação mensal
 * - Botão para alternar entre datasets (Ambos/Temperatura/Precipitação)
 * - Navegação contínua para outras páginas
 * 
 * Tecnologias: Chart.js 4.4.0, Font Awesome 6.4.0
 * 
 * @return \Illuminate\View\View
 */
Route::get('/grafico', function () {
    return view('grafico');
});
