<?php

namespace models;

class User {
    private int $id;
    private string $username;
    private string $email;
    private string $full_name;
    private string $password;

    public function __construct(int $id,
        string $username,
        string $email,
        string $full_name,
        string $password) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->full_name = $full_name;
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

    public function set_email(string $new_email): void {
        $this->email = $new_email;
    }

    public function get_full_name(): string {
        return $this->full_name;
    }

    public function set_full_name(string $new_full_name): void {
        $this->full_name = htmlspecialchars(trim($new_full_name));
    }

    public function get_password(): string {
        return $this->password;
    }

    public function set_password(string $new_password): void {
        $this->password = hash("sha256", $new_password);
    }
}
