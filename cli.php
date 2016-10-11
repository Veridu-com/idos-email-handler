#!/usr/bin/env php
<?php
/*
 * Copyright (c) 2012-2016 Veridu Ltd <https://veridu.com>
 * All rights reserved.
 */

/**
 * This worker is responsible for sending e-mails and communicating back to idOS when it was successful.
 */

require_once __DIR__ . '/vendor/autoload.php';

date_default_timezone_set('UTC');
setlocale(LC_ALL, 'en_US.UTF8');
mb_http_output('UTF-8');
mb_internal_encoding('UTF-8');

use Symfony\Component\Console\Application;

require __DIR__ . '/config/settings.php';
$settings = $appSettings['worker'];

$logger = new App\Worker\Logger($settings['log']);
$mailer = new App\Worker\Mailer($settings['mail']);


$application = new Application();
$application->add(new App\Worker\EmailDaemon($mailer, $logger, $settings));
$application->run();