<?php

    namespace App\Controllers;

    use App\Entities\Post;
    use Yeager\Framework\Controller\BaseController;
    use Yeager\Framework\HTTP\RedirectResponse;
    use Yeager\Framework\HTTP\Response;
    use App\Services\PostsService;
    use Yeager\Framework\Session\ISession;
    use Yeager\Framework\Session\Session;

    class PostsController extends BaseController
    {
        private readonly PostsService $postsService;
        private readonly ISession $session;

        public function __construct(PostsService $postsService, ISession $session)
        {
            $this->postsService = $postsService;
            $this->session = $session;
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

            return $this->render("create-post.html.twig", compact("title"));
        }

        public function store(): Response
        {
            $post = Post::create(
                $this->request->input("title"),
                $this->request->input("description"),
            );

            $post = $this->postsService->save($post);

            $this->request->getSession()->setFlash("success", "Пост успешно создан!");

            return new RedirectResponse("/posts/{$post->getId()}");
        }
    }