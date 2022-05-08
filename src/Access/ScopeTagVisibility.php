<?php

/*
 * This file is part of DuRoom.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace DuRoom\Tags\Access;

use DuRoom\Tags\Tag;
use DuRoom\User\User;
use Illuminate\Database\Eloquent\Builder;

class ScopeTagVisibility
{
    /**
     * @param User $actor
     * @param Builder $query
     */
    public function __invoke(User $actor, Builder $query)
    {
        $query->whereIn('id', function ($query) use ($actor) {
            Tag::query()->setQuery($query->from('tags'))->whereHasPermission($actor, 'viewForum')->select('tags.id');
        });
    }
}
