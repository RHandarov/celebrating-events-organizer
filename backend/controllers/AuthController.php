<?php

namespace controllers;

class AuthController {
    private \services\UserService $user_service;

    public function __construct() {
        $this->user_service = new \services\UserService();
    }

    public function show_login_form(): void {
        if (isset($_SESSION["is_logged"]) && $_SESSION["is_logged"] === true) {
            return; // should redirect to the home page
        }

        include("templates/header.php");
        include("templates/login_form.php");
        include("templates/footer.php");
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
}