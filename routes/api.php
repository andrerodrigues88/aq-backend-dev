<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocalizacaoController;
use App\Http\Controllers\PontoUsuarioController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Rotas Geoespaciais
|--------------------------------------------------------------------------
*/

/**
 * Endpoint para localizar município por latitude e longitude
 * GET /api/localizar-municipio?latitude={lat}&longitude={lng}
 */
Route::get('/localizar-municipio', [LocalizacaoController::class, 'localizarMunicipio']);

/**
 * CRUD completo para pontos de usuário
 * GET    /api/pontos       - Lista todos os pontos
 * POST   /api/pontos       - Cria um novo ponto
 * GET    /api/pontos/{id}  - Exibe um ponto específico
 * PUT    /api/pontos/{id}  - Atualiza um ponto
 * DELETE /api/pontos/{id}  - Remove um ponto
 */
Route::apiResource('pontos', PontoUsuarioController::class);
