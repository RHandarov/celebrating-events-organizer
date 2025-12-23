<?php

namespace Db;

use mysqli;

class DBPool {
    private int $num_connections;
    private array $db_connections;

    public function __construct() {
        $this->num_connections = 0;
        $this->db_connections = array();
    }

    public function get_connection(): \mysqli {
        $db_connection = null;
        if ($this->num_connections > 0) {
            $db_connection = $this->db_connections[$this->num_connections - 1];

            array_pop($this->db_connections);
            --$this->num_connections;
        } else {
            $db_connection = $this->make_connection();
        }

        return $db_connection;
    }

    private function make_connection(): \mysqli {
        $new_connection = new \mysqli(
            "localhost",
            "root",
            "",
            "celebrating_events_organizer",
            3306
        );

        if ($new_connection->connect_errno) {
            throw new \Exception(
                "Connection to the db has failed! Error: " . $new_connection->connect_errno
            );
        }

        $new_connection->set_charset("utf8mb4");

        return $new_connection;
    }

    public function release_connection(\mysqli $db_connection): void {
        if ($db_connection === null) {
            throw new \BadMethodCallException("DB connection is null!");
        }

        array_push($this->db_connections, $db_connection);
        ++$this->num_connections;
    }

    public function __destruct() {
        foreach ($this->db_connections as $db_connection) {
            $db_connection->close();
        }
    }
}