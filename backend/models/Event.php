<?php

namespace models;

class Event {
    private int $id;
    private \models\Date $date;
    private \models\User $organizer;
    private string $location;
    private string $description;

    public function __construct(
        int $id,
        \models\Date $date,
        \models\User $organizer,
        string $location,
        string $description
    ) {
        $this->id = $id;
        $this->date = $date;
        $this->organizer = $organizer;
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

    public function get_location(): string {
        return $this->location;
    }

    public function set_location(string $new_location): void {
        $this->location = $new_location;
    }

    public function get_description(): string {
        return $this->description;
    }

    public function set_description(string $new_description): void {
        $this->description = $new_description;
    }
}