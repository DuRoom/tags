<?php

/*
 * This file is part of DuRoom.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

use DuRoom\Api\Controller as DuRoomController;
use DuRoom\Api\Serializer\DiscussionSerializer;
use DuRoom\Api\Serializer\ForumSerializer;
use DuRoom\Discussion\Discussion;
use DuRoom\Discussion\Event\Saving;
use DuRoom\Discussion\Filter\DiscussionFilterer;
use DuRoom\Discussion\Search\DiscussionSearcher;
use DuRoom\Extend;
use DuRoom\Flags\Api\Controller\ListFlagsController;
use DuRoom\Http\RequestUtil;
use DuRoom\Tags\Access;
use DuRoom\Tags\Api\Controller;
use DuRoom\Tags\Api\Serializer\TagSerializer;
use DuRoom\Tags\Content;
use DuRoom\Tags\Event\DiscussionWasTagged;
use DuRoom\Tags\Filter\HideHiddenTagsFromAllDiscussionsPage;
use DuRoom\Tags\Filter\PostTagFilter;
use DuRoom\Tags\Listener;
use DuRoom\Tags\LoadForumTagsRelationship;
use DuRoom\Tags\Post\DiscussionTaggedPost;
use DuRoom\Tags\Query\TagFilterGambit;
use DuRoom\Tags\Tag;
use Psr\Http\Message\ServerRequestInterface;

$eagerLoadTagState = function ($query, ?ServerRequestInterface $request, array $relations) {
    if ($request && in_array('tags.state', $relations, true)) {
        $query->withStateFor(RequestUtil::getActor($request));
    }
};

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/less/forum.less')
        ->route('/t/{slug}', 'tag', Content\Tag::class)
        ->route('/tags', 'tags', Content\Tags::class),

    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js')
        ->css(__DIR__.'/less/admin.less'),

    (new Extend\Routes('api'))
        ->get('/tags', 'tags.index', Controller\ListTagsController::class)
        ->post('/tags', 'tags.create', Controller\CreateTagController::class)
        ->post('/tags/order', 'tags.order', Controller\OrderTagsController::class)
        ->get('/tags/{slug}', 'tags.show', Controller\ShowTagController::class)
        ->patch('/tags/{id}', 'tags.update', Controller\UpdateTagController::class)
        ->delete('/tags/{id}', 'tags.delete', Controller\DeleteTagController::class),

    (new Extend\Model(Discussion::class))
        ->belongsToMany('tags', Tag::class, 'discussion_tag'),

    (new Extend\ApiSerializer(ForumSerializer::class))
        ->hasMany('tags', TagSerializer::class)
        ->attribute('canBypassTagCounts', function (ForumSerializer $serializer) {
            return $serializer->getActor()->can('bypassTagCounts');
        }),

    (new Extend\ApiSerializer(DiscussionSerializer::class))
        ->hasMany('tags', TagSerializer::class)
        ->attribute('canTag', function (DiscussionSerializer $serializer, $model) {
            return $serializer->getActor()->can('tag', $model);
        }),

    (new Extend\ApiController(DuRoomController\ListPostsController::class))
        ->load('discussion.tags'),

    (new Extend\ApiController(ListFlagsController::class))
        ->load('post.discussion.tags'),

    (new Extend\ApiController(DuRoomController\ListDiscussionsController::class))
        ->addInclude(['tags', 'tags.state', 'tags.parent'])
        ->loadWhere('tags', $eagerLoadTagState),

    (new Extend\ApiController(DuRoomController\ShowDiscussionController::class))
        ->addInclude(['tags', 'tags.state', 'tags.parent'])
        ->loadWhere('tags', $eagerLoadTagState),

    (new Extend\ApiController(DuRoomController\CreateDiscussionController::class))
        ->addInclude(['tags', 'tags.state', 'tags.parent'])
        ->loadWhere('tags', $eagerLoadTagState),

    (new Extend\ApiController(DuRoomController\ShowForumController::class))
        ->addInclude(['tags', 'tags.parent'])
        ->prepareDataForSerialization(LoadForumTagsRelationship::class),

    (new Extend\Settings())
        ->serializeToForum('minPrimaryTags', 'duroom-tags.min_primary_tags')
        ->serializeToForum('maxPrimaryTags', 'duroom-tags.max_primary_tags')
        ->serializeToForum('minSecondaryTags', 'duroom-tags.min_secondary_tags')
        ->serializeToForum('maxSecondaryTags', 'duroom-tags.max_secondary_tags'),

    (new Extend\Policy())
        ->modelPolicy(Discussion::class, Access\DiscussionPolicy::class)
        ->modelPolicy(Tag::class, Access\TagPolicy::class)
        ->globalPolicy(Access\GlobalPolicy::class),

    (new Extend\ModelVisibility(Discussion::class))
        ->scopeAll(Access\ScopeDiscussionVisibilityForAbility::class),

    (new Extend\ModelVisibility(Tag::class))
        ->scope(Access\ScopeTagVisibility::class),

    new Extend\Locales(__DIR__.'/locale'),

    (new Extend\View)
        ->namespace('tags', __DIR__.'/views'),

    (new Extend\Post)
        ->type(DiscussionTaggedPost::class),

    (new Extend\Event())
        ->listen(Saving::class, Listener\SaveTagsToDatabase::class)
        ->listen(DiscussionWasTagged::class, Listener\CreatePostWhenTagsAreChanged::class)
        ->subscribe(Listener\UpdateTagMetadata::class),

    (new Extend\Filter(PostFilterer::class))
        ->addFilter(PostTagFilter::class),

    (new Extend\Filter(DiscussionFilterer::class))
        ->addFilter(TagFilterGambit::class)
        ->addFilterMutator(HideHiddenTagsFromAllDiscussionsPage::class),

    (new Extend\SimpleDuRoomSearch(DiscussionSearcher::class))
        ->addGambit(TagFilterGambit::class),
];
