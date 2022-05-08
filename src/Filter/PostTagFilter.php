<?php

/*
 * This file is part of DuRoom.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace DuRoom\Tags\Filter;

use DuRoom\Filter\FilterInterface;
use DuRoom\Filter\FilterState;

class PostTagFilter implements FilterInterface
{
    public function getFilterKey(): string
    {
        return 'tag';
    }

    public function filter(FilterState $filterState, string $filterValue, bool $negate)
    {
        $filterState->getQuery()
            ->join('discussion_tag', 'discussion_tag.discussion_id', '=', 'posts.discussion_id')
            ->where('discussion_tag.tag_id', $filterValue);
    }
}
