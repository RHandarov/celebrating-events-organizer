<?php

namespace repositories;

class EventRepository {
    private \mysqli $db_connection;

    public function __construct(\mysqli $db_connection) {
        $this->db_connection = $db_connection;
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
}