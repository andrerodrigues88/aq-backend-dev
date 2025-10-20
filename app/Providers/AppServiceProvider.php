<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * App Service Provider
 * 
 * Este service provider é responsável por registrar e inicializar
 * serviços da aplicação. É um dos primeiros providers a ser carregado
 * durante o bootstrap da aplicação Laravel.
 * 
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Registra serviços da aplicação
     * 
     * Este método é chamado durante o registro de todos os service providers.
     * Use este método para vincular classes ao container de serviços.
     *
     * @return void
     */
    public function register()
    {
        // Registre bindings do container aqui
    }

    /**
     * Inicializa serviços da aplicação
     * 
     * Este método é chamado após todos os service providers terem sido registrados.
     * Use este método para realizar ações que dependem de outros services providers.
     *
     * @return void
     */
    public function boot()
    {
        // Inicialize serviços da aplicação aqui
    }
}
