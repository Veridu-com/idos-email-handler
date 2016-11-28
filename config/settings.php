<?php
/*
 * Copyright (c) 2012-2016 Veridu Ltd <https://veridu.com>
 * All rights reserved.
 */

declare(strict_types = 1);

use App\Helper\Env;

if (! defined('__VERSION__')) {
    define('__VERSION__', Env::asString('IDOS_VERSION', '1.0'));
}

$appSettings = [
    'debug'                             => Env::asBool('IDOS_DEBUG', false),
    'displayErrorDetails'               => Env::asBool('IDOS_DEBUG', false),
    'determineRouteBeforeAppMiddleware' => true,
    'log'                               => [
        'path' => Env::asString(
            'IDOS_LOG_FILE',
            sprintf(
                '%s/../log/email.log',
                __DIR__
            )
        ),
        'level' => Monolog\Logger::DEBUG
    ],
    'mail' => [
       'host'       => Env::asString('IDOS_EMAIL_HOST', '***REMOVED***'),
       'port'       => Env::asInteger('IDOS_EMAIL_PORT', 587),
       'username'   => Env::asString('IDOS_EMAIL_USER', '***REMOVED***'),
       'password'   => Env::asString('IDOS_EMAIL_PASS', ''),
       'encryption' => Env::asString('IDOS_EMAIL_ENCRYPTION', 'tls')
    ],
    'gearman' => [
        'timeout' => 1000,
        'servers' => Env::fromJson('IDOS_GEARMAN_SERVERS', [['localhost', 4730]])
    ],
    'path' => [
        'views' => __DIR__ . '/../resources/emails',
        'cache' => __DIR__ . '/../resources/emails/cache'
    ]
];
