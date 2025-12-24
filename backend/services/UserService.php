<?php

namespace services;

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