<?php

    namespace App\Controllers;

    use App\Services\PostsService;
    use Yeager\Framework\HTTP\Response;

    class PostsController
    {
        private readonly PostsService $postsService;

        public function __construct(PostsService $postsService)
        {
            $this->postsService = $postsService;
        }

        public function index(): Response
        {
            $content = json_encode($this->postsService->getPosts());

            return new Response($content);
        }

        public function show(int $id): Response
        {
            $content = "<h1>Post #{$id}</h1>";

            return new Response($content);
        }
    }