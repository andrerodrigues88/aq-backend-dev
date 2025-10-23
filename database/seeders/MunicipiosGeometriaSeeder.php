<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

/**
 * Seeder para popular a tabela municipios_geometria
 * Importa dados dos GeoJSONs de municípios de SP e MG
 */
class MunicipiosGeometriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // URLs dos GeoJSONs conforme especificado no teste
        $urls = [
            'SP' => 'https://raw.githubusercontent.com/tbrugz/geodata-br/master/geojson/geojs-35-mun.json',
            'MG' => 'https://raw.githubusercontent.com/tbrugz/geodata-br/master/geojson/geojs-31-mun.json'
        ];

        foreach ($urls as $estado => $url) {
            echo "Importando municípios de {$estado}...\n";
            
            // Baixa o GeoJSON (desabilita verificação SSL para evitar erro de certificado)
            $response = Http::withoutVerifying()->timeout(60)->get($url);
            
            if (!$response->successful()) {
                echo "Erro ao baixar GeoJSON de {$estado}\n";
                continue;
            }

            $geojson = $response->json();

            // Processa cada feature (município)
            foreach ($geojson['features'] as $feature) {
                $nomeMunicipio = $feature['properties']['name'];
                $geometry = json_encode($feature['geometry']);

                // Insere o município usando ST_GeomFromGeoJSON para converter GeoJSON em geometria
                DB::statement("
                    INSERT INTO municipios_geometria (nome_municipio, geom, created_at, updated_at)
                    VALUES (?, ST_GeomFromGeoJSON(?), NOW(), NOW())
                ", [$nomeMunicipio, $geometry]);
            }

            echo "Importados " . count($geojson['features']) . " municípios de {$estado}\n";
        }

        echo "Importação de municípios concluída!\n";
    }
}
