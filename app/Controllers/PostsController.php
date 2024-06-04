<?php

    namespace App\Controllers;

    use Yeager\Framework\Controller\BaseController;
    use Yeager\Framework\HTTP\Response;
    use App\Services\PostsService;

    class PostsController extends BaseController
    {
        private readonly PostsService $postsService;

        public function __construct(PostsService $postsService)
        {
            $this->postsService = $postsService;
        }

        public function index(): Response
        {
            $title = "Posts page";
            $posts = $this->postsService->getPosts();

            return $this->render("posts.html.twig", compact("posts", "title"));
        }

        public function show(int $id): Response
        {
            $content = "<h1>Post #{$id}</h1>";

            return new Response($content);
        }

        public function create(): Response
        {
            $title = "Create post page";

            return $this->render("create_post.html.twig", compact( "title"));
        }
    }