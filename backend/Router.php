<?php

final class Router {
    private static array $PATHS = array(
        "GET" => [
            "/login" => [\controllers\AuthController::class, "show_login_form"],
            "/register" => [\controllers\AuthController::class, "show_register_form"],
            "/logout" => [\controllers\AuthController::class, "log_out"],
            "/my-dates/delete" => [\controllers\DateController::class, "delete_date"],
            "/my-dates" => [\controllers\DateController::class, "show_my_dates"],
            "/" => [\controllers\HomeController::class, "show_home_page"]
        ],
        "POST" => [
            "/login" => [\controllers\AuthController::class, "login"],
            "/register" => [\controllers\AuthController::class, "register"]
        ]
    );

    public static function run(): void {
        $routes = self::$PATHS[$_SERVER["REQUEST_METHOD"]];
        $controller_and_method = self::find_controller_and_method($_SERVER["REQUEST_URI"], $routes);
        $requst_params = self::get_request_params($_SERVER["REQUEST_URI"], $routes);

        $controller = self::make_controller($controller_and_method[0]);
        $controller->{$controller_and_method[1]}($requst_params);
    }

    private static function find_controller_and_method(string $request_uri, array $routes): ?array {
        foreach ($routes as $path => $controller_and_method) {
            if (str_starts_with($request_uri, $path)) {
                return $controller_and_method;
            }
        }

        return null;
    }

    private static function get_request_params(string $request_uri, array $routes): array {
        $request_uri = self::slice_path($request_uri, $routes);
        $request_uri = trim($request_uri, "/");

        if ($request_uri === "") {
            return [];
        }

        return explode("/", $request_uri);
    }

    private static function slice_path(string $request_uri, array $routes): string {
        foreach ($routes as $path => $controller_and_method) {
            if (str_starts_with($request_uri, $path)) {
                return substr($request_uri, strlen($path));
            }
        }

        return "";
    }

    private function __construct() {

    }

    private function __clone() {

    }
}