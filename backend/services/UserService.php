<?php

namespace services;

use DateTime;

class UserService {
    private \mysqli $db_connection;
    private \repositories\UserRepository $user_repository;

    public function __construct() {
        $this->db_connection = \db\DBPool::get_instance()->get_connection();
        $this->user_repository = new \repositories\UserRepository($this->db_connection);
    }

    public function find_user_by_username_and_password(string $username,
        string $password, array &$errors): ?\models\User {
        $username = htmlspecialchars(trim($username));
        $password = hash("sha256", $password);

        if (!$this->validate_username($username, $errors)) {
            return null;
        }

        $user = $this->user_repository->find_user_by_username_and_password($username, $password);

        if ($user === null) {
            array_push($errors,
                "Грешка! Проверете потребителското име и паролата!");
        }

        return $user;
    }

    public function find_user_by_id(int $user_id): ?\models\User {
        return $this->user_repository->find_user_by_id($user_id);
    }

    public function add_user(string $username, string $email, string $full_name, string $password, array &$errors): ?\models\User {
        $username = htmlspecialchars(trim($username));
        $email = htmlspecialchars(trim($email));
        $full_name = htmlspecialchars(trim($full_name));
        $password = hash("sha256", $password);

        if (!$this->validate_username($username, $errors)) {
            return null;
        }

        if (!$this->validate_email($email, $errors)) {
            return null;
        }

        if (!$this->validate_full_name($full_name, $errors)) {
            return null;
        }

        if ($this->user_repository->find_user_by_username($username) !== null) {
            array_push($errors,
                "Потребителското име " . $username . " вече е заето!");

            return null;
        }

        return $this->user_repository->add_user($username, $email, $full_name, $password);
    }

    public function change_user(\models\User $changed_user, array &$errors): ?\models\User {
        if (!$this->validate_email($changed_user->get_email(), $errors)) {
            return null;
        }

        if (!$this->validate_full_name($changed_user->get_full_name(), $errors)) {
            return null;
        }

        return $this->user_repository->change_user($changed_user);
    }

    public function get_all_followers_of_user(\models\User $user): array {
        return $this->user_repository->get_all_followers_of_user($user);
    }

    public function get_all_followed_of_user(\models\User $user): array {
        return $this->user_repository->get_all_followed_of_user($user);
    }

    public function follow_user(\models\User $follower, \models\User $followed): true {
        if ($follower->get_id() === $followed->get_id()) {
            return true;
        }

        if ($this->user_repository->are_users_already_following($follower, $followed)) {
            return true;
        }

        $this->user_repository->follow_user($follower, $followed);

        return true;
    }

    public function unfollow_user(\models\User $follower, \models\User $followed): true {
        if ($follower->get_id() === $followed->get_id()) {
            return true;
        }

        if (!$this->user_repository->are_users_already_following($follower, $followed)) {
            return true;
        }

        $this->user_repository->unfollow_user($follower, $followed);

        return true;
    }

    public function get_all_dates_of_user(\models\User $user): array {
        return $this->user_repository->get_dates_of_user($user);
    }

    public function add_date(\models\User $user, string $date, string $title, array &$errors): ?\models\Date {
        $title = htmlspecialchars(trim($title));
        $date = trim($date);

        if (!$this->validate_date_format($date, $errors)) {
            return null;
        }

        if (!$this->validate_date_title($title, $errors)) {
            return null;
        }

        return $this->user_repository->add_date($user, $date, $title);
    }

    public function change_date(\models\Date $changed_date, array &$errors): ?\models\Date {
        if (!$this->validate_date_format($changed_date->get_date(), $errors)) {
            return null;
        }

        if (!$this->validate_date_title($changed_date->get_title(), $errors)) {
            return null;
        }

        return $this->user_repository->change_date($changed_date);
    }

    public function delete_date(\models\Date $date): true {
        $this->user_repository->delete_date($date);
        return true;
    }

    private function validate_date_title(string $title, array &$errors): bool {
        $username_length = mb_strlen($title);

        if ($username_length === 0) {
            array_push($errors,
                "Поводът не трябва да е празен!");

            return false;
        }

        if ($username_length > 100) {
            array_push($errors,
                "Дължината на повода трябва да е не повече от 100 символа!");

            return false;
        }

        return true;
    }

    private function validate_date_format(string $date, array &$errors): bool {
        $date_format = DateTime::createFromFormat("Y-m-d", $date);
        
        if (!$date_format || $date_format->format("Y-m-d") !== $date) {
            array_push($errors,
                "Датата трябва да е във формат YYYY-MM-DD!");

            return false;
        }

        return true;
    }

    private function validate_username(string $username, array &$errors): bool {
        $username_length = mb_strlen($username);
        if ($username_length === 0 || $username_length > 30) {
            array_push($errors,
                "Дължината на потребителското име трябва да е не повече от 30 символа!");

            return false;
        }

        return true;
    }

    private function validate_email(string $email, array &$errors): bool {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            array_push($errors, "Невалиден имейл!");

            return false;
        }

        return true;
    }

    private function validate_full_name(string $full_name, array &$errors): bool {
        $full_name_length = mb_strlen($full_name);

        if ($full_name_length === 0) {
            array_push($errors,
                "Пълното име не може да е празно!");

            return false;
        }

        if ($full_name_length > 255) {
            array_push($errors,
                "Дължината на пълното име трябва да е не повече от 255 символа!");

            return false;
        }

        return true;
    }

    public function __destruct() {
        \db\DBPool::get_instance()->release_connection($this->db_connection);
    }
}
