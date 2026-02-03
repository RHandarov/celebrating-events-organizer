<?php

namespace controllers;

use Router;
use SessionManager;

class AuthController {
    private \services\UserService $user_service;

    public function __construct() {
        $this->user_service = new \services\UserService();
    }

    public function show_login_form(array $params): void {
        $this->forward_if_logged_in();

        include("frontend/templates/header.php");
        include("frontend/templates/auth/login_form.php");
        include("frontend/templates/footer.php");
    }

    public function show_register_form(array $params): void {
        $this->forward_if_logged_in();

        include("frontend/templates/header.php");
        include("frontend/templates/auth/register_form.php");
        include("frontend/templates/footer.php");
    }

    private function forward_if_logged_in(): void {
        if (SessionManager::is_logged_in()) {
            header("Location: " . Router::get_url());
            exit;
        }
    }

    public function log_out(array $params): void {
        SessionManager::logout();

        header("Location: " . Router::get_url());
        exit;
    }

    public function login(array $params): void {
        $errors = [];
        $user =
            $this->user_service->find_user_by_username_and_password(
                $_POST["username"],
                $_POST["password"],
                $errors
            );

        if ($user !== null) {
            SessionManager::login($user);

            header("Location: " . Router::get_url());
            exit;
        } else {
            include("frontend/templates/header.php");
            include("frontend/templates/auth/login_form.php");
            include("frontend/templates/footer.php");
        }
    }

    public function register(array $params): void {
        $errors = [];
        $new_user =
            $this->user_service->add_user(
                $_POST["username"],
                $_POST["email"],
                $_POST["full-name"],
                $_POST["password"],
                $errors
            );

        if ($new_user !== null) {
            header("Location: " . Router::get_url() . "?action=login");
            exit;
        } else {
            include("frontend/templates/header.php");
            include("frontend/templates/auth/register_form.php");
            include("frontend/templates/footer.php");
        }
    }
}
