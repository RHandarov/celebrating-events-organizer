<?php

namespace controllers;

use SessionManager;

class DateController {
    private \services\UserService $user_service;

    public function __construct(\services\UserService $user_service) {
        $this->user_service = $user_service;
    }

    public function show_my_dates(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: /");
            exit;
        }

        $user_id = SessionManager::get_logged_user_id();
        $user = $this->user_service->find_user_by_id($user_id);
        $user_dates = $this->user_service->get_all_dates_of_user($user);

        include("templates/header.php");
        include("templates/my-dates.php");
        include("templates/footer.php");
    }

    public function delete_date(array $params): void {
        
    }
}