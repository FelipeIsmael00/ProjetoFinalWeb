<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Editor
    |--------------------------------------------------------------------------
    |
    | Choose your preferred editor to use when clicking error stack trace
    | links.
    |
    | Supported: "phpstorm", "vscode", "vscode-insiders", "textmate", "emacs",
    |            "sublime", "atom", "nova", "macvim", "idea", "netbeans",
    |            "xdebug"
    |
    */

    'editor' => env('IGNITION_EDITOR', 'phpstorm'),

    /*
    |--------------------------------------------------------------------------
    | Theme
    |--------------------------------------------------------------------------
    |
    | Here you may specify which theme Ignition should use.
    |
    | Supported: "light", "dark", "auto"
    |
    */

    'theme' => env('IGNITION_THEME', 'auto'),

    /*
    |--------------------------------------------------------------------------
    | Sharing
    |--------------------------------------------------------------------------
    |
    | You can share local errors with colleagues or others around the world.
    | Sharing is completely free and doesn't require an account on Flare.
    |
    | If enabled, you can share errors at /_ignition/share and the resulting
    | shareable link will be stored in the `ignition_shares` table.
    |
    */

    'enable_share_button' => env('IGNITION_ENABLE_SHARE_BUTTON', false),

    /*
    |--------------------------------------------------------------------------
    | Register Ignition routes
    |--------------------------------------------------------------------------
    |
    | Ignition routes are only registered when this option is enabled.
    |
    */

    'register_routes' => env('IGNITION_REGISTER_ROUTES', true),

    /*
    |--------------------------------------------------------------------------
    | Ignored Solution Providers
    |--------------------------------------------------------------------------
    |
    | You may list solution providers that should not be loaded. Ignition
    | will ignore these solution providers and not suggest any solutions
    | from them.
    |
    */

    'ignored_solution_providers' => [
        \Spatie\Ignition\Solutions\SolutionProviders\MissingPackageSolutionProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Runnable Solutions
    |--------------------------------------------------------------------------
    |
    | Some solutions that Ignition displays are runnable and can perform
    | various tasks. Runnable solutions are enabled when your app has
    | debug mode enabled. You may also fully disable this feature.
    |
    */

    'enable_runnable_solutions' => env('IGNITION_ENABLE_RUNNABLE_SOLUTIONS', null),

    /*
    |--------------------------------------------------------------------------
    | Remote Path Mapping
    |--------------------------------------------------------------------------
    |
    | If you are using a remote dev server, like Laravel Homestead, Docker, or
    | even a remote VPS, it will be necessary to specify your path mapping.
    |
    | Leaving one, or both of these, empty or null will not trigger the remote
    | URL changes and Ignition will treat your editor links as local files.
    |
    | "remote_sites_path" is an absolute base path for your sites or projects
    | in your machines, i.e. "/var/www".
    |
    | "local_sites_path" is an absolute base path for your sites or projects
    | in your local machine, i.e. "/Users/myuser/sites".
    |
    */

    'remote_sites_path' => env('IGNITION_REMOTE_SITES_PATH', ''),
    'local_sites_path' => env('IGNITION_LOCAL_SITES_PATH', ''),

    /*
    |--------------------------------------------------------------------------
    | Housekeeping Endpoint Prefix
    |--------------------------------------------------------------------------
    |
    | Ignition registers a couple of routes when it is enabled. To keep
    | your application clean, you can set a prefix for these routes.
    |
    */

    'housekeeping_endpoint_prefix' => '_ignition',

];

