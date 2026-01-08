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
        include("templates/dates/my-dates.php");
        include("templates/footer.php");
    }

    public function delete_date(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: /");
            exit;
        }

        if (count($params) === 0) {
            header("Location: /my-dates");
            exit;
        }

        $date_id = intval($params[0]);
        $date = $this->find_date_by_id($date_id);

        if ($date !== null) {
            $this->user_service->delete_date($date);
        }

        header("Location: /my-dates");
        exit;
    }

    public function show_add_date_form(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: /");
            exit;
        }

        $add_date = true;

        include("templates/header.php");
        include("templates/dates/dates-form.php");
        include("templates/footer.php");
    }

    public function add_date(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: /");
            exit;
        }

        $user = $this->user_service->find_user_by_id(SessionManager::get_logged_user_id());
        
        $errors = [];
        $date = $this->user_service->add_date(
            $user,
            $_POST["date"],
            $_POST["title"],
            $errors
        );

        if ($date === null) {
            $add_date = true;

            include("templates/header.php");
            include("templates/dates/dates-form.php");
            include("templates/footer.php");
        } else {
            header("Location: /my-dates");
            exit;
        }
    }

    public function show_edit_date_form(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: /");
            exit;
        }

        if (count($params) === 0) {
            header("Location: /my-dates");
            exit;
        }

        $date_id = intval($params[0]);
        $date = $this->find_date_by_id($date_id);

        if ($date === null) {
            header("Location: /my-dates");
            exit;
        }

        $add_date = false;

        include("templates/header.php");
        include("templates/dates/dates-form.php");
        include("templates/footer.php");
    }

    public function edit_date(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: /");
            exit;
        }

        if (count($params) === 0) {
            header("Location: /my-dates");
            exit;
        }

        $date_id = intval($params[0]);
        $date = $this->find_date_by_id($date_id);

        if ($date === null) {
            header("Location: /my-dates");
            exit;
        }

        $updated_date = clone $date;
        $updated_date->set_date($_POST["date"]);
        $updated_date->set_title($_POST["title"]);

        $errors = [];
        $updated_date = $this->user_service->change_date($updated_date, $errors);

        if ($updated_date === null) {
            $add_date = false;

            include("templates/header.php");
            include("templates/dates/dates-form.php");
            include("templates/footer.php");
        } else {
            header("Location: /my-dates");
            exit;
        }
    }

    private function find_date_by_id(int $date_id): ?\models\Date {
        $user = $this->user_service->find_user_by_id(SessionManager::get_logged_user_id());
        $dates = $this->user_service->get_all_dates_of_user($user);

        foreach ($dates as $date) {
            if ($date->get_id() === $date_id) {
                return $date;
            }
        }

        return null;
    }
}