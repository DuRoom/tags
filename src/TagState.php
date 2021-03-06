<?php

/*
 * This file is part of DuRoom.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace DuRoom\Tags;

use DuRoom\Database\AbstractModel;
use DuRoom\Foundation\EventGeneratorTrait;
use DuRoom\User\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $user_id
 * @property int $tag_id
 * @property \Carbon\Carbon|null $marked_as_read_at
 * @property bool $is_hidden
 * @property Tag $tag
 * @property User $user
 */
class TagState extends AbstractModel
{
    use EventGeneratorTrait;

    /**
     * {@inheritdoc}
     */
    protected $table = 'tag_user';

    /**
     * {@inheritdoc}
     */
    protected $dates = ['marked_as_read_at'];

    /**
     * Define the relationship with the tag that this state is for.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

    /**
     * Define the relationship with the user that this state is for.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Set the keys for a save update query.
     *
     * @param Builder $query
     * @return Builder
     */
    protected function setKeysForSaveQuery($query)
    {
        $query->where('tag_id', $this->tag_id)
              ->where('user_id', $this->user_id);

        return $query;
    }
}
