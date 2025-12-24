<?php

namespace controllers;

class AuthController {
    private \repositories\UserRepository $user_repository;

    public function __construct() {
        $db_connection = \db\DBPool::get_instance()->get_connection();
        $this->user_repository = new \repositories\UserRepository($db_connection);
    }

    public function show_login_form(): void {
        if (isset($_SESSION["is_logged"]) && $_SESSION["is_logged"] === true) {
            return; // should redirect to the home page
        }

        include("templates/header.php");
        include("templates/login_form.php");
        include("templates/footer.php");
    }
}