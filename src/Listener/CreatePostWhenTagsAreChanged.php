<?php

/*
 * This file is part of DuRoom.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace DuRoom\Tags\Listener;

use DuRoom\Tags\Event\DiscussionWasTagged;
use DuRoom\Tags\Post\DiscussionTaggedPost;
use Illuminate\Support\Arr;

class CreatePostWhenTagsAreChanged
{
    /**
     * @param DiscussionWasTagged $event
     * @return void
     */
    public function handle(DiscussionWasTagged $event)
    {
        $post = DiscussionTaggedPost::reply(
            $event->discussion->id,
            $event->actor->id,
            Arr::pluck($event->oldTags, 'id'),
            $event->discussion->tags()->pluck('id')->all()
        );

        $event->discussion->mergePost($post);
    }
}
