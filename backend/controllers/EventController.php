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

    public function show_all_events(array $params): void {
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

    public function show_event_details(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: /login");
            exit;
        }

        if (count($params) === 0) {
            header("Location: /all-events");
            exit;
        }

        $event_id = intval($params[0]);
        $event = $this->event_service->find_event_by_id($event_id);

        if ($event->get_organized()->get_id() === SessionManager::get_logged_user_id()) {
            header("Location: /all-events");
            exit;
        }

        $guests = $this->event_service->get_all_guests_of_event($event);

        $is_logged_user_guest = false;
        foreach ($guests as $guest) {
            if ($guest->get_id() === SessionManager::get_logged_user_id()) {
                $is_logged_user_guest = true;
                break;
            }
        }
        
        include("templates/header.php");
        include("templates/events/event-details.php");
        include("templates/footer.php");
    }

    public function enroll_in_event(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: /login");
            exit;
        }

        $event_id = intval($_POST["event_id"]);
        $event = $this->event_service->find_event_by_id($event_id);

        if ($event === null) {
            header("Location: /all-events");
            exit;
        }

        $logged_user = $this->user_service->find_user_by_id(SessionManager::get_logged_user_id());

        $errors = [];
        $this->event_service->add_guest_to_event($logged_user, $event, $errors);

        header("Location: /event/" . $event->get_id());
        exit;
    }

    public function leave_event(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: /login");
            exit;
        }

        $event_id = intval($_POST["event_id"]);
        $event = $this->event_service->find_event_by_id($event_id);

        if ($event === null) {
            header("Location: /all-events");
            exit;
        }

        $logged_user = $this->user_service->find_user_by_id(SessionManager::get_logged_user_id());

        $errors = [];
        $this->event_service->delete_guest_from_event($logged_user, $event, $errors);

        header("Location: /event/" . $event->get_id());
        exit;
    }
}
