<?php

    namespace App\Services;

    use App\Entities\User;
    use DateTimeImmutable;
    use Doctrine\DBAL\Connection;
    use Yeager\Framework\Authentication\IAuthUser;
    use Yeager\Framework\Authentication\IUserService;

    class UserService implements IUserService
    {
        private Connection $connection;

        public function __construct(Connection $connection)
        {
            $this->connection = $connection;
        }

        public function save(User $user): User
        {
            $queryBuilder = $this->connection->createQueryBuilder();

            $queryBuilder
                ->insert("users")
                ->values([
                    "email" => ":email",
                    "name" => ":name",
                    "password" => ":password",
                    "created_at" => ":created_at"
                ])
                ->setParameters([
                    "email" => $user->getEmail(),
                    "name" => $user->getName(),
                    "password" => $user->getPassword(),
                    "created_at" => $user->getCreatedAt()->format("Y-m-d H:i:s"),
                ])
                ->executeQuery();

            $id = $this->connection->lastInsertId();

            $user->setId($id);

            return $user;
        }


        public function findByEmail(string $email): IAuthUser|null
        {
            $queryBuilder = $this->connection->createQueryBuilder();

            $user = $queryBuilder
                ->select("*")
                ->from("users")
                ->where("email = :email")
                ->setParameter("email", $email)
                ->executeQuery()
                ->fetchAssociative();


            if (!$user) {
                return null;
            }

            return User::create(
                email: $user["email"],
                name: $user["name"],
                password: $user["password"],
                id: $user["id"],
                created_at: new DateTimeImmutable($user["created_at"]),
            );
        }
    }