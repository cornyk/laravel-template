<?php

use App\Utils\LogFormatterUtil;
use Monolog\Handler\RotatingFileHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that is utilized to write
    | messages to your logs. The value provided here should match one of
    | the channels present in the list of "channels" configured below.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Deprecations Log Channel
    |--------------------------------------------------------------------------
    |
    | This option controls the log channel that should be used to log warnings
    | regarding deprecated PHP and library features. This allows you to get
    | your application ready for upcoming major versions of dependencies.
    |
    */

    'deprecations' => [
        'channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),
        'trace' => env('LOG_DEPRECATIONS_TRACE', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Laravel
    | utilizes the Monolog PHP logging library, which includes a variety
    | of powerful log handlers and formatters that you're free to use.
    |
    | Available drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog", "custom", "stack"
    |
    */

    'channels' => [
        // 默认日志
        'stack' => [
            'driver' => 'stack',
            'channels' => ['app', 'error'],
        ],
        // 业务日志
        'app' => [
            'driver' => 'monolog',
            'handler' => RotatingFileHandler::class,
            'with' => [
                'filename' => storage_path('logs/app.log'),
                'maxFiles' => 10,
                'level' => 'debug',
                'filePermission' => 0777,
                'dateFormat' => 'Ymd',
            ],
            'formatter' => LogFormatterUtil::class,
        ],
        // 错误日志
        'error' => [
            'driver' => 'monolog',
            'handler' => RotatingFileHandler::class,
            'with' => [
                'filename' => storage_path('logs/error.log'),
                'maxFiles' => 10,
                'level' => 'error',
                'filePermission' => 0777,
                'dateFormat' => 'Ymd',
            ],
            'formatter' => LogFormatterUtil::class,
        ],
        // 请求日志
        'access' => [
            'driver' => 'monolog',
            'handler' => RotatingFileHandler::class,
            'with' => [
                'filename' => storage_path('logs/access.log'),
                'maxFiles' => 10,
                'level' => 'debug',
                'filePermission' => 0777,
                'dateFormat' => 'Ymd',
            ],
            'formatter' => LogFormatterUtil::class,
        ],
        // cli运行日志
        'cli' => [
            'driver' => 'monolog',
            'handler' => RotatingFileHandler::class,
            'with' => [
                'filename' => storage_path('logs/cli.log'),
                'maxFiles' => 10,
                'level' => 'debug',
                'filePermission' => 0777,
                'dateFormat' => 'Ymd',
            ],
            'formatter' => LogFormatterUtil::class,
        ],
        // SQL日志
        'sql' => [
            'driver' => 'monolog',
            'handler' => RotatingFileHandler::class,
            'with' => [
                'filename' => storage_path('logs/sql.log'),
                'maxFiles' => 10,
                'level' => 'debug',
                'filePermission' => 0777,
                'dateFormat' => 'Ymd',
            ],
            'formatter' => LogFormatterUtil::class,
        ],
        // SQL错误日志
        'sql_error' => [
            'driver' => 'monolog',
            'handler' => RotatingFileHandler::class,
            'with' => [
                'filename' => storage_path('logs/sql_err.log'),
                'maxFiles' => 10,
                'level' => 'debug',
                'filePermission' => 0777,
                'dateFormat' => 'Ymd',
            ],
            'formatter' => LogFormatterUtil::class,
        ],
        // 慢SQL日志
        'sql_slow' => [
            'driver' => 'monolog',
            'handler' => RotatingFileHandler::class,
            'with' => [
                'filename' => storage_path('logs/sql_slow.log'),
                'maxFiles' => 10,
                'level' => 'debug',
                'filePermission' => 0777,
                'dateFormat' => 'Ymd',
            ],
            'formatter' => LogFormatterUtil::class,
        ],
        // 发送请求日志
        'send_request' => [
            'driver' => 'monolog',
            'handler' => RotatingFileHandler::class,
            'with' => [
                'filename' => storage_path('logs/send_req.log'),
                'maxFiles' => 10,
                'level' => 'debug',
                'filePermission' => 0777,
                'dateFormat' => 'Ymd',
            ],
            'formatter' => LogFormatterUtil::class,
        ],
        // 发送请求错误日志
        'send_request_error' => [
            'driver' => 'monolog',
            'handler' => RotatingFileHandler::class,
            'with' => [
                'filename' => storage_path('logs/send_req_err.log'),
                'maxFiles' => 10,
                'level' => 'debug',
                'filePermission' => 0777,
                'dateFormat' => 'Ymd',
            ],
            'formatter' => LogFormatterUtil::class,
        ],
        // 任务运行日志
        'queue_run' => [
            'driver' => 'monolog',
            'handler' => RotatingFileHandler::class,
            'with' => [
                'filename' => storage_path('logs/queue_run.log'),
                'maxFiles' => 10,
                'level' => 'debug',
                'filePermission' => 0777,
                'dateFormat' => 'Ymd',
            ],
            'formatter' => LogFormatterUtil::class,
        ],
    ],
];
