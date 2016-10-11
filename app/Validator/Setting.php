<?php

/*
 * Copyright (c) 2012-2016 Veridu Ltd <https://veridu.com>
 * All rights reserved.
 */

declare(strict_types=1);

namespace App\Validator;

use Respect\Validation\Validator;

/**
 * Setting Validation Rules.
 */
class Setting implements ValidatorInterface {
    /**
     * Asserts a valid name.
     *
     * @throws \Respect\Validation\Exceptions\ExceptionInterface
     *
     * @return void
     */
    public function assertPropName($value) {
        Validator::prnt()
            ->assert($value);
    }

    /**
     * Asserts a valid name.
     *
     * @throws \Respect\Validation\Exceptions\ExceptionInterface
     *
     * @return void
     */
    public function assertSectionName($value) {
        Validator::prnt()
            ->assert($value);
    }

    /**
     * Asserts a valid id, integer.
     *
     * @throws \Respect\Validation\Exceptions\ExceptionInterface
     *
     * @return void
     */
    public function assertId($id) {
        Validator::digit()
            ->assert($id);
    }

}
