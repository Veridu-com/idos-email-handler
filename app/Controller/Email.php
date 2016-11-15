<?php

/*
 * Copyright (c) 2012-2016 Veridu Ltd <https://veridu.com>
 * All rights reserved.
 */

declare(strict_types = 1);

namespace App\Controller;

use App\Factory\Command;
use League\Tactician\CommandBus;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Handles requests to /email.
 */
class Email implements ControllerInterface {
    /**
     * Command Bus instance.
     *
     * @var \League\Tactician\CommandBus
     */
    private $commandBus;
    /**
     * Command Factory instance.
     *
     * @var App\Factory\Command
     */
    private $commandFactory;

    /**
     * Class constructor.
     *
     * @param \League\Tactician\CommandBus $commandBus
     * @param App\Factory\Command          $commandFactory
     *
     * @return void
     */
    public function __construct(
        CommandBus $commandBus,
        Command $commandFactory
    ) {
        $this->commandBus     = $commandBus;
        $this->commandFactory = $commandFactory;
    }

    /**
     * Sends user Invitation e-mail.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function invitation(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface {
        $command = $this->commandFactory->create('Email\\Invitation');
        $command->setParameters($request->getParsedBody());
        $success = $this->commandBus->handle($command);

        $body = [
            'status' => $success
        ];
        $statusCode = $success ? 200 : 500;

        $command = $this->commandFactory->create('ResponseDispatch');
        $command
            ->setParameter('request', $request)
            ->setParameter('response', $response)
            ->setParameter('statusCode', $statusCode)
            ->setParameter('body', $body);

        return $this->commandBus->handle($command);
    }

    /**
     * Sends user an OTP Check e-mail.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function otp(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface {
        $command = $this->commandFactory->create('Email\\OTP');
        $command->setParameters($request->getParsedBody());
        $success = $this->commandBus->handle($command);

        $body = [
            'status' => $success
        ];
        $statusCode = $success ? 200 : 500;

        $command = $this->commandFactory->create('ResponseDispatch');
        $command
            ->setParameter('request', $request)
            ->setParameter('response', $response)
            ->setParameter('statusCode', $statusCode)
            ->setParameter('body', $body);

        return $this->commandBus->handle($command);
    }
}
