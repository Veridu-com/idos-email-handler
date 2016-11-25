<?php
/*
 * Copyright (c) 2012-2016 Veridu Ltd <https://veridu.com>
 * All rights reserved.
 */
declare(strict_types = 1);

namespace Cli\Utils;

use Swift_Mailer;
use Swift_SmtpTransport;

/**
 * Command definition for Process-based Daemon.
 */
class Mailer {
    public $mailer;

    public function __construct(array $settings) {
        $transport = Swift_SmtpTransport::newInstance($settings['host'], $settings['port'])
            ->setUsername($settings['username'])
            ->setEncryption($settings['encryption'])
            ->setPassword($settings['password']);

        $this->mailer = Swift_Mailer::newInstance($transport);
    }

    public function __call(string $name, array $arguments) {
        return call_user_func_array([$this->mailer, $name], $arguments);
    }
}
