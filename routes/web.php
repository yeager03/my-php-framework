<?php

    use App\Controllers\HomeController;
    use App\Controllers\PostsController;
    use Yeager\Framework\Routing\Route;

    return [
        Route::get("/", [HomeController::class, "index"]),

        Route::get("/posts", [PostsController::class, "index"]),
        Route::get("/posts/{id:\d+}", [PostsController::class, "show"]),

        Route::get("/hello/{name}", function (string $name) {
            return new \Yeager\Framework\HTTP\Response("Hello, {$name}!");
        }),
    ];