<?php

namespace services;

use DateTime;

class UserService {
    private \repositories\UserRepository $user_repository;

    public function __construct() {
        $db_connection = \db\DBPool::get_instance()->get_connection();
        $this->user_repository = new \repositories\UserRepository($db_connection);
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

    public function add_user(string $username, string $email, string $password, array &$errors): bool {
        $username = htmlspecialchars(trim($username));
        $email = htmlspecialchars(trim($email));
        $password = hash("sha256", $password);

        if (!$this->validate_username($username, $errors)) {
            return false;
        }

        if (!$this->validate_email($email, $errors)) {
            return false;
        }

        $this->user_repository->add_user($username, $email, $password);
        return true;
    }

    public function follow_user(int $follower_id, int $followed_id, array &$errors): bool {
        if ($follower_id === $followed_id) {
            return true;
        }

        if (!$this->does_user_with_id_exists($follower_id, $errors)) {
            return false;
        }

        if (!$this->does_user_with_id_exists($followed_id, $errors)) {
            return false;
        }

        if ($this->user_repository->are_users_already_following($follower_id, $followed_id)) {
            return true;
        }

        $this->user_repository->follow_user($follower_id, $followed_id);

        return true;
    }

    public function unfollow_user(int $follower_id, int $followed_id, array &$errors): bool {
        if ($follower_id === $followed_id) {
            return true;
        }

        if (!$this->does_user_with_id_exists($follower_id, $errors)) {
            return false;
        }

        if (!$this->does_user_with_id_exists($followed_id, $errors)) {
            return false;
        }

        if (!$this->user_repository->are_users_already_following($follower_id, $followed_id)) {
            return true;
        }

        $this->user_repository->unfollow_user($follower_id, $followed_id);

        return true;
    }

    public function add_date(int $user_id, string $date, string $title, array &$errors): bool {
        $title = htmlspecialchars(trim($title));


        if (!$this->validate_date_format($date, $errors)) {
            return false;
        }

        if (!$this->validate_date_title($title, $errors)) {
            return false;
        }

        if (!$this->does_user_with_id_exists($user_id, $errors)) {
            return false;
        }

        $this->user_repository->add_date($user_id, $date, $title);

        return true;
    }

    private function does_user_with_id_exists(int $user_id, array &$errors): bool {
        if ($this->user_repository->find_user_by_id($user_id) === null) {
            array_push($errors,
                "Потребителят с ИД " . $user_id . " не съществува!");

            return false;
        }

        return true;
    }

    private function validate_date_title(string $title, array &$errors): bool {
        $username_length = mb_strlen($title);
        if ($username_length === 0 || $username_length > 100) {
            array_push($errors,
                "Дължината на заглавието на датата трябва да е не повече от 100 символа!");

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
}