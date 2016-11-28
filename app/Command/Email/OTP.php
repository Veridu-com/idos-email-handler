<?php
/*
 * Copyright (c) 2012-2016 Veridu Ltd <https://veridu.com>
 * All rights reserved.
 */

declare(strict_types = 1);

namespace App\Command\Email;

use App\Command\AbstractCommand;

/**
 * Email "OTP" Command.
 */
class OTP extends AbstractCommand {
    /**
     * The email address to send.
     *
     * @var string
     */
    public $email;
    /**
     * The OTP password.
     *
     * @var int
     */
    public $password;
    /**
     * The OTP company.
     *
     * @var \stdClass
     */
    public $company;
    /**
     * The Credential public key.
     *
     * @var string
     */
    public $credentialPubKey;
    /**
     * The source id.
     *
     * @var string
     */
    public $sourceId;
    /**
     * The process id.
     *
     * @var string
     */
    public $processId;
    /**
     * The user's username.
     *
     * @var string
     */
    public $username;

    /**
     * {@inheritdoc}
     */
    public function setParameters(array $parameters) : self {
        if (isset($parameters['userName'])) {
            $this->username = $parameters['userName'];
        }

        if (isset($parameters['company'])) {
            $this->company = $parameters['company'];
        }

        if (isset($parameters['processId'])) {
            $this->processId = $parameters['processId'];
        }

        if (isset($parameters['sourceId'])) {
            $this->sourceId = $parameters['sourceId'];
        }

        if (isset($parameters['target'])) {
            $this->email = $parameters['target'];
        }

        if (isset($parameters['publicKey'])) {
            $this->credentialPubKey = $parameters['publicKey'];
        }

        if (isset($parameters['password'])) {
            $this->password = $parameters['password'];
        }

        return $this;
    }
}
