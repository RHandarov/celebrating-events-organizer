<?php

namespace models;

class Event {
    private int $id;
    private \models\Date $date;
    private \models\User $organizer;
    private string $title;
    private string $location;
    private string $description;

    public function __construct(
        int $id,
        \models\Date $date,
        \models\User $organizer,
        string $title,
        string $location,
        string $description
    ) {
        $this->id = $id;
        $this->date = $date;
        $this->organizer = $organizer;
        $this->title = $title;
        $this->location = $location;
        $this->description = $description;
    }

    public function get_id(): int {
        return $this->id;
    }

    public function get_date(): \models\Date {
        return $this->date;
    }

    public function get_organizer(): \models\User {
        return $this->organizer;
    }

    public function get_title(): string {
        return $this->title;
    }

    public function get_location(): string {
        return $this->location;
    }

    public function set_location(string $new_location): void {
        $this->location = htmlspecialchars(trim($new_location));
    }

    public function get_description(): string {
        return $this->description;
    }

    public function set_title(string $new_title): void {
        $this->title = htmlspecialchars(trim($new_title));
    }

    public function set_description(string $new_description): void {
        $this->description = htmlspecialchars(trim($new_description));
    }
}