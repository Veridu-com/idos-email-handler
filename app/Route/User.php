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
 * User routing definitions.
 *
 * @link docs/signup/overview.md
 * @see App\Controller\User
 */
class User implements RouteInterface {
    /**
     * {@inheritdoc}
     */
    public static function getPublicNames() : array {
        return [
            'user:signup',
            'user:invitation'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function register(App $app) {
        $app->getContainer()[\App\Controller\User::class] = function (ContainerInterface $container) {
            return new \App\Controller\User(
                $container->get('commandBus'),
                $container->get('commandFactory'),
                $container->get('optimus')
            );
        };

        $container            = $app->getContainer();
        $authMiddleware       = $container->get('authMiddleware');

        self::signup($app, $authMiddleware);
        self::invitation($app, $authMiddleware);
    }

    /**
     * Sends user signup e-mail.
     *
     * @param \Slim\App $app
     * @param \callable $auth
     *
     * @return void
     */
    private static function signup(App $app, callable $auth) {
        $app
            ->post(
                '/user/signup',
                'App\Controller\User:signup'
            )
           ->add($auth(Auth::BASIC))
            ->setName('user:signup');
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
                '/user/invitation',
                'App\Controller\User:invitation'
            )
           ->add($auth(Auth::BASIC))
            ->setName('user:invitation');
    }

}
