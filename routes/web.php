<?php

    use App\Controllers\CabinetController;
    use App\Controllers\HomeController;
    use App\Controllers\PostsController;
    use App\Controllers\SignInController;
    use App\Controllers\SignUpController;
    use Yeager\Framework\HTTP\Middleware\Guest;
    use Yeager\Framework\Routing\Route;
    use Yeager\Framework\HTTP\Middleware\Authenticate;

    return [
        Route::get("/", [HomeController::class, "index"]),

        Route::get("/posts", [PostsController::class, "index"]),
        Route::post("/posts", [PostsController::class, "store"]),
        Route::get("/posts/{id:\d+}", [PostsController::class, "show"]),
        Route::get("/posts/create", [PostsController::class, "create"]),

        Route::get("/sign-up", [SignUpController::class, "index"], [Guest::class]),
        Route::post("/sign-up", [SignUpController::class, "store"]),

        Route::get("/sign-in", [SignInController::class, "index"], [Guest::class]),
        Route::post("/sign-in", [SignInController::class, "store"]),
        Route::post("/logout", [SignInController::class, "logout"]),

        Route::get("/cabinet", [CabinetController::class, "index"], [Authenticate::class]),

        Route::get("/hello/{name}", function (string $name) {
            return new \Yeager\Framework\HTTP\Response("Hello, {$name}!");
        }),
    ];