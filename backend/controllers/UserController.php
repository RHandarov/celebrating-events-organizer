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

        echo "Called with id " . $user_id;
    }
}