<?php

namespace repositories;

class UserRepository {
    private \mysqli $db_connection;

    public function __construct(\mysqli $db_connection) {
        $this->db_connection = $db_connection;
    }

    
}