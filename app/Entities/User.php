<?php

    namespace App\Entities;

    use DateTimeImmutable;
    use Yeager\Framework\Authentication\IAuthUser;

    class User implements IAuthUser
    {
        private int|null $id;
        private string $email;
        private string $name;
        private string $password;
        private DateTimeImmutable|null $created_at;


        public function __construct(?int $id, string $email, string $name, string $password, ?DateTimeImmutable $created_at)
        {
            $this->id = $id;
            $this->email = $email;
            $this->name = $name;
            $this->password = $password;
            $this->created_at = $created_at;
        }

        public static function create(string $email, string $name, string $password, ?int $id = null, ?DateTimeImmutable $created_at = null): static
        {
            return new static($id, $email, $name, $password, $created_at ?? new DateTimeImmutable());
        }

        public function getId(): ?int
        {
            return $this->id;
        }

        public function setId(?int $id): void
        {
            $this->id = $id;
        }

        public function getEmail(): string
        {
            return $this->email;
        }

        public function setEmail(string $email): void
        {
            $this->email = $email;
        }

        public function getName(): string
        {
            return $this->name;
        }

        public function setName(string $name): void
        {
            $this->name = $name;
        }

        public function getPassword(): string
        {
            return $this->password;
        }

        public function setPassword(string $password): void
        {
            $this->password = $password;
        }

        public function getCreatedAt(): ?DateTimeImmutable
        {
            return $this->created_at;
        }

        public function setCreatedAt(?DateTimeImmutable $created_at): void
        {
            $this->created_at = $created_at;
        }
    }