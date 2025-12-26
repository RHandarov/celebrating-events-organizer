<?php

namespace models;

class User {
    private int $id;
    private string $username;
    private string $email;
    private string $password;

    public function __construct(int $id,
        string $username,
        string $email,
        string $password) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
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

    public function set_email($new_email): void {
        $this->email = $new_email;
    }

    public function get_password(): string {
        return $this->password;
    }

    public function set_password($new_password): void {
        $this->password = hash("sha256", $new_password);
    }
}