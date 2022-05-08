<?php

/*
 * This file is part of DuRoom.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace DuRoom\Tags;

use DuRoom\Foundation\AbstractValidator;

class TagValidator extends AbstractValidator
{
    /**
     * {@inheritdoc}
     */
    protected $rules = [
        'name' => ['required'],
        'slug' => ['required', 'unique:tags', 'regex:/^[^\/\\ ]*$/i'],
        'is_hidden' => ['bool'],
        'description' => ['string', 'max:700'],
        'color' => ['regex:/^#([a-f0-9]{6}|[a-f0-9]{3})$/i'],
    ];
}
