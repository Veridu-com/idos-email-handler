<?php
/*
 * Copyright (c) 2012-2016 Veridu Ltd <https://veridu.com>
 * All rights reserved.
 */

declare(strict_types = 1);

namespace App\Command\Email;

use App\Command\AbstractCommand;

/**
 * Email "Invitation" Command.
 */
class Invitation extends AbstractCommand {
    /**
     * User's object.
     *
     * @var object
     */
    public $user;
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
     * Signup hash.
     *
     * @var string
     */
    public $signupHash;

    /**
     * The invitation object.
     *
     * @var object
     */
    public $invitation;

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

        if (isset($parameters['invitation'])) {
            $this->invitation = $parameters['invitation'];
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
