<?php

namespace models;

class User {
    private int $id;
    private string $username;
    private string $email;
    private string $password;
    private array $followers;
    private array $dates;

    public function __construct(int $id,
        string $username,
        string $email,
        string $password,
        array $followers = [],
        array $dates = []) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->followers = $followers;
        $this->dates = $dates;
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

    public function get_dates(): array {
        return $this->dates;
    }

    public function add_follower(int $follower_id): void {
        array_push($this->followers, $follower_id);
    }
}