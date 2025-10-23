<?php

namespace App\Http\Controllers;

use App\Models\PontoUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controller para CRUD de pontos de usuário
 * Gerencia os pontos postados pelos usuários
 */
class PontoUsuarioController extends Controller
{
    /**
     * Lista todos os pontos de usuário
     * 
     * Endpoint: GET /api/pontos
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $pontos = DB::table('pontos_usuario')
            ->select(
                'id',
                'latitude',
                'longitude',
                'municipio_id',
                DB::raw('ST_AsGeoJSON(geom) as geom'),
                'created_at',
                'updated_at'
            )
            ->get()
            ->map(function ($ponto) {
                $ponto->geom = json_decode($ponto->geom);
                return $ponto;
            });

        return response()->json([
            'success' => true,
            'message' => 'Pontos recuperados com sucesso',
            'data' => $pontos
        ], 200);
    }

    /**
     * Cria um novo ponto de usuário
     * 
     * Endpoint: POST /api/pontos
     * Body: { "latitude": -23.5505, "longitude": -46.6333 }
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Valida os dados de entrada
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180'
        ]);

        $latitude = $validated['latitude'];
        $longitude = $validated['longitude'];

        // Busca o município correspondente às coordenadas
        $municipio = DB::table('municipios_geometria')
            ->select('id')
            ->whereRaw("ST_Contains(geom, ST_SetSRID(ST_MakePoint(?, ?), 4326))", [$longitude, $latitude])
            ->first();

        $municipioId = $municipio ? $municipio->id : null;

        // Insere o ponto no banco de dados
        // Usa ST_MakePoint para criar a geometria do ponto
        $pontoId = DB::table('pontos_usuario')->insertGetId([
            'latitude' => $latitude,
            'longitude' => $longitude,
            'municipio_id' => $municipioId,
            'geom' => DB::raw("ST_SetSRID(ST_MakePoint($longitude, $latitude), 4326)"),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Recupera o ponto criado
        $ponto = DB::table('pontos_usuario')
            ->select(
                'id',
                'latitude',
                'longitude',
                'municipio_id',
                DB::raw('ST_AsGeoJSON(geom) as geom'),
                'created_at',
                'updated_at'
            )
            ->where('id', $pontoId)
            ->first();

        $ponto->geom = json_decode($ponto->geom);

        return response()->json([
            'success' => true,
            'message' => 'Ponto criado com sucesso',
            'data' => $ponto
        ], 201);
    }

    /**
     * Exibe um ponto específico
     * 
     * Endpoint: GET /api/pontos/{id}
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $ponto = DB::table('pontos_usuario')
            ->select(
                'id',
                'latitude',
                'longitude',
                'municipio_id',
                DB::raw('ST_AsGeoJSON(geom) as geom'),
                'created_at',
                'updated_at'
            )
            ->where('id', $id)
            ->first();

        if (!$ponto) {
            return response()->json([
                'success' => false,
                'message' => 'Ponto não encontrado'
            ], 404);
        }

        $ponto->geom = json_decode($ponto->geom);

        return response()->json([
            'success' => true,
            'message' => 'Ponto recuperado com sucesso',
            'data' => $ponto
        ], 200);
    }

    /**
     * Atualiza um ponto existente
     * 
     * Endpoint: PUT /api/pontos/{id}
     * Body: { "latitude": -23.5505, "longitude": -46.6333 }
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Verifica se o ponto existe
        $pontoExiste = DB::table('pontos_usuario')->where('id', $id)->exists();

        if (!$pontoExiste) {
            return response()->json([
                'success' => false,
                'message' => 'Ponto não encontrado'
            ], 404);
        }

        // Valida os dados de entrada
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180'
        ]);

        $latitude = $validated['latitude'];
        $longitude = $validated['longitude'];

        // Busca o município correspondente às novas coordenadas
        $municipio = DB::table('municipios_geometria')
            ->select('id')
            ->whereRaw("ST_Contains(geom, ST_SetSRID(ST_MakePoint(?, ?), 4326))", [$longitude, $latitude])
            ->first();

        $municipioId = $municipio ? $municipio->id : null;

        // Atualiza o ponto
        DB::table('pontos_usuario')
            ->where('id', $id)
            ->update([
                'latitude' => $latitude,
                'longitude' => $longitude,
                'municipio_id' => $municipioId,
                'geom' => DB::raw("ST_SetSRID(ST_MakePoint($longitude, $latitude), 4326)"),
                'updated_at' => now()
            ]);

        // Recupera o ponto atualizado
        $ponto = DB::table('pontos_usuario')
            ->select(
                'id',
                'latitude',
                'longitude',
                'municipio_id',
                DB::raw('ST_AsGeoJSON(geom) as geom'),
                'created_at',
                'updated_at'
            )
            ->where('id', $id)
            ->first();

        $ponto->geom = json_decode($ponto->geom);

        return response()->json([
            'success' => true,
            'message' => 'Ponto atualizado com sucesso',
            'data' => $ponto
        ], 200);
    }

    /**
     * Remove um ponto
     * 
     * Endpoint: DELETE /api/pontos/{id}
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Verifica se o ponto existe
        $pontoExiste = DB::table('pontos_usuario')->where('id', $id)->exists();

        if (!$pontoExiste) {
            return response()->json([
                'success' => false,
                'message' => 'Ponto não encontrado'
            ], 404);
        }

        // Remove o ponto
        DB::table('pontos_usuario')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ponto removido com sucesso'
        ], 200);
    }
}
