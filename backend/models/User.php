<?php

namespace models;

class User {
    private int $id;
    private string $username;
    private string $email;
    private string $password;
    private array $followers;

    public function __construct(int $id,
        string $username,
        string $email,
        string $password,
        array $followers = []) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
<<<<<<< HEAD
<<<<<<< HEAD
=======
        $this->followers = $followers;
>>>>>>> 3d48d92 (Add method of getting all followers by user id and finding user by id)
=======
        $this->followers = [];
>>>>>>> a65f128 (Add followers field, getter and adding methods in the user model)
    }

    public function get_id(): int {
        return $this->id;
    }

    public function get_username(): string {
        return $this->username;
    }

    public function get_email(): string {
        return $this->email;
    }

    public function get_password(): string {
        return $this->password;
    }

    public function get_followers(): array {
        return $this->followers;
    }

    public function add_follower(int $follower_id): void {
        array_push($this->followers, $follower_id);
    }
}