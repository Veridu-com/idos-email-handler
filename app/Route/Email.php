<?php

/*
 * Copyright (c) 2012-2016 Veridu Ltd <https://veridu.com>
 * All rights reserved.
 */

declare(strict_types = 1);

namespace App\Route;

use App\Middleware\Auth;
use Interop\Container\ContainerInterface;
use Slim\App;

/**
 * E-mail routing definitions.
 *
 * @link docs/signup/overview.md
 * @see App\Controller\Email
 */
class Email implements RouteInterface {
    /**
     * {@inheritdoc}
     */
    public static function getPublicNames() : array {
        return [
            'email:invitation'
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

        $container      = $app->getContainer();
        $authMiddleware = $container->get('authMiddleware');

        self::signup($app, $authMiddleware);
        self::invitation($app, $authMiddleware);
    }

    /**
     * Sends user invitation e-mail.
     *
     * @param \Slim\App $app
     * @param \callable $auth
     *
     * @return void
     */
    private static function invitation(App $app, callable $auth) {
        $app
            ->post(
                '/email/invitation',
                'App\Controller\Email:invitation'
            )
            ->add($auth(Auth::BASIC))
            ->setName('email:invitation');
    }
}
