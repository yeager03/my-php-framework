<?php

    namespace App\Services;

    class PostsService
    {
        public function getPosts(): array
        {
            return [
                [
                    "id" => 1,
                    "title" => "New York is biggest city",
                    "description" => "Description...",
                    "author" => "Alex Mercer"
                ]
            ];
        }
    }