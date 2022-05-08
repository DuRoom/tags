<?php

/*
 * This file is part of DuRoom.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace DuRoom\Tags\Event;

use DuRoom\Tags\Tag;
use DuRoom\User\User;

class Deleting
{
    /**
     * @var Tag
     */
    public $tag;

    /**
     * @var User
     */
    public $actor;

    /**
     * @param Tag $tag
     * @param User $actor
     */
    public function __construct(Tag $tag, User $actor)
    {
        $this->tag = $tag;
        $this->actor = $actor;
    }
}
