<?php

namespace controllers;

class AuthController {
    private \services\UserService $user_service;

    public function __construct() {
        $this->user_service = new \services\UserService();
    }

    public function show_login_form(): void {
        $this->forward_if_logged_in();

        include("templates/header.php");
        include("templates/login_form.php");
        include("templates/footer.php");
    }

    public function show_register_form(): void {
        $this->forward_if_logged_in();

        include("templates/header.php");
        include("templates/register_form.php");
        include("templates/footer.php");
    }

    private function forward_if_logged_in(): void {
        if ($this->is_logged_in()) {
            header("Location: /");
            exit;
        }
    }

    public function log_out(): void {
        if ($this->is_logged_in()) {
            $_SESSION = array();
        }

        header("Location: /");
        exit;
    }

    private function is_logged_in(): bool {
        return isset($_SESSION["is_logged"]) && $_SESSION["is_logged"] === true;
    }

    public function login(): void {
        $errors = [];

        $user =
            $this->user_service->find_user_by_username_and_password(
                $_POST["username"],
                $_POST["password"],
                $errors
            );

        if ($user !== null) {
            $_SESSION["is_logged"] = true;
            $_SESSION["id"] = $user->get_id();

            header("Location: /");
            exit;
        } else {
            include("templates/header.php");
            include("templates/login_form.php");
            include("templates/footer.php");
        }
    }

    public function register(): void {
        $errors = [];

        $success =
            $this->user_service->add_user(
                $_POST["username"],
                $_POST["email"],
                $_POST["password"],
                $errors
            );

        if ($success === true) {
            header("Location: /login");
            exit;
        } else {
            include("templates/header.php");
            include("templates/register_form.php");
            include("templates/footer.php");
        }
    }
}