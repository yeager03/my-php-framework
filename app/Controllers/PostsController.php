<?php

    namespace App\Controllers;

    use App\Entities\Post;
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
            $posts = [];

            return $this->render("posts.html.twig", compact("posts", "title"));
        }

        public function show(int $id): Response
        {
            $post = $this->postsService->findOrFail($id);

            $title = "Post {$post->getId()}";

            return $this->render("post.html.twig", compact("title", "post"));
        }

        public function create(): Response
        {
            $title = "Create post page";

            return $this->render("create_post.html.twig", compact("title"));
        }

        public function store(): Response
        {
            $post = Post::create(
                $this->request->POST["title"],
                $this->request->POST["description"],
            );

            $post = $this->postsService->save($post);

            dd($post);
        }
    }