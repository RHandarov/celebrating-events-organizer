<?php

final class Router {
    private static array $PATHS = array(
        "GET" => [
            "login" => [\controllers\AuthController::class, "show_login_form"],
            "register" => [\controllers\AuthController::class, "show_register_form"],
            "logout" => [\controllers\AuthController::class, "log_out"]
        ],
        "POST" => [
            "login" => [\controllers\AuthController::class, "login"],
            "register" => [\controllers\AuthController::class, "register"]
        ]
    );

    public static function run(): void {
        $routes = self::$PATHS[$_SERVER["REQUEST_METHOD"]];
        $tokens = explode("/", $_SERVER["REQUEST_URI"]);
        $controller_and_method = $routes[$tokens[1]];
        $controller = new $controller_and_method[0]();
        $controller->{$controller_and_method[1]}();
    }

    private function __construct() {

    }

    private function __clone() {

    }
}