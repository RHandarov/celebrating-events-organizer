<?php

namespace controllers;

use SessionManager;

class EventController {
    private \services\UserService $user_service;
    private \services\EventService $event_service;

    public function __construct(
        \services\UserService $user_service,
        \services\EventService $event_service
    ) {
        $this->user_service = $user_service;
        $this->event_service = $event_service;
    }
}