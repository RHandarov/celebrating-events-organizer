<?php

namespace models;

class Gift {
    private int $id;
    private \models\Event $event;
    private \models\User $assigned_guest;
    private string $description;

    public function __construct(
        int $id,
        \models\Event $event,
        \models\User $assigned_guest,
        string $description
    ) {
        $this->id = $id;
        $this->event = $event;
        $this->assigned_guest = $assigned_guest;
        $this->description = $description;
    }

    public function get_id(): int {
        return $this->id;
    }

    public function get_event(): \models\Event {
        return $this->event;
    }

    public function get_assigned_guest(): \models\User {
        return $this->assigned_guest;
    }

    public function get_description(): string {
        return $this->description;
    }

    public function set_description(string $new_description): void {
        $this->description = $new_description;
    }
}