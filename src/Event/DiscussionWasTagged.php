<?php

/*
 * This file is part of DuRoom.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace DuRoom\Tags\Event;

use DuRoom\Discussion\Discussion;
use DuRoom\User\User;

class DiscussionWasTagged
{
    /**
     * @var Discussion
     */
    public $discussion;

    /**
     * @var User
     */
    public $actor;

    /**
     * @var array
     */
    public $oldTags;

    /**
     * @param Discussion $discussion
     * @param User $actor
     * @param \DuRoom\Tags\Tag[] $oldTags
     */
    public function __construct(Discussion $discussion, User $actor, array $oldTags)
    {
        $this->discussion = $discussion;
        $this->actor = $actor;
        $this->oldTags = $oldTags;
    }
}
