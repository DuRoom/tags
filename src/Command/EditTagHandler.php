<?php

/*
 * This file is part of DuRoom.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace DuRoom\Tags\Command;

use DuRoom\Tags\Event\Saving;
use DuRoom\Tags\TagRepository;
use DuRoom\Tags\TagValidator;
use Illuminate\Support\Arr;

class EditTagHandler
{
    /**
     * @var TagRepository
     */
    protected $tags;

    /**
     * @var TagValidator
     */
    protected $validator;

    /**
     * @param TagRepository $tags
     * @param TagValidator $validator
     */
    public function __construct(TagRepository $tags, TagValidator $validator)
    {
        $this->tags = $tags;
        $this->validator = $validator;
    }

    /**
     * @param EditTag $command
     * @return \DuRoom\Tags\Tag
     * @throws \DuRoom\User\Exception\PermissionDeniedException
     */
    public function handle(EditTag $command)
    {
        $actor = $command->actor;
        $data = $command->data;

        $tag = $this->tags->findOrFail($command->tagId, $actor);

        $actor->assertCan('edit', $tag);

        $attributes = Arr::get($data, 'attributes', []);

        if (isset($attributes['name'])) {
            $tag->name = $attributes['name'];
        }

        if (isset($attributes['slug'])) {
            $tag->slug = $attributes['slug'];
        }

        if (isset($attributes['description'])) {
            $tag->description = $attributes['description'];
        }

        if (isset($attributes['color'])) {
            $tag->color = $attributes['color'];
        }

        if (isset($attributes['icon'])) {
            $tag->icon = $attributes['icon'];
        }

        if (isset($attributes['isHidden'])) {
            $tag->is_hidden = (bool) $attributes['isHidden'];
        }

        if (isset($attributes['isRestricted'])) {
            $tag->is_restricted = (bool) $attributes['isRestricted'];
        }

        event(new Saving($tag, $actor, $data));

        $this->validator->assertValid($tag->getDirty());

        $tag->save();

        return $tag;
    }
}
