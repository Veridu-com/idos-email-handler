<?php

/*
 * Copyright (c) 2012-2016 Veridu Ltd <https://veridu.com>
 * All rights reserved.
 */

declare(strict_types = 1);

namespace App\Listener\Email;

use App\Event\Email;
use App\Listener;
use Interop\Container\ContainerInterface;

class ListenerProvider extends Listener\AbstractListenerProvider {
    public function __construct(ContainerInterface $container) {
        $this->events = [
            Email\Created::class => [
                new Listener\LogFiredEventListener($container->get('log')('handler'))
            ]
        ];
    }
}
