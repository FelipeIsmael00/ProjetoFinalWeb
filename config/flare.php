<?php

return [
    'key' => env('FLARE_KEY'),

    'flare_middleware' => [
        \Spatie\LaravelIgnition\FlareMiddleware\AddQueries::class => [
            'maximum_number_of_collected_queries' => 200,
            'report_query_bindings' => true,
        ],
        \Spatie\LaravelIgnition\FlareMiddleware\AddDumps::class => [],
        \Spatie\LaravelIgnition\FlareMiddleware\AddLogs::class => [
            'maximum_number_of_collected_logs' => 200,
        ],
        \Spatie\LaravelIgnition\FlareMiddleware\AddJobs::class => [
            'max_chained_job_reporting_depth' => 5,
        ],
        \Spatie\LaravelIgnition\FlareMiddleware\AddContext::class => [],
        \Spatie\LaravelIgnition\FlareMiddleware\AddExceptionInformation::class => [],
        \Spatie\LaravelIgnition\FlareMiddleware\AddNotifierInformation::class => [],
    ],

    'send_logs_as_events' => true,
];

