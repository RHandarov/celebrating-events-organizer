<?php

namespace repositories;

class EventRepository {
    private \mysqli $db_connection;
    private \services\UserService $user_service;

    public function __construct(\mysqli $db_connection, \services\UserService $user_service) {
        $this->db_connection = $db_connection;
        $this->user_service = $user_service;
    }

    public function add_event(
        \models\Date $date,
        \models\User $organizer,
        string $location,
        string $description
    ): \models\Event {
        $prepared_statement =
            $this->db_connection->prepare(
                "INSERT INTO events (date_id, organizer_id, `location`, `description`)
                VALUES (?, ?, ?, ?)"
            );

        $date_id = $date->get_id();
        $organizer_id = $organizer->get_id();
        $prepared_statement->bind_param("iiss", $date_id, $organizer_id, $location, $description);
        $prepared_statement->execute();

        return new \models\Event(
            $prepared_statement->insert_id,
            $date,
            $organizer,
            $location,
            $description
        );
    }

    public function change_event(\models\Event $changed_event): \models\Event {
        $prepared_statement =
            $this->db_connection->prepare(
                "UPDATE events
                SET `location` = ?, `description` = ?
                WHERE id = ?"
            );

        $location = $changed_event->get_location();
        $description = $changed_event->get_description();
        $event_id = $changed_event->get_id();
        $prepared_statement->bind_param("ssi", $location, $description, $event_id);
        $prepared_statement->execute();

        return $changed_event;
    }

    public function get_all_events_for_date(\models\Date $date): array {
        $prepared_statement =
            $this->db_connection->prepare(
                "SELECT * FROM events WHERE date_id = ?"
            );

        $date_id = $date->get_id();
        $prepared_statement->bind_param("i", $date_id);
        $prepared_statement->execute();

        $events = [];

        $result = $prepared_statement->get_result();
        while (true) {
            $row = $result->fetch_assoc();

            if ($row === null || $row === false) {
                break;
            }

            $organizer = $this->user_service->find_user_by_id($row["organizer_id"]);
            array_push($events, new \models\Event(
                $row["id"],
                $date,
                $organizer,
                $row["location"],
                $row["description"]
            ));
        }

        return $events;
    }

    public function save_user_as_guest(\models\User $guest, \models\Event $event): void {
        if ($this->is_user_already_guest($guest, $event)) {
            return;
        }

        $prepared_statement =
            $this->db_connection->prepare(
                "INSERT guests (event_id, guest_id) VALUES (?, ?)"
            );

        $event_id = $event->get_id();
        $guest_id = $guest->get_id();
        $prepared_statement->bind_param("ii", $event_id, $guest_id);
        $prepared_statement->execute();
    }

    public function is_user_already_guest(\models\User $guest, \models\Event $event): bool {
        $prepared_statement =
            $this->db_connection->prepare(
                "SELECT * FROM guests WHERE guest_id = ? AND event_id = ?"
            );

        $guest_id = $guest->get_id();
        $event_id = $event->get_id();
        $prepared_statement->bind_param("ii", $guest_id, $event_id);
        $prepared_statement->execute();

        return $prepared_statement->get_result()->num_rows > 0;
    }

    public function delete_guest_from_event(\models\User $guest, \models\Event $event): void {
        $prepared_statement =
            $this->db_connection->prepare(
                "DELETE FROM guests WHERE guest_id = ? AND event_id = ?"
            );

        $guest_id = $guest->get_id();
        $event_id = $event->get_id();
        $prepared_statement->bind_param("ii", $guest_id, $event_id);
        $prepared_statement->execute();
    }
}