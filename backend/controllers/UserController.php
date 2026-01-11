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

    public function follow(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: /login");
            exit;
        }

        $user_id = intval($_POST["user_id"]);
        $other_user = $this->user_service->find_user_by_id($user_id);

        if ($other_user === null) {
            header("Location: /");
            exit;
        }

        $logged_user = $this->user_service->find_user_by_id(SessionManager::get_logged_user_id());

        $this->user_service->follow_user($other_user, $logged_user);

        header("Location: /user/" . $user_id);
        exit;
    }

    public function unfollow(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: /login");
            exit;
        }

        $user_id = intval($_POST["user_id"]);
        $other_user = $this->user_service->find_user_by_id($user_id);

        if ($other_user === null) {
            header("Location: /");
            exit;
        }

        $logged_user = $this->user_service->find_user_by_id(SessionManager::get_logged_user_id());

        $this->user_service->unfollow_user($other_user, $logged_user);

        header("Location: /user/" . $user_id);
        exit;
    }
}