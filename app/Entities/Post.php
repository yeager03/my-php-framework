<?php

    namespace App\Entities;

    use DateTimeImmutable;
    use Yeager\Framework\Dbal\Entity;

    class Post extends Entity
    {
        private int|null $id;
        private string $title;
        private string $description;
        private DateTimeImmutable|null $created_at;


        public function __construct(?int $id, string $title, string $description, ?DateTimeImmutable $created_at)
        {
            $this->id = $id;
            $this->title = $title;
            $this->description = $description;
            $this->created_at = $created_at;
        }

        public static function create(string $title, string $description, ?int $id = null, ?DateTimeImmutable $created_at = null): static
        {
            return new static($id, $title, $description, $created_at ?? new DateTimeImmutable());
        }

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getTitle(): string
        {
            return $this->title;
        }

        public function getDescription(): string
        {
            return $this->description;
        }

        public function getCreatedAt(): ?DateTimeImmutable
        {
            return $this->created_at;
        }

        public function setId(?int $id): void
        {
            $this->id = $id;
        }

        public function setTitle(string $title): void
        {
            $this->title = $title;
        }

        public function setDescription(string $description): void
        {
            $this->description = $description;
        }

        public function setCreatedAt(?DateTimeImmutable $created_at): void
        {
            $this->created_at = $created_at;
        }
    }