<?php

namespace models;

class Date {
    private int $id;
    private \models\User $owner;
    private string $date;
    private string $title;

    public function __construct(int $id, \models\User $owner, string $date, string $title) {
        $this->id = $id;
        $this->owner = $owner;
        $this->date = $date;
        $this->title = $title;
    }

    public function get_id(): int {
        return $this->id;
    }

    public function get_owner(): \models\User {
        return $this->owner;
    }

    public function get_date(): string {
        return $this->date;
    }

    public function set_date(string $new_date): void {
        $this->date = trim($new_date);
    }

    public function get_title(): string {
        return $this->title;
    }

    public function set_title($new_title): void {
        $this->title = htmlspecialchars(trim($new_title));
    }
}