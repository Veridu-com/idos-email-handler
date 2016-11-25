<?php

/*
 * Copyright (c) 2012-2016 Veridu Ltd <https://veridu.com>
 * All rights reserved.
 */

declare(strict_types = 1);

namespace App\Route;

use Interop\Container\ContainerInterface;
use Slim\App;

/**
 * E-mail routing definitions.
 *
 * @link docs/email/overview.md
 * @see App\Controller\Email
 */
class Email implements RouteInterface {
    /**
     * {@inheritdoc}
     */
    public static function getPublicNames() : array {
        return [
            'email:invitation',
            'email:otp',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function register(App $app) {
        $app->getContainer()[\App\Controller\Email::class] = function (ContainerInterface $container) {
            return new \App\Controller\Email(
                $container->get('commandBus'),
                $container->get('commandFactory')
            );
        };

        $container = $app->getContainer();

        self::invitation($app);
        self::otp($app);
    }

    /**
     * Sends user invitation e-mail.
     *
     * @param \Slim\App $app
     *
     * @return void
     */
    private static function invitation(App $app) {
        $app
            ->post(
                '/email/invitation',
                'App\Controller\Email:invitation'
            )
            ->setName('email:invitation');
    }

    /**
     * Sends user an OTP check Email.
     *
     * @param \Slim\App $app
     *
     * @return void
     */
    private static function otp(App $app) {
        $app
            ->post(
                '/email/otp',
                'App\Controller\Email:otp'
            )
            ->setName('email:otp');
    }
}
