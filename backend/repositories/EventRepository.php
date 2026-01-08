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
        string $title,
        string $location,
        string $description
    ): \models\Event {
        $prepared_statement =
            $this->db_connection->prepare(
                "INSERT INTO events (date_id, organizer_id, title, `location`, `description`)
                VALUES (?, ?, ?, ?, ?)"
            );

        $date_id = $date->get_id();
        $organizer_id = $organizer->get_id();
        $prepared_statement->bind_param("iisss", $date_id, $organizer_id, $title, $location, $description);
        $prepared_statement->execute();

        return new \models\Event(
            $prepared_statement->insert_id,
            $date,
            $organizer,
            $title,
            $location,
            $description
        );
    }

    public function change_event(\models\Event $changed_event): \models\Event {
        $prepared_statement =
            $this->db_connection->prepare(
                "UPDATE events
                SET title = ?, `location` = ?, `description` = ?
                WHERE id = ?"
            );

        $title = $changed_event->get_title();
        $location = $changed_event->get_location();
        $description = $changed_event->get_description();
        $event_id = $changed_event->get_id();
        $prepared_statement->bind_param("sssi", $title, $location, $description, $event_id);
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
                $row["title"],
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
                "INSERT INTO guests (event_id, guest_id) VALUES (?, ?)"
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

    public function add_gift_to_event(
        \models\Event $event,
        \models\User $assigned_guest,
        string $description
    ): \models\Gift {
        $prepared_statement =
            $this->db_connection->prepare(
                "INSERT INTO gifts (event_id, assigned_guest_id, `description`)
                VALUES (?, ?, ?)"
            );

        $event_id = $event->get_id();
        $assigned_guest_id = $assigned_guest->get_id();
        $prepared_statement->bind_param("iis", $event_id, $assigned_guest_id, $description);
        $prepared_statement->execute();

        return new \models\Gift(
            $prepared_statement->insert_id,
            $event,
            $assigned_guest,
            $description
        );
    }

    public function get_all_gifts_of_event(\models\Event $event): array {
        $prepared_statement = 
            $this->db_connection->prepare(
                "SELECT * FROM gifts WHERE event_id = ?"
            );

        $event_id = $event->get_id();
        $prepared_statement->bind_param("i", $event_id);
        $prepared_statement->execute();

        $gifts = [];

        $result = $prepared_statement->get_result();
        while (true) {
            $row = $result->fetch_assoc();

            if ($row === null || $row === false) {
                break;
            }

            $assigned_guest = $this->user_service->find_user_by_id($row["assigned_guest_id"]);
            array_push($gifts,
                new \models\Gift(
                    $row["id"],
                    $event,
                    $assigned_guest,
                    $row["description"]
                ));
        }

        return $gifts;
    }

    public function change_gift(\models\Gift $gift): \models\Gift {
        $prepared_statement =
            $this->db_connection->prepare(
                "UPDATE gifts SET `description` = ? WHERE id = ?"
            );

        $description = $gift->get_description();
        $gift_id = $gift->get_id();
        $prepared_statement->bind_param("si", $description, $gift_id);
        $prepared_statement->execute();

        return $gift;
    }

    public function delete_gift_from_event(\models\Gift $gift): void {
        $prepared_statement =
            $this->db_connection->prepare(
                "DELETE FROM gifts WHERE id = ?"
            );

        $gift_id = $gift->get_id();
        $prepared_statement->bind_param("i", $gift_id);
        $prepared_statement->execute();
    }
}