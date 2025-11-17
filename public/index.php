<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Verificar Se a Aplicação Está em Manutenção
|--------------------------------------------------------------------------
|
| Se a aplicação estiver em modo de manutenção/demo via o comando "down",
| carregaremos este arquivo para que qualquer conteúdo pré-renderizado possa
| ser exibido em vez de iniciar o framework, o que poderia causar uma exceção.
|
*/

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Registrar o Auto Loader
|--------------------------------------------------------------------------
|
| O Composer fornece um carregador de classes conveniente e gerado
| automaticamente para esta aplicação. Só precisamos utilizá-lo! Simplesmente
| vamos requerê-lo no script aqui para que não precisemos carregar nossas
| classes manualmente.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Executar a Aplicação
|--------------------------------------------------------------------------
|
| Uma vez que temos a aplicação, podemos lidar com a requisição recebida usando
| o kernel HTTP da aplicação. Em seguida, enviaremos a resposta de volta
| para o navegador do cliente, permitindo que eles usem nossa aplicação.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
