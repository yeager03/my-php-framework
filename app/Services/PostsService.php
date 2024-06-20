<?php

    namespace App\Services;

    use App\Entities\Post;
    use DateTimeImmutable;
    use Yeager\Framework\Dbal\EntityService;
    use Yeager\Framework\HTTP\Exceptions\NotFoundException;

    class PostsService
    {
        private EntityService $entityService;

        public function __construct(EntityService $entityService)
        {
            $this->entityService = $entityService;
        }

        public function findOne(int $id): Post|null
        {
            $queryBuilder = $this->entityService->getConnection()->createQueryBuilder();

            $post = $queryBuilder
                ->select("*")
                ->from("posts")
                ->where("id = :id")
                ->setParameter("id", $id)
                ->executeQuery()
                ->fetchAssociative();

            if (!$post) {
                return null;
            }

            return Post::create(
                title: $post["title"],
                description: $post["description"],
                id: $post["id"],
                created_at: new DateTimeImmutable($post["created_at"]),
            );
        }


        /**
         * @throws NotFoundException
         */
        public function findOrFail(int $id): Post
        {
            $post = $this->findOne($id);

            if (is_null($post)) {
                throw new NotFoundException("Post with id: {$id} not found");
            }

            return $post;
        }

        public function save(Post $post): Post
        {
            $queryBuilder = $this->entityService->getConnection()->createQueryBuilder();

            $queryBuilder
                ->insert("posts")
                ->values([
                    "title" => ":title",
                    "description" => ":description",
                    "created_at" => ":created_at"
                ])
                ->setParameters([
                    "title" => $post->getTitle(),
                    "description" => $post->getDescription(),
                    "created_at" => $post->getCreatedAt()->format("Y-m-d H:i:s"),
                ])
                ->executeQuery();

            $id = $this->entityService->save($post);

            $post->setId($id);

            return $post;
        }
    }