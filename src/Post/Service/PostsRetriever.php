<?php

declare(strict_types=1);

namespace App\Post\Service;

use App\Comment\Dto\Comment;
use App\Post\Entity\Post;
use App\Post\ExternalContracts\LastCommentsFinder;
use App\Post\ExternalContracts\PeopleFinder;
use App\Post\Repository\PostRepository;
use App\Post\Transformer\PostTransformer;

//need for rest
class PostsRetriever
{
    private PostRepository $postRepository;
    private LastCommentsFinder $commentsFinder;
    private PostTransformer $postTransformer;
    private PeopleFinder $peopleFinder;

    /**
     * PostsRetriever constructor.
     * @param PostRepository $postRepository
     * @param LastCommentsFinder $commentsFinder
     * @param PostTransformer $postTransformer
     * @param PeopleFinder $peopleFinder
     */
    public function __construct(
        PostRepository $postRepository,
        LastCommentsFinder $commentsFinder,
        PostTransformer $postTransformer,
        PeopleFinder $peopleFinder
    ) {
        $this->postRepository = $postRepository;
        $this->commentsFinder = $commentsFinder;
        $this->postTransformer = $postTransformer;
        $this->peopleFinder = $peopleFinder;
    }

    public function getPostsWithComments(int $offset, int $limit, int $commentsCount): array
    {
        $posts = $this->postRepository->getAllPosts($offset, $limit);
        $count = $this->postRepository->getAllPostsCount();
        $postIds = [];
        $userIds = [];
        foreach ($posts as $post) {
            $postIds[] = $post->getId()->toString();
            $userIds[] = $post->getOwnerId()->toString();
        }
        $comments = $this->commentsFinder->getLastComments($postIds, $commentsCount);

        foreach ($comments as $comment) {
            $userIds[] = $comment->getAuthorId();
        }

        $peopleMap = $this->getPeopleMapResult($userIds);
        $commentMap = $this->convertCommentsToMapResult($comments, $peopleMap);
        $result = $this->convertPostsToResult($posts, $commentMap, $peopleMap);

        return ['data' => $result, 'totalCount' => $count];
    }

    /**
     * @param array<int, Post> $posts
     * @param array $commentMap
     * @param array $peopleMap
     * @return array
     */
    private function convertPostsToResult(array $posts, array $commentMap, array $peopleMap): array
    {
        $result = [];
        foreach ($posts as $post) {
            $id = $post->getId()->toString();
            $ownerId = $post->getOwnerId()->toString();
            $buf = $this->postTransformer->transformPost($post);
            if (isset($commentMap[$id])) {
                $buf['lastComments'] = $commentMap;
            }
            if (isset($peopleMap[$ownerId])) {
                $buf['owner'] = $peopleMap[$ownerId];
                unset($buf['ownerId']);
            }

            $result[] = $buf;
        }

        return $result;
    }

    /**
     * @param array<int, Comment> $comments
     * @param array $peopleMap
     * @return array
     */
    private function convertCommentsToMapResult(array $comments, array $peopleMap): array
    {
        $map = [];
        foreach ($comments as $comment) {
            $authorId = $comment->getAuthorId();
            $postId = $comment->getPostId();
            $buf = $comment->jsonSerialize();
            if (isset($peopleMap[$authorId])) {
                $buf['author'] = $peopleMap[$authorId];
                unset($buf['authorId']);
            }
            if (!isset($map[$postId])) {
                $map[$postId] = [];
            }
            $map[$postId][] = $buf;
        }

        return $map;
    }

    private function getPeopleMapResult(array $userIds): array
    {
        $people = $this->peopleFinder->getPeople($userIds);
        $map = [];
        foreach ($people as $person) {
            $map[$person->getId()] = $person->jsonSerialize();
        }

        return $map;
    }
}
