<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Migration para criar a tabela estados_geometria
 * Armazena as geometrias dos estados SP e MG
 * Criada a partir do dissolve/união das geometrias dos municípios
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
        Schema::create('estados_geometria', function (Blueprint $table) {
            $table->id();
            $table->string('nome_estado');
            $table->timestamps();
        });

        // Adiciona a coluna de geometria usando SQL direto
        // Tipo: Geometry (aceita qualquer tipo de geometria)
        // SRID: 4326 (WGS84 - padrão GPS)
        DB::statement('ALTER TABLE estados_geometria ADD COLUMN geom geometry(Geometry, 4326)');
        
        // Cria índice espacial para melhorar performance das consultas geoespaciais
        DB::statement('CREATE INDEX estados_geometria_geom_idx ON estados_geometria USING GIST (geom)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estados_geometria');
    }
};
