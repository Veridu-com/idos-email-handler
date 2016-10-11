<?php

/*
 * Copyright (c) 2012-2016 Veridu Ltd <https://veridu.com>
 * All rights reserved.
 */

declare(strict_types=1);

namespace App\Listener\Company;

use App\Event\Company;
use App\Listener;
use Interop\Container\ContainerInterface;

class ListenerProvider extends Listener\AbstractListenerProvider {
    public function __construct(ContainerInterface $container) {
        $this->events = [
            Company\Created::class => [
                new Listener\LogFiredEventListener($container->get('log')('handler'))
            ]
        ];
    }

}
