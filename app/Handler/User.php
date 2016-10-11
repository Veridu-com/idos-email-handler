<?php

/*
 * Copyright (c) 2012-2016 Veridu Ltd <https://veridu.com>
 * All rights reserved.
 */

declare(strict_types=1);

namespace App\Handler;

use App\Command\AbstractCommand;
use App\Command\User as UserCommand;
use Interop\Container\ContainerInterface;
use Respect\Validation\Validator;

/**
 * Handles User commands.
 */
class User implements HandlerInterface {
    /**
     * {@inheritdoc}
     */
    public static function register(ContainerInterface $container) {
        $container[self::class] = function (ContainerInterface $container) {
            return new \App\Handler\User();
        };
    }

    /**
     * Class constructor.
     *
     * @param App\Repository\UserInterface
     * @param App\Validator\User
     *
     * @return void
     */
    public function __construct() {
    }

    /**
     * Sends User signup e-mail.
     *
     * @param App\Command\User\Signup $command
     *
     * @return App\Entity\User
     */
    public function handleSignup(UserCommand\Signup $command) : bool {
        $this->validate($command);
        $user = $command->user;

        $emailData = [
            'templatePath'  => 'user.signup',
            'task_id'       => $command->taskId,
            'subject'       => sprintf('Welcome %s', $user['name']),
            'from'          => '***REMOVED***',
            'to'            => $user['email'],
            'variables'     => $user,
            'bodyType'      => 'text/html'
        ];

        $client = new \GearmanClient();
        $client->addServer('localhost');
        $queued = $client->doBackground('send_email', json_encode($emailData));

        return (bool) $queued;
    }

    /**
     * Sends User invitation e-mail.
     *
     * @param App\Command\User\Invitation $command
     *
     * @return App\Entity\User
     */
    public function handleInvitation(UserCommand\Invitation $command) : bool {
        $this->validate($command);

        $data                  = $user                  = $command->user;
        $data['signupHash']    = $command->signupHash;
        $data['companyName']   = $command->companyName;
        $data['dashboardName'] = $command->dashboardName;

        $emailData = [
            'templatePath'  => 'user.invitation',
            'subject'       => sprintf('idOS Dashboard Invitation'),
            'from'          => '***REMOVED***',
            'to'            => $user['email'],
            'variables'     => $data,
            'bodyType'      => 'text/html'
        ];

        $client = new \GearmanClient();
        $client->addServer('localhost');
        $queued = $client->doBackground('send_email', json_encode($emailData));

        return (bool) $queued;
    }

    /**
     * UserCommand\Signup validator.
     *
     * @param UserCommand\Signup $command The command
     */
    private function validate(AbstractCommand $command) {
        Validator::attribute(
            'user',
            Validator::arrayType()
                ->key('name', Validator::stringType())
                ->key('email', Validator::email())
            )->assert($command);
    }
}
