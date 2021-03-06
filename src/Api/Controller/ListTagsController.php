<?php

/*
 * This file is part of DuRoom.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace DuRoom\Tags\Api\Controller;

use DuRoom\Api\Controller\AbstractListController;
use DuRoom\Http\RequestUtil;
use DuRoom\Tags\Api\Serializer\TagSerializer;
use DuRoom\Tags\TagRepository;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListTagsController extends AbstractListController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = TagSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $include = [
        'parent'
    ];

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = [
        'children',
        'lastPostedDiscussion',
        'state'
    ];

    /**
     * @var TagRepository
     */
    protected $tags;

    public function __construct(TagRepository $tags)
    {
        $this->tags = $tags;
    }

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = RequestUtil::getActor($request);
        $include = $this->extractInclude($request);

        if (in_array('lastPostedDiscussion', $include)) {
            $include = array_merge($include, ['lastPostedDiscussion.tags', 'lastPostedDiscussion.state']);
        }

        return $this->tags
            ->with($include, $actor)
            ->whereVisibleTo($actor)
            ->withStateFor($actor)
            ->get();
    }
}
