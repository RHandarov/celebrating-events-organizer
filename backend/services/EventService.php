<?php

namespace services;

class EventService {
    private \mysqli $db_connection;
    private \repositories\EventRepository $event_repository;

    public function __construct() {
        $this->db_connection = \db\DBPool::get_instance()->get_connection();
        $this->event_repository = new \repositories\EventRepository($this->db_connection);
    }

    public function __destruct() {
        \db\DBPool::get_instance()->release_connection($this->db_connection);
    }
}