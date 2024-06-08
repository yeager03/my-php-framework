<?php

    namespace App\Services;

    use App\Entities\Post;
    use DateTimeImmutable;
    use Doctrine\DBAL\Connection;
    use Yeager\Framework\HTTP\Exceptions\NotFoundException;

    class PostsService
    {

        private Connection $connection;

        public function __construct(Connection $connection)
        {
            $this->connection = $connection;
        }

        public function find_one(int $id): Post|null
        {
            $queryBuilder = $this->connection->createQueryBuilder();

            $result = $queryBuilder
                ->select("*")
                ->from("posts")
                ->where("id = :id")
                ->setParameter("id", $id)
                ->executeQuery()
                ->fetchAssociative();

            if (!$result) {
                return null;
            }

            return Post::create(
                title: $result["title"],
                description: $result["description"],
                id: $result["id"],
                created_at: new DateTimeImmutable($result["created_at"]),
            );
        }


        /**
         * @throws NotFoundException
         */
        public function findOrFail(int $id): Post
        {
            $post = $this->find_one($id);

            if(is_null($post)) {
                throw new NotFoundException("Post with id: {$id} not found");
            }

            return $post;
        }

        public function save(Post $post): Post
        {
            $queryBuilder = $this->connection->createQueryBuilder();

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

            $id = $this->connection->lastInsertId();

            $post->setId($id);

            return $post;
        }
    }