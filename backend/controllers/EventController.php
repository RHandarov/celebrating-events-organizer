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

    public function show_all_events(): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: /login");
            exit;
        }

        $user_id = SessionManager::get_logged_user_id();
        $user = $this->user_service->find_user_by_id($user_id);

        if ($user === null) {
            header("Location: /login");
            exit;
        }

        $all_events_for_this_user = $this->event_service->get_all_organizing_events_for_user($user);

        include("templates/header.php");
        include("templates/events/all-events.php");
        include("templates/footer.php");
    }
}