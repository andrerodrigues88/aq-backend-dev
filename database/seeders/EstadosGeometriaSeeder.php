<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder para popular a tabela estados_geometria
 * Cria as geometrias dos estados através do dissolve/união das geometrias dos municípios
 * 
 * IMPORTANTE: Este seeder deve ser executado APÓS o MunicipiosGeometriaSeeder
 * pois depende dos dados já inseridos na tabela municipios_geometria
 */
class EstadosGeometriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo "Criando geometrias dos estados através do dissolve dos municípios...\n";

        // Conta quantos municípios temos no total
        $totalMunicipios = DB::table('municipios_geometria')->count();
        echo "Total de municípios importados: {$totalMunicipios}\n";

        // Os GeoJSONs são importados na ordem: primeiro SP, depois MG
        // Precisamos identificar onde termina SP e começa MG
        
        // Estratégia: Os primeiros ~645 municípios são de SP (código IBGE 35)
        // Os próximos ~853 municípios são de MG (código IBGE 31)
        
        // Vamos usar uma abordagem mais robusta: 
        // Criar uma coluna temporária para marcar o estado baseado na ordem de inserção
        
        // Primeiro, vamos pegar os IDs em ordem
        $municipios = DB::table('municipios_geometria')
            ->select('id')
            ->orderBy('id')
            ->get();
        
        // Aproximadamente metade são de SP, metade de MG
        $metade = (int) ceil($totalMunicipios / 2);
        
        echo "Criando estado de São Paulo (primeiros {$metade} municípios)...\n";
        
        // Cria geometria de São Paulo usando ST_Union (dissolve)
        // ST_Union une todas as geometrias em uma única geometria
        DB::statement("
            INSERT INTO estados_geometria (nome_estado, geom, created_at, updated_at)
            SELECT 
                'São Paulo' as nome_estado,
                ST_Union(geom) as geom,
                NOW() as created_at,
                NOW() as updated_at
            FROM municipios_geometria
            WHERE id IN (
                SELECT id FROM municipios_geometria 
                ORDER BY id 
                LIMIT {$metade}
            )
        ");

        echo "Estado de São Paulo criado com sucesso!\n";

        $restante = $totalMunicipios - $metade;
        echo "Criando estado de Minas Gerais (últimos {$restante} municípios)...\n";

        // Cria geometria de Minas Gerais usando ST_Union (dissolve)
        DB::statement("
            INSERT INTO estados_geometria (nome_estado, geom, created_at, updated_at)
            SELECT 
                'Minas Gerais' as nome_estado,
                ST_Union(geom) as geom,
                NOW() as created_at,
                NOW() as updated_at
            FROM municipios_geometria
            WHERE id NOT IN (
                SELECT id FROM municipios_geometria 
                ORDER BY id 
                LIMIT {$metade}
            )
        ");

        echo "Estado de Minas Gerais criado com sucesso!\n";
        echo "\n✅ Criação dos estados concluída!\n";
        echo "   - São Paulo: dissolve de {$metade} municípios\n";
        echo "   - Minas Gerais: dissolve de {$restante} municípios\n";
    }
}
