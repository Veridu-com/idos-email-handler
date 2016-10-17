<?php
/*
 * Copyright (c) 2012-2016 Veridu Ltd <https://veridu.com>
 * All rights reserved.
 */
declare(strict_types = 1);

namespace App\Worker;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as Monolog;
use Monolog\Processor\UidProcessor;
use Monolog\Processor\WebProcessor;

/**
 * Handles Thread-Safe logging.
 */
class Logger {
    /**
     * Monolog instance.
     *
     * @var \Monolog\Logger
     */
    public $logger;

    /**
     * Class constructor.
     *
     * @param string $stream
     * @param int    $level
     *
     * @return void
     */
    public function __construct(array $settings) {
        $this->logger = new Monolog('worker');
        $this->logger
            ->pushProcessor(new UidProcessor())
            ->pushProcessor(new WebProcessor())
            ->pushHandler(new StreamHandler($settings['path'], $settings['level']));
    }

    public function __call(string $name, array $arguments) {
        return call_user_func_array([$this->logger, $name], $arguments);
    }
}
