<?php

class SessionManager {
    public static function start(): void {
        session_set_cookie_params(0,
            "/",
            null,
            null,
            true);
        session_start([
            "cookie_httponly" => "1"
        ]);
    }

    public static function login(\models\User $user): void {
        $_SESSION["is_logged"] = true;
        $_SESSION["id"] = $user->get_id();
    }

    public static function logout(): void {
        if (self::is_logged_in()) {
            session_destroy();
        }
    }

    public static function is_logged_in(): bool {
        return isset($_SESSION["is_logged"]) && $_SESSION["is_logged"] === true;
    }

    public static function get_logged_user_id(): int {
        return $_SESSION["id"];
    }

    private function __construct() {

    }

    private function __clone() {

    }
}
