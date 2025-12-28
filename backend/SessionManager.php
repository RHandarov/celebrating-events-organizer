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
            $_SESSION = array();
        }
    }

    public static function is_logged_in(): bool {
        return isset($_SESSION["is_logged"]) && $_SESSION["is_logged"] === true;
    }

    private function __construct() {

    }

    private function __clone() {

    }
}