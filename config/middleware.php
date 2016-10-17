<?php
/*
 * Copyright (c) 2012-2016 Veridu Ltd <https://veridu.com>
 * All rights reserved.
 */

use App\Middleware\Debugger;
use App\Middleware\Watcher;
use Slim\HttpCache\Cache;
use Slim\Middleware\HttpBasicAuthentication;

if (! isset($app)) {
    die('$app is not set!');
}

$app
    ->add(
        new HttpBasicAuthentication(
            [
                'users' => [
                    __AUTHUSER__ => __AUTHPASS__
                ],
                'secure' => false
            ]
        )
    );