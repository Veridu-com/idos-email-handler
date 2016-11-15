<?php

/*
 * Copyright (c) 2012-2016 Veridu Ltd <https://veridu.com>
 * All rights reserved.
 */

declare(strict_types = 1);

namespace App\Handler;

use App\Command\AbstractCommand;
use App\Command\Email\Invitation;
use App\Command\Email\OTP;
use Interop\Container\ContainerInterface;
use Respect\Validation\Validator;

/**
 * Handles Email commands.
 */
class Email implements HandlerInterface {
    /**
     * Gearman Client instance.
     *
     * @var \GearmanClient
     */
    protected $gearman;

    /**
     * {@inheritdoc}
     */
    public static function register(ContainerInterface $container) {
        $container[self::class] = function (ContainerInterface $container) {
            return new \App\Handler\Email(
                $container
                    ->get('gearmanClient')
            );
        };
    }

    /**
     * Class constructor.
     *
     * @param \GearmanClient $gearmanClient
     *
     * @return void
     */
    public function __construct(
        \GearmanClient $gearmanClient
    ) {
        $this->gearman = $gearmanClient;
    }

    /**
     * Sends Email invitation e-mail.
     *
     * @param App\Command\Email\Invitation $command
     *
     * @return bool
     */
    public function handleInvitation(Invitation $command) : bool {
        $this->validate($command);

        $emailData = [
            'templatePath'  => 'user.invitation',
            'subject'       => sprintf('%s Invitation', $command->dashboardName),
            'from'          => '***REMOVED***',
            'to'            => $command->user['email'],
            'variables'     => [
                'email'         => $command->user['email'],
                'name'          => $command->user['name'],
                'invitation'    => $command->invitation,
                'signupHash'    => $command->signupHash,
                'companyName'   => $command->companyName,
                'dashboardName' => $command->dashboardName
            ],
            'bodyType'      => 'text/html'
        ];

        // Job Scheduling
        $task = $this->gearman->doBackground(
            'send_email',
            json_encode($emailData)
        );
        if ($this->gearman->returnCode() === \GEARMAN_SUCCESS) {
            // $this->emitter->emit(new JobScheduled($command, $task));

            return true;
        }

        // $this->emitter->emit(new ScheduleFailed($command, $this->gearman->error()));

        return false;
    }

    /**
     * Sends OTP Email.
     *
     * @param \App\Command\Email\OTP $command
     *
     * @return bool
     */
    public function handleOTP(OTP $command) : bool {
        $emailData = [
            'templatePath'  => 'user.otp',
            'subject'       => 'Email address verification',
            'from'          => '***REMOVED***',
            'to'            => $command->email,
            'variables'     => [
                'password' => $command->password,
                'company'  => $command->company
            ],
            'bodyType'      => 'text/html'
        ];

        // Job Scheduling
        $task = $this->gearman->doBackground(
            'send_email',
            json_encode($emailData)
        );
        if ($this->gearman->returnCode() === \GEARMAN_SUCCESS) {
            // $this->emitter->emit(new JobScheduled($command, $task));

            return true;
        }

        // $this->emitter->emit(new ScheduleFailed($command, $this->gearman->error()));

        return false;
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
