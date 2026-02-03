<?php

namespace controllers;

use Router;
use SessionManager;

class UserController {
    private \services\UserService $user_service;

    public function __construct(\services\UserService $user_service) {
        $this->user_service = $user_service;
    }

    public function show_user_details(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: " . Router::get_url() . "?action=login");
            exit;
        }

        if (!isset($_GET["id"])) {
            header("Location: " . Router::get_url());
            exit;
        }

        $user_id = intval($_GET["id"]);
        $user = $this->user_service->find_user_by_id($user_id);

        if ($user === null) {
            header("Location: " . Router::get_url());
            exit;
        }

        $user_dates = $this->user_service->get_all_dates_of_user($user);
        $user_followers = $this->user_service->get_all_followers_of_user($user);

        $does_logged_user_follows = false;
        foreach ($user_followers as $follower) {
            if ($follower->get_id() === SessionManager::get_logged_user_id()) {
                $does_logged_user_follows = true;
                break;
            }
        }

        include("frontend/templates/header.php");
        include("frontend/templates/users/user-details.php");
        include("frontend/templates/footer.php");
    }

    public function show_find_users(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: " . Router::get_url() . "?action=login");
            exit;
        }

        $logged_user_id = SessionManager::get_logged_user_id();
        $logged_user = $this->user_service->find_user_by_id($logged_user_id);

        $all_users = $this->user_service->get_all_users_except($logged_user_id);

        $following_users = $this->user_service->get_all_followed_of_user($logged_user);

        $following_ids = [];
        foreach ($following_users as $f_user) {
            $following_ids[] = $f_user->get_id();
        }

        include("frontend/templates/header.php");
        include("frontend/templates/users/find-users.php");
        include("frontend/templates/footer.php");
    }

    public function follow(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: " . Router::get_url() . "?action=login");
            exit;
        }

        $followed_id = intval($_POST["followed_id"]);
        $other_user = $this->user_service->find_user_by_id($followed_id);

        if ($other_user === null) {
            header("Location: " . Router::get_url() . "?action=users&a=find");
            exit;
        }

        $logged_user = $this->user_service->find_user_by_id(SessionManager::get_logged_user_id());

        $this->user_service->follow_user($logged_user, $other_user);

        header("Location: " . Router::get_url() . "?action=users&a=find");
        exit;
    }

    public function unfollow(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: " . Router::get_url() . "?action=login");
            exit;
        }

        $followed_id = intval($_POST["followed_id"]);
        $other_user = $this->user_service->find_user_by_id($followed_id);

        if ($other_user === null) {
            header("Location: " . Router::get_url() . "?action=users&a=find");
            exit;
        }

        $logged_user = $this->user_service->find_user_by_id(SessionManager::get_logged_user_id());

        $this->user_service->unfollow_user($logged_user, $other_user);

        header("Location: " . Router::get_url() . "?action=users&a=find");
        exit;
    }

    public function show_change_password_form(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: " . Router::get_url() . "?action=login");
            exit;
        }

        include("frontend/templates/header.php");
        include("frontend/templates/users/change-password.php");
        include("frontend/templates/footer.php");
    }

    public function change_password(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: " . Router::get_url() . "?action=login");
            exit;
        }

        $errors = [];

        if ($_POST["new-password"] !== $_POST["repeat-new-password"]) {
            array_push($errors, "Новата парола не съвпада с повторението си!");

            include("frontend/templates/header.php");
            include("frontend/templates/users/change-password.php");
            include("frontend/templates/footer.php");

            exit;
        }

        $old_password = hash("sha256", $_POST["old-password"]);

        $user = $this->user_service->find_user_by_id(SessionManager::get_logged_user_id());
        if ($old_password !== $user->get_password()) {
            array_push($errors, "Грешна парола!");

            include("frontend/templates/header.php");
            include("frontend/templates/users/change-password.php");
            include("frontend/templates/footer.php");

            exit;
        }

        $user->set_password($_POST["new-password"]);
        $user = $this->user_service->change_user($user, $errors);

        if ($user === null) {
            include("frontend/templates/header.php");
            include("frontend/templates/users/change-password.php");
            include("frontend/templates/footer.php");

            exit;
        } else {
            header("Location: " . Router::get_url() . "?action=user&id=" . SessionManager::get_logged_user_id());
            exit;
        }
    }

    public function show_change_full_name_form(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: " . Router::get_url() . "?action=login");
            exit;
        }

        $user_id = SessionManager::get_logged_user_id();
        $user = $this->user_service->find_user_by_id($user_id);

        include("frontend/templates/header.php");
        include("frontend/templates/users/change-full-name.php");
        include("frontend/templates/footer.php");
    }

    public function change_full_name(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: " . Router::get_url() . "?action=login");
            exit;
        }

        $user_id = SessionManager::get_logged_user_id();
        $user = $this->user_service->find_user_by_id($user_id);

        $old_full_name = $user->get_full_name();
        $user->set_full_name($_POST["new-full-name"]);

        $errors = [];
        $changed_user = $this->user_service->change_user($user, $errors);

        if ($changed_user === null) {
            $user->set_full_name($old_full_name);

            include("frontend/templates/header.php");
            include("frontend/templates/users/change-full-name.php");
            include("frontend/templates/footer.php");
        } else {
            header("Location: " . Router::get_url() . "?action=user&id=" . $user->get_id());
            exit;
        }
    }
}
