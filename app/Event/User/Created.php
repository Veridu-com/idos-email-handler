<?php

/*
 * Copyright (c) 2012-2016 Veridu Ltd <https://veridu.com>
 * All rights reserved.
 */

declare(strict_types=1);

namespace App\Event\User;

use App\Event\AbstractEvent;

/**
 * Signup event.
 */
class Signup extends AbstractEvent {
    public $user;

    public function __construct(array $user) {
        $this->user = $user;
    }
}
