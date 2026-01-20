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
        string $celebrating_date,
        \models\User $organizer,
        \models\User $organized,
        string $title,
        string $location,
        string $description
    ): \models\Event {
        $prepared_statement =
            $this->db_connection->prepare(
                "INSERT INTO events (celebrating_date, organizer_id, organized_id, title, `location`, `description`)
                VALUES (?, ?, ?, ?, ?, ?)"
            );

        $organizer_id = $organizer->get_id();
        $organized_id = $organized->get_id();
        $prepared_statement->bind_param("siisss", $celebrating_date, $organizer_id, $organized_id, $title, $location, $description);
        $prepared_statement->execute();

        return new \models\Event(
            $prepared_statement->insert_id,
            $celebrating_date,
            $organizer,
            $organized,
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

    public function get_all_events_for_date(string $date): array {
        $prepared_statement =
            $this->db_connection->prepare(
                "SELECT * FROM events WHERE celebrating_date = ?"
            );

        $prepared_statement->bind_param("s", $date);
        $prepared_statement->execute();

        $events = [];

        $result = $prepared_statement->get_result();
        while (true) {
            $row = $result->fetch_assoc();

            if ($row === null || $row === false) {
                break;
            }

            $organizer = $this->user_service->find_user_by_id($row["organizer_id"]);
            $organized = $this->user_service->find_user_by_id($row["organized_id"]);
            array_push($events, new \models\Event(
                $row["id"],
                $date,
                $organizer,
                $organized,
                $row["title"],
                $row["location"],
                $row["description"]
            ));
        }

        return $events;
    }

    public function get_all_events_organized_for(\models\User $organized): array {
        $prepared_statement =
            $this->db_connection->prepare(
                "SELECT * FROM events WHERE organized_id = ?"
            );

        $organized_id = $organized->get_id();
        $prepared_statement->bind_param("i", $organized_id);
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
                $row["celebrating_date"],
                $organizer,
                $organized,
                $row["title"],
                $row["location"],
                $row["description"]
            ));
        }

        return $events;
    }

    public function get_all_guests_for_event(\models\Event $event): array {
        $prepared_statement =
            $this->db_connection->prepare(
                "SELECT guest_id FROM guests WHERE event_id = ?"
            );

        $event_id = $event->get_id();
        $prepared_statement->bind_param("i", $event_id);
        $prepared_statement->execute();

        $guests = [];

        $result = $prepared_statement->get_result();
        while (true) {
            $row = $result->fetch_assoc();

            if ($row === null || $row === false) {
                break;
            }

            $guest = $this->user_service->find_user_by_id($row["guest_id"]);
            array_push($guests, $guest);
        }

        return $guests;
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

    public function find_gift_by_id(int $gift_id): ?\models\Gift {
        $prepared_statement =
            $this->db_connection->prepare(
                "SELECT * FROM gifts WHERE id = ?"
            );

        $prepared_statement->bind_param("i", $gift_id);
        $prepared_statement->execute();

        $result = $prepared_statement->get_result();

        if ($result->num_rows !== 1) {
            return null;
        }

        $row = $result->fetch_assoc();
        $event = $this->find_event_by_id($row["event_id"]);
        $guest = $this->user_service->find_user_by_id($row["assigned_guest_id"]);
        return new \models\Gift(
            $row["id"],
            $event,
            $guest,
            $row["description"]
        );
    }

    public function find_event_by_id(int $event_id): ?\models\Event {
        $prepared_staement =
            $this->db_connection->prepare(
                "SELECT * FROM events WHERE id = ?"
            );

        $prepared_staement->bind_param("i", $event_id);
        $prepared_staement->execute();

        $result = $prepared_staement->get_result();
        if ($result->num_rows !== 1) {
            return null;
        } else {
            $row = $result->fetch_assoc();
            $organizer = $this->user_service->find_user_by_id($row["organizer_id"]);
            $organized = $this->user_service->find_user_by_id($row["organized_id"]);
            return new \models\Event(
                $event_id,
                $row["celebrating_date"],
                $organizer,
                $organized,
                $row["title"],
                $row["location"],
                $row["description"]
            );
        }
    }
}
