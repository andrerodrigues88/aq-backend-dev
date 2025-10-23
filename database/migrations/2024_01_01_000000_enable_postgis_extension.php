<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Migration para habilitar a extensão PostGIS no PostgreSQL
 * Esta extensão é necessária para trabalhar com dados geoespaciais
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
        // Habilita a extensão PostGIS no banco de dados
        DB::statement('CREATE EXTENSION IF NOT EXISTS postgis');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove a extensão PostGIS
        DB::statement('DROP EXTENSION IF EXISTS postgis');
    }
};
