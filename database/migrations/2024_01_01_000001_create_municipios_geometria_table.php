<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Migration para criar a tabela municipios_geometria
 * Armazena as geometrias dos municípios de SP e MG
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('municipios_geometria', function (Blueprint $table) {
            $table->id();
            $table->string('nome_municipio');
            $table->timestamps();
        });

        // Adiciona a coluna de geometria usando SQL direto
        // Tipo: Geometry (aceita qualquer tipo de geometria)
        // SRID: 4326 (WGS84 - padrão GPS)
        DB::statement('ALTER TABLE municipios_geometria ADD COLUMN geom geometry(Geometry, 4326)');
        
        // Cria índice espacial para melhorar performance das consultas geoespaciais
        DB::statement('CREATE INDEX municipios_geometria_geom_idx ON municipios_geometria USING GIST (geom)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('municipios_geometria');
    }
};
