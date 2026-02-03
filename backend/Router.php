<?php

final class Router {
    private static \services\UserService $user_service;
    private static \services\EventService $event_service;
    private static string $parsed_request_uri;

    private static array $PATHS = array(
        "GET" => [
            "/index.php?action=login" => [\controllers\AuthController::class, "show_login_form"],
            "/index.php?action=register" => [\controllers\AuthController::class, "show_register_form"],
            "/index.php?action=logout" => [\controllers\AuthController::class, "log_out"],
            "/index.php?action=my-dates&a=delete" => [\controllers\DateController::class, "delete_date"],
            "/index.php?action=my-dates&a=add" => [\controllers\DateController::class, "show_add_date_form"],
            "/index.php?action=my-dates&a=edit" => [\controllers\DateController::class, "show_edit_date_form"],
            "/index.php?action=my-dates" => [\controllers\DateController::class, "show_my_dates"],
            "/index.php?action=all-events" => [\controllers\EventController::class, "show_all_events"],
            "/index.php?action=event&a=create" => [\controllers\EventController::class, "show_create_event_form"],
            "/index.php?action=event" => [\controllers\EventController::class, "show_event_details"],
            "/index.php?action=user&a=change-password" => [\controllers\UserController::class, "show_change_password_form"],
            "/index.php?action=user&a=change-full-name" => [\controllers\UserController::class, "show_change_full_name_form"],
            "/index.php?action=users&a=find" => [\controllers\UserController::class, "show_find_users"],
            "/index.php?action=user" => [\controllers\UserController::class, "show_user_details"],
            "/index.php?action=gift&a=add" => [\controllers\GiftController::class, "show_add_gift_form"],
            "/index.php?action=gift&a=edit" => [\controllers\GiftController::class, "show_edit_gift_form"],
            "/index.php?action=gift&a=delete" => [\controllers\GiftController::class, "delete_gift"],
            "/" => [\controllers\HomeController::class, "show_home_page"]
        ],
        "POST" => [
            "/index.php?action=login" => [\controllers\AuthController::class, "login"],
            "/index.php?action=register" => [\controllers\AuthController::class, "register"],
            "/index.php?action=my-dates&a=add" => [\controllers\DateController::class, "add_date"],
            "/index.php?action=my-dates&a=edit" => [\controllers\DateController::class, "edit_date"],
            "/index.php?action=user&a=follow" => [\controllers\UserController::class, "follow"],
            "/index.php?action=user&a=unfollow" => [\controllers\UserController::class, "unfollow"],
            "/index.php?action=user&a=change-password" => [\controllers\UserController::class, "change_password"],
            "/index.php?action=user&a=change-full-name" => [\controllers\UserController::class, "change_full_name"],
            "/index.php?action=event&a=create" => [\controllers\EventController::class, "create_event"],
            "/index.php?action=event&a=enroll" => [\controllers\EventController::class, "enroll_in_event"],
            "/index.php?action=event&a=leave" => [\controllers\EventController::class, "leave_event"],
            "/index.php?action=gift&a=add" => [\controllers\GiftController::class, "add_gift"],
            "/index.php?action=gift&a=edit" => [\controllers\GiftController::class, "edit_gift"]
        ]
    );

    public static function init(): void {
        self::$user_service = new \services\UserService();
        self::$event_service = new \services\EventService(self::$user_service);
    }

    public static function run(): void {
        $routes = self::$PATHS[$_SERVER["REQUEST_METHOD"]];
        $request_uri = self::parse_request_uri($_SERVER["REQUEST_URI"]);
        self::$parsed_request_uri = $request_uri;
        $controller_and_method = self::find_controller_and_method($request_uri, $routes);
        $requst_params = self::get_request_params($request_uri, $routes);

        $controller = self::make_controller($controller_and_method[0]);
        $controller->{$controller_and_method[1]}($requst_params);
    }

    private static function parse_request_uri(string $request_uri): string {
        $tokens = explode("/", $request_uri);

        $result = "/";
        $should_add = false;
        foreach ($tokens as $token) {
            if (str_starts_with($token, "index.php")) {
                $should_add = true;
            }

            if ($should_add) {
                $result = $result . $token;
            }
        }

        return $result;
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
        } else if ($controller_class === \controllers\UserController::class) {
            return new \controllers\UserController(self::$user_service);
        } else if ($controller_class === \controllers\GiftController::class) {
            return new \controllers\GiftController(self::$event_service, self::$user_service);
        } else if ($controller_class === \controllers\HomeController::class) {
            return new \controllers\HomeController(self::$user_service, self::$event_service);
        }

        return new $controller_class();
    }

    public static function get_url(): string {
        $current_url = explode("?", $_SERVER["REQUEST_URI"]);
        if (!str_ends_with($current_url[0], "/index.php")) {
            $current_url[0] = $current_url[0] . "index.php";
        }

        $result = "http://" . $_SERVER["HTTP_HOST"] . $current_url[0];
        return $result;
    }

    private function __construct() {

    }

    private function __clone() {

    }
}
