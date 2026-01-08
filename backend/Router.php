<?php

final class Router {
    private static \services\UserService $user_service;
    private static \services\EventService $event_service;

    private static array $PATHS = array(
        "GET" => [
            "/login" => [\controllers\AuthController::class, "show_login_form"],
            "/register" => [\controllers\AuthController::class, "show_register_form"],
            "/logout" => [\controllers\AuthController::class, "log_out"],
            "/my-dates/delete" => [\controllers\DateController::class, "delete_date"],
            "/my-dates/add" => [\controllers\DateController::class, "show_add_date_form"],
            "/my-dates/edit" => [\controllers\DateController::class, "show_edit_date_form"],
            "/my-dates" => [\controllers\DateController::class, "show_my_dates"],
            "/all-events" => [\controllers\EventController::class, "show_all_events"],
            "/" => [\controllers\HomeController::class, "show_home_page"]
        ],
        "POST" => [
            "/login" => [\controllers\AuthController::class, "login"],
            "/register" => [\controllers\AuthController::class, "register"],
            "/my-dates/add" => [\controllers\DateController::class, "add_date"],
            "/my-dates/edit" => [\controllers\DateController::class, "edit_date"]
        ]
    );

    public static function init(): void {
        self::$user_service = new \services\UserService();
        self::$event_service = new \services\EventService(self::$user_service);
    }

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

    private static function make_controller($controller_class): object {
        if ($controller_class === \controllers\DateController::class) {
            return new \controllers\DateController(self::$user_service);
        } else if ($controller_class === \controllers\EventController::class) {
            return new \controllers\EventController(self::$user_service, self::$event_service);
        }

        return new $controller_class();
    }

    private function __construct() {

    }

    private function __clone() {

    }
}