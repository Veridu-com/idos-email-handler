<?php

/*
 * Copyright (c) 2012-2016 Veridu Ltd <https://veridu.com>
 * All rights reserved.
 */

declare(strict_types=1);

namespace App\Validator;

use Respect\Validation\Validator;

/**
 * Company Validation Rules.
 */
class Company implements ValidatorInterface {
    /**
     * Asserts a valid company id, digit.
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
     * Asserts a valid parent id, digit or null.
     *
     * @throws \Respect\Validation\Exceptions\ExceptionInterface
     *
     * @return void
     */
    public function assertParentId($parentId) {
        Validator::oneOf(
            Validator::nullType(),
            Validator::digit()
        )->assert($parentId);
    }
}
