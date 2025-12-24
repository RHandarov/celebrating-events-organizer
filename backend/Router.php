<?php

final class Router {
    private static array $PATHS = array(
        "GET" => [
            "login" => [\controllers\AuthController::class, "show_login_form"]
        ],
        "POST" => [
            "login" => [\controllers\AuthController::class, "login"]
        ]
    );

    public static function run(): void {
        $routes = self::$PATHS[$_SERVER["REQUEST_METHOD"]];
        $tokens = explode("/", $_SERVER["REQUEST_URI"]);
        $controller = new $routes[$tokens[1]][0]();
        $controller->{$routes[$tokens[1]][1]}();
    }

    private function __construct() {

    }

    private function __clone() {

    }
}