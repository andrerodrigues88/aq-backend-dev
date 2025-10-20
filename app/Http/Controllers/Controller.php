<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Controller Base da Aplicação
 * 
 * Esta classe serve como controlador base para todos os controladores da aplicação.
 * Ela utiliza traits do Laravel para fornecer funcionalidades comuns como:
 * - Autorização de requisições (AuthorizesRequests)
 * - Despacho de jobs (DispatchesJobs)
 * - Validação de requisições (ValidatesRequests)
 * 
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
