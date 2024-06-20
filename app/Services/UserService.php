<?php

    namespace App\Services;

    use App\Entities\User;
    use DateTimeImmutable;
    use Yeager\Framework\Authentication\IAuthUser;
    use Yeager\Framework\Authentication\IUserService;
    use Yeager\Framework\Dbal\EntityService;

    class UserService implements IUserService
    {
        private EntityService $entityService;

        public function __construct(EntityService $entityService)
        {
            $this->entityService = $entityService;
        }

        public function save(User $user): User
        {
            $queryBuilder = $this->entityService->getConnection()->createQueryBuilder();

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

            $id = $this->entityService->save($user);

            $user->setId($id);

            return $user;
        }


        public function findByEmail(string $email): IAuthUser|null
        {
            $queryBuilder = $this->entityService->getConnection()->createQueryBuilder();

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