<?php

namespace controllers;

use Router;
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
            header("Location: " . Router::get_url() . "?action=login");
            exit;
        }

        $user_id = SessionManager::get_logged_user_id();
        $user = $this->user_service->find_user_by_id($user_id);

        if ($user === null) {
            header("Location: " . Router::get_url() . "?action=login");
            exit;
        }

        $all_events_for_this_user = $this->event_service->get_all_organizing_events_for_user($user);

        include("frontend/templates/header.php");
        include("frontend/templates/events/all-events.php");
        include("frontend/templates/footer.php");
    }

    public function show_event_details(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: " . Router::get_url() . "?action=login");
            exit;
        }

        if (!isset($_GET["id"])) {
            header("Location: " . Router::get_url() . "?action=all-events");
            exit;
        }

        $event_id = intval($_GET["id"]);
        $event = $this->event_service->find_event_by_id($event_id);

        if ($event->get_organized()->get_id() === SessionManager::get_logged_user_id()) {
            header("Location: " . Router::get_url() . "?action=all-events");
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

        $gifts = $this->event_service->get_all_gifts_of_event($event);
        
        include("frontend/templates/header.php");
        include("frontend/templates/events/event-details.php");
        include("frontend/templates/footer.php");
    }

    public function enroll_in_event(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: " . Router::get_url() . "?action=login");
            exit;
        }

        $event_id = intval($_POST["event_id"]);
        $event = $this->event_service->find_event_by_id($event_id);

        if ($event === null) {
            header("Location: " . Router::get_url() . "?action=all-events");
            exit;
        }

        $logged_user = $this->user_service->find_user_by_id(SessionManager::get_logged_user_id());

        $errors = [];
        $this->event_service->add_guest_to_event($logged_user, $event, $errors);

        header("Location: " . Router::get_url() . "?action=event&id=" . $event->get_id());
        exit;
    }

    public function leave_event(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: " . Router::get_url() . "?action=login");
            exit;
        }

        $event_id = intval($_POST["event_id"]);
        $event = $this->event_service->find_event_by_id($event_id);

        if ($event === null) {
            header("Location: " . Router::get_url() . "?action=all-events");
            exit;
        }

        $logged_user = $this->user_service->find_user_by_id(SessionManager::get_logged_user_id());

        $errors = [];
        $this->event_service->delete_guest_from_event($logged_user, $event, $errors);

        header("Location: " . Router::get_url() . "?action=event&id=" . $event->get_id());
        exit;
    }

    public function show_create_event_form(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: " . Router::get_url() . "?action=login");
            exit;
        }

        $user_id = SessionManager::get_logged_user_id();
        $user = $this->user_service->find_user_by_id($user_id);

        if ($user === null) {
            header("Location: " . Router::get_url() . "?action=login");
            exit;
        }

        $followed_users = $this->user_service->get_all_followed_of_user($user);

        $available_dates = [];
        foreach ($followed_users as $friend) {
            $friend_dates = $this->user_service->get_all_dates_of_user($friend);
            $available_dates = array_merge($available_dates, $friend_dates);
        }

        include("frontend/templates/header.php");
        include("frontend/templates/events/create-event.php");
        include("frontend/templates/footer.php");
    }

    public function create_event(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: " . Router::get_url() . "?action=login");
            exit;
        }

        $user_id = SessionManager::get_logged_user_id();
        $organizer = $this->user_service->find_user_by_id($user_id);

        $title = $_POST['title'];
        $location = $_POST['location'];
        $description = $_POST['description'];
        $date_id = intval($_POST['date_id']); 

        $errors = [];

        $date = $this->user_service->find_date_by_id($date_id);

        if ($date === null) {
            header("Location: " . Router::get_url() . "?action=event&a=create");
            exit;
        }

        $event = $this->event_service->add_event(
            $date, 
            $organizer, 
            $title, 
            $location, 
            $description, 
            $errors
        );

        if ($event === null || count($errors) > 0) {
            
            $followed_users = $this->user_service->get_all_followed_of_user($organizer);
            $available_dates = [];
            foreach ($followed_users as $friend) {
                $friend_dates = $this->user_service->get_all_dates_of_user($friend);
                $available_dates = array_merge($available_dates, $friend_dates);
            }

            include("frontend/templates/header.php");
            include("frontend/templates/events/create-event.php");
            include("frontend/templates/footer.php");
        } else {
            header("Location: " . Router::get_url() . "?action=event&id=" . $event->get_id());
            exit;
        }
    }
}
