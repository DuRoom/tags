<?php

/*
 * This file is part of DuRoom.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace DuRoom\Tags\Command;

use DuRoom\Tags\Event\Deleting;
use DuRoom\Tags\TagRepository;

class DeleteTagHandler
{
    /**
     * @var TagRepository
     */
    protected $tags;

    /**
     * @param TagRepository $tags
     */
    public function __construct(TagRepository $tags)
    {
        $this->tags = $tags;
    }

    /**
     * @param DeleteTag $command
     * @return \DuRoom\Tags\Tag
     * @throws \DuRoom\User\Exception\PermissionDeniedException
     */
    public function handle(DeleteTag $command)
    {
        $actor = $command->actor;

        $tag = $this->tags->findOrFail($command->tagId, $actor);

        $actor->assertCan('delete', $tag);

        event(new Deleting($tag, $actor));

        $tag->delete();

        return $tag;
    }
}
