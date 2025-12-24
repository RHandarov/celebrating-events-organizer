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

        $username_length = mb_strlen($username);
        if ($username_length === 0 || $username_length > 30) {
            array_push($errors,
                "Дължината на потребителското име трябва да е не повече от 30 символа!");

            return null;
        }

        $user = $this->user_repository->find_user_by_username_and_password($username, $password);

        if ($user === null) {
            array_push($errors,
                "Потребителят " . $username . " не съществува! Проверете потребителското име и паролата!");
        }

        return $user;
    }
}