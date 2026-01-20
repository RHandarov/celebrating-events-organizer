<?php

namespace controllers;

use SessionManager;

class UserController {
    private \services\UserService $user_service;

    public function __construct(\services\UserService $user_service) {
        $this->user_service = $user_service;
    }

    public function show_user_details(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: /login");
            exit;
        }

        if (count($params) === 0) {
            header("Location: /");
            exit;
        }

        $user_id = intval($params[0]);
        $user = $this->user_service->find_user_by_id($user_id);

        if ($user === null) {
            header("Location: /");
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

        include("templates/header.php");
        include("templates/users/user-details.php");
        include("templates/footer.php");
    }

    public function show_find_users(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: /login");
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

        include("templates/header.php");
        include("templates/users/find-users.php");
        include("templates/footer.php");
    }


    public function follow(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: /login");
            exit;
        }

        $followed_id = intval($_POST["followed_id"]);
        $other_user = $this->user_service->find_user_by_id($followed_id);

        if ($other_user === null) {
            header("Location: /users/find");
            exit;
        }

        $logged_user = $this->user_service->find_user_by_id(SessionManager::get_logged_user_id());

        $this->user_service->follow_user($logged_user, $other_user);

        header("Location: /users/find");
        exit;
    }

    // public function follow(array $params): void {
    //     if (!SessionManager::is_logged_in()) {
    //         header("Location: /login");
    //         exit;
    //     }

    //     $user_id = intval($_POST["user_id"]);
    //     $other_user = $this->user_service->find_user_by_id($user_id);

    //     if ($other_user === null) {
    //         header("Location: /");
    //         exit;
    //     }

    //     $logged_user = $this->user_service->find_user_by_id(SessionManager::get_logged_user_id());

    //     $this->user_service->follow_user($other_user, $logged_user);

    //     header("Location: /user/" . $user_id);
    //     exit;
    // }


    public function unfollow(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: /login");
            exit;
        }

        $followed_id = intval($_POST["followed_id"]);
        $other_user = $this->user_service->find_user_by_id($followed_id);

        if ($other_user === null) {
            header("Location: /users/find");
            exit;
        }

        $logged_user = $this->user_service->find_user_by_id(SessionManager::get_logged_user_id());

        $this->user_service->unfollow_user($logged_user, $other_user);

        header("Location: /users/find");
        exit;
    }

    // public function unfollow(array $params): void {
    //     if (!SessionManager::is_logged_in()) {
    //         header("Location: /login");
    //         exit;
    //     }

    //     $user_id = intval($_POST["user_id"]);
    //     $other_user = $this->user_service->find_user_by_id($user_id);

    //     if ($other_user === null) {
    //         header("Location: /");
    //         exit;
    //     }

    //     $logged_user = $this->user_service->find_user_by_id(SessionManager::get_logged_user_id());

    //     $this->user_service->unfollow_user($other_user, $logged_user);

    //     header("Location: /user/" . $user_id);
    //     exit;
    // }

    public function show_change_password_form(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: /login");
            exit;
        }

        include("templates/header.php");
        include("templates/users/change-password.php");
        include("templates/footer.php");
    }

    public function change_password(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: /login");
            exit;
        }

        $errors = [];

        if ($_POST["new-password"] !== $_POST["repeat-new-password"]) {
            array_push($errors, "Новата парола не съвпада с повторението си!");

            include("templates/header.php");
            include("templates/users/change-password.php");
            include("templates/footer.php");

            exit;
        }

        $old_password = hash("sha256", $_POST["old-password"]);

        $user = $this->user_service->find_user_by_id(SessionManager::get_logged_user_id());
        if ($old_password !== $user->get_password()) {
            array_push($errors, "Грешна парола!");

            include("templates/header.php");
            include("templates/users/change-password.php");
            include("templates/footer.php");

            exit;
        }

        $user->set_password($_POST["new-password"]);
        $user = $this->user_service->change_user($user, $errors);

        if ($user === null) {
            include("templates/header.php");
            include("templates/users/change-password.php");
            include("templates/footer.php");

            exit;
        } else {
            header("Location: /user/" . SessionManager::get_logged_user_id());
            exit;
        }
    }

    public function show_change_full_name_form(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: /login");
            exit;
        }

        $user_id = SessionManager::get_logged_user_id();
        $user = $this->user_service->find_user_by_id($user_id);

        include("templates/header.php");
        include("templates/users/change-full-name.php");
        include("templates/footer.php");
    }

    public function change_full_name(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: /login");
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

            include("templates/header.php");
            include("templates/users/change-full-name.php");
            include("templates/footer.php");
        } else {
            header("Location: /user/" . $user->get_id());
            exit;
        }
    }
}
