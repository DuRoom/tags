<?php

/*
 * This file is part of DuRoom.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace DuRoom\Tags\Filter;

use DuRoom\Filter\FilterState;
use DuRoom\Query\QueryCriteria;
use DuRoom\Tags\Tag;

class HideHiddenTagsFromAllDiscussionsPage
{
    public function __invoke(FilterState $filter, QueryCriteria $queryCriteria)
    {
        if (count($filter->getActiveFilters()) > 0) {
            return;
        }

        $filter->getQuery()->whereNotIn('discussions.id', function ($query) {
            return $query->select('discussion_id')
            ->from('discussion_tag')
            ->whereIn('tag_id', Tag::where('is_hidden', 1)->pluck('id'));
        });
    }
}
