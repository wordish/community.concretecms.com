<?php

return [
    'debug' => [
        'detail' => 'message'
    ],

    'log' => [
        'errors' => true,
        'configuration' => [
            'advanced' => [
                'configuration' => [
                    "formatters" => [
                        "logstash" => [
                            "class" => \Monolog\Formatter\LogstashFormatter::class,
                            "applicationName" => implode('-', [
                                getenv('LAGOON_PROJECT') ?: 'project_unset',
                                getenv('LAGOON_GIT_SAFE_BRANCH') ?? 'safe_branch_unset',
                            ]),
                            "version" => 1,
                        ]
                    ],
                    "handlers" => [
                        "lagoon" => [
                            "class" => \Monolog\Handler\SocketHandler::class,
                            "level" => "info",
                            "formatter" => "logstash",
                            "connectionString" => "udp://application-logs.lagoon.svc:5140",
                        ],
                    ],
                    "loggers" => [
                        "all" => [
                            "handlers" => [
                                "lagoon",
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
