<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MunicipioGeometria;

/**
 * Controller para localização de municípios por coordenadas
 */
class LocalizacaoController extends Controller
{
    /**
     * Localiza o município correspondente a uma latitude e longitude
     * 
     * Endpoint: GET /api/localizar-municipio?latitude={lat}&longitude={lng}
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function localizarMunicipio(Request $request)
    {
        // Valida os parâmetros de entrada
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180'
        ]);

        $latitude = $validated['latitude'];
        $longitude = $validated['longitude'];

        // Cria um ponto a partir das coordenadas fornecidas
        // Usa ST_Contains para verificar se o ponto está dentro da geometria do município
        $municipio = DB::table('municipios_geometria')
            ->select('id', 'nome_municipio', DB::raw('ST_AsGeoJSON(geom) as geom'))
            ->whereRaw("ST_Contains(geom, ST_SetSRID(ST_MakePoint(?, ?), 4326))", [$longitude, $latitude])
            ->first();

        // Se não encontrou município, retorna erro 404
        if (!$municipio) {
            return response()->json([
                'success' => false,
                'message' => 'Município não encontrado para as coordenadas fornecidas',
                'data' => [
                    'latitude' => $latitude,
                    'longitude' => $longitude
                ]
            ], 404);
        }

        // Retorna o município encontrado
        return response()->json([
            'success' => true,
            'message' => 'Município localizado com sucesso',
            'data' => [
                'id' => $municipio->id,
                'nome_municipio' => $municipio->nome_municipio,
                'coordenadas' => [
                    'latitude' => $latitude,
                    'longitude' => $longitude
                ],
                'geometria' => json_decode($municipio->geom)
            ]
        ], 200);
    }
}
