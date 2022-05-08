<?php

/*
 * This file is part of DuRoom.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace DuRoom\Tags\Access;

use DuRoom\Tags\Tag;
use DuRoom\User\Access\AbstractPolicy;
use DuRoom\User\User;

class TagPolicy extends AbstractPolicy
{
    public function can(User $actor, string $ability, Tag $tag)
    {
        if ($tag->parent_id !== null && ! $actor->can($ability, $tag->parent)) {
            return $this->deny();
        }

        if ($tag->is_restricted) {
            $id = $tag->id;

            return $actor->hasPermission("tag$id.$ability");
        }
    }

    public function addToDiscussion(User $actor, Tag $tag)
    {
        return $actor->can('startDiscussion', $tag);
    }
}
