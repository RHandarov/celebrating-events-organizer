<?php

namespace models;

class Event {
    private int $id;
    private string $celebrating_date;
    private \models\User $organizer;
    private \models\User $organized;
    private string $title;
    private string $location;
    private string $description;

    public function __construct(
        int $id,
        string $celebrating_date,
        \models\User $organizer,
        \models\User $organized,
        string $title,
        string $location,
        string $description
    ) {
        $this->id = $id;
        $this->celebrating_date = $celebrating_date;
        $this->organizer = $organizer;
        $this->organized = $organized;
        $this->title = $title;
        $this->location = $location;
        $this->description = $description;
    }

    public function get_id(): int {
        return $this->id;
    }

    public function get_celebrating_date(): string {
        return $this->celebrating_date;
    }

    public function get_organizer(): \models\User {
        return $this->organizer;
    }

    public function get_organized(): \models\User {
        return $this->organized;
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