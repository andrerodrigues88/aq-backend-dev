<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Migration para criar a tabela pontos_usuario
 * Armazena pontos postados pelos usuários com suas coordenadas
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
        Schema::create('pontos_usuario', function (Blueprint $table) {
            $table->id();
            $table->decimal('latitude', 10, 8);  // Latitude com 8 casas decimais
            $table->decimal('longitude', 11, 8); // Longitude com 8 casas decimais
            $table->foreignId('municipio_id')->nullable()->constrained('municipios_geometria')->onDelete('set null');
            $table->timestamps();
        });

        // Adiciona a coluna de geometria usando SQL direto
        // Tipo: Geometry (aceita qualquer tipo de geometria, mas será usado para pontos)
        // SRID: 4326 (WGS84 - padrão GPS)
        DB::statement('ALTER TABLE pontos_usuario ADD COLUMN geom geometry(Geometry, 4326)');
        
        // Cria índice espacial para melhorar performance das consultas geoespaciais
        DB::statement('CREATE INDEX pontos_usuario_geom_idx ON pontos_usuario USING GIST (geom)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pontos_usuario');
    }
};
