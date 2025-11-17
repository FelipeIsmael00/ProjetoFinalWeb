<?php

/*
|--------------------------------------------------------------------------
| Criar a Aplicação
|--------------------------------------------------------------------------
|
| A primeira coisa que faremos é criar uma nova instância da aplicação Laravel
| que serve como a "cola" para todos os componentes do Laravel, e é
| o container IoC do sistema que vincula todas as várias partes.
|
*/

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| Vincular Interfaces Importantes
|--------------------------------------------------------------------------
|
| Em seguida, precisamos vincular algumas interfaces importantes no container
| para que possamos resolvê-las quando necessário. Os kernels servem as
| requisições recebidas nesta aplicação tanto da web quanto do CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Retornar a Aplicação
|--------------------------------------------------------------------------
|
| Este script retorna a instância da aplicação. A instância é fornecida ao
| script chamador para que possamos separar a construção das instâncias
| da execução real da aplicação e do envio de respostas.
|
*/

return $app;
