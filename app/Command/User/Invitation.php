<?php
/*
 * Copyright (c) 2012-2016 Veridu Ltd <https://veridu.com>
 * All rights reserved.
 */

declare(strict_types = 1);

namespace App\Command\User;

use App\Command\AbstractCommand;

/**
 * User "Invitation" Command.
 */
class Invitation extends AbstractCommand {
    /**
     * User's object.
     *
     * @var object
     */
    public $user;
    /**
     * Signup hash.
     *
     * @var string
     */
    public $hash;
    /**
     * Target company name.
     *
     * @var string
     */
    public $companyName;
    /**
     * Target dashboard name.
     *
     * @var string
     */
    public $dashboardName;

    /**
     * {@inheritdoc}
     */
    public function setParameters(array $parameters) : self {
        if (isset($parameters['user'])) {
            $this->user = $parameters['user'];
        }

        if (isset($parameters['signupHash'])) {
            $this->signupHash = $parameters['signupHash'];
        }

        if (isset($parameters['companyName'])) {
            $this->companyName = $parameters['companyName'];
        }

        if (isset($parameters['dashboardName'])) {
            $this->dashboardName = $parameters['dashboardName'];
        }

        return $this;
    }
}
