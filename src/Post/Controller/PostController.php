<?php

declare(strict_types=1);

namespace App\Post\Controller;

use App\Post\Request\GetPostListRequest;
use App\Post\Service\PostsRetriever;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PostController
{

    /**
     * Get list of posts
     * @Route("/", name="get_list_of_posts", methods={"GET"})
     * @param GetPostListRequest $request
     * @param PostsRetriever $retriever
     * @return JsonResponse
     */
    public function getListOfPosts(
        GetPostListRequest $request,
        PostsRetriever $retriever
    ): JsonResponse {

        $posts = $retriever->getPostsWithComments(
            $request->getOffset(),
            $request->getLimit(),
            $request->getCommentsCount()
        );

        return new JsonResponse($posts);
    }
}
