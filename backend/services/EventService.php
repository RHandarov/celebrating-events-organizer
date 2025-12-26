<?php

namespace services;

class EventService {
    private \mysqli $db_connection;
    private \repositories\EventRepository $event_repository;
    private \services\UserService $user_service;

    public function __construct(\services\UserService $user_service) {
        $this->db_connection = \db\DBPool::get_instance()->get_connection();
        $this->event_repository = new \repositories\EventRepository($this->db_connection, $user_service);
        $this->user_service = $user_service;
    }

    public function add_event(
        \models\Date $date,
        \models\User $organizer,
        string $location,
        string $description,
        array &$errors): ?\models\Event {
        if ($this->is_organizer_the_same_as_celebrant($organizer, $date)) {
            array_push($errors,
                "Не може да организираш парти на себе си!");

            return null;
        }

        if (!$this->validate_location($location, $errors)) {
            return null;
        }

        if ($this->is_date_past($date)) {
            array_push($errors,
                "Датата на партито е минала!");

            return null;
        }

        if (!$this->is_organizer_following_celebrant($organizer, $date)) {
            array_push($errors,
                "Организаторът не следва \"виновника\"!");

            return null;
        }

        return $this->event_repository->add_event($date, $organizer, $location, $description);
    }

    public function change_event(\models\Event $changed_event, array &$errors): ?\models\Event {
        if (!$this->validate_location($changed_event->get_location(), $errors)) {
            return null;
        }

        return $this->event_repository->change_event($changed_event);
    }

    private function is_organizer_the_same_as_celebrant(\models\User $organizer, \models\Date $date): bool {
        return $organizer->get_id() === $date->get_owner()->get_id();
    }

    private function validate_location(string $location, array &$errors) : bool {
        if ($location === "") {
            array_push($errors,
                "Локацията не бива да е празна!");

            return false;
        }

        $location_length = mb_strlen($location);

        if ($location_length > 256) {
            array_push($errors,
                "Дължината на локацията трябва да е не повече от 256 символа!");

            return false;
        }

        return true;
    }

    private function is_date_past(\models\Date $date): bool {
        return time() >= $this->get_actual_celebrating_date($date);
    }

    // Changes year of the date and returns it as a timestamp to compore woth
    // the current moment.
    private function get_actual_celebrating_date(\models\Date $date): int {
        $day_and_month = date("m-d", strtotime($date->get_date()));
        return strtotime(date("Y") . "-" . $day_and_month);
    }

    private function is_organizer_following_celebrant(
        \models\User $organizer,
        \models\Date $date
    ): bool {
        $organizer_followeds = $this->user_service->get_all_followers_of_user($organizer);

        foreach ($organizer_followeds as $followed) {
            if ($followed->get_id() === $date->get_owner()->get_id()) {
                return true;
            }
        }

        return false;
    }

    public function get_all_organizing_events_for_user(\models\User $user): array {
        $events = [];

        $followed_users = $this->user_service->get_all_followers_of_user($user);
        foreach ($followed_users as $followed_user) {
            $events = array_merge($events, $this->get_all_celebrant_events($followed_user));
        }

        return $events;
    }

    private function get_all_celebrant_events(\models\User $celebrant): array {
        $events = [];

        $dates = $this->user_service->get_all_dates_of_user($celebrant);
        foreach ($dates as $date) {
            $events = array_merge($events, $this->event_repository->get_all_events_for_date($date));
        }

        return $events;
    }

    public function __destruct() {
        \db\DBPool::get_instance()->release_connection($this->db_connection);
    }
}