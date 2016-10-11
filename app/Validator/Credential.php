<?php

/*
 * Copyright (c) 2012-2016 Veridu Ltd <https://veridu.com>
 * All rights reserved.
 */

declare(strict_types=1);

namespace App\Validator;

use Respect\Validation\Validator;

/**
 * Credential Validation Rules.
 */
class Credential implements ValidatorInterface {
    /**
     * Asserts a valid name, 1-15 chars long.
     *
     * @throws \Respect\Validation\Exceptions\ExceptionInterface
     *
     * @return void
     */
    public function assertName($name) {
        Validator::prnt()
            ->length(1, 15)
            ->assert($name);
    }

    /**
     * Asserts a valid slug, 1-15 chars long.
     *
     * @throws \Respect\Validation\Exceptions\ExceptionInterface
     *
     * @return void
     */
    public function assertSlug($slug) {
        Validator::slug()
            ->length(1, 15)
            ->assert($slug);
    }

    /**
     * Asserts a valid production flag, boolean.
     *
     * @throws \Respect\Validation\Exceptions\ExceptionInterface
     *
     * @return void
     */
    public function assertProduction($production) {
        Validator::boolVal()
            ->assert($production);
    }

    /**
     * Asserts a valid company id, integer.
     *
     * @throws \Respect\Validation\Exceptions\ExceptionInterface
     *
     * @return void
     */
    public function assertCompanyId($id) {
        Validator::digit()
            ->assert($id);
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

    /**
     * Validates a production flag value.
     *
     * @return bool
     */
    public function productionValue($production) : bool {
        return Validator::trueVal()
            ->validate($production);
    }
}
