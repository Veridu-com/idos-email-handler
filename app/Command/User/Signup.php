<?php
/*
 * Copyright (c) 2012-2016 Veridu Ltd <https://veridu.com>
 * All rights reserved.
 */

declare(strict_types = 1);

namespace App\Command\User;

use App\Command\AbstractCommand;

/**
 * User "Signup" Command.
 */
class Signup extends AbstractCommand {
    /**
     * User Name.
     *
     * @var string
     */
    public $taskId;
    /**
     * User's object.
     *
     * @var object
     */
    public $user;

    /**
     * {@inheritdoc}
     */
    public function setParameters(array $parameters) : self {
        if (isset($parameters['task_id'])) {
            $this->taskId = $parameters['task_id'];
        }

        if (isset($parameters['user'])) {
            $this->user = $parameters['user'];
        }

        return $this;
    }
}
