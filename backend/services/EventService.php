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
        string $title,
        string $location,
        string $description,
        array &$errors): ?\models\Event {
        $title = htmlspecialchars(trim($title));
        $location = htmlspecialchars(trim($location));
        $description = htmlspecialchars(trim($description));

        if ($this->is_organizer_the_same_as_celebrant($organizer, $date)) {
            array_push($errors,
                "Не може да организираш парти на себе си!");

            return null;
        }

        if (!$this->validate_title($title, $errors)) {
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

        $event = $this->event_repository->add_event(
            $this->get_actual_celebrating_date_as_string($date),
            $organizer,
            $date->get_owner(),
            $title,
            $location,
            $description
        );

        $errors = [];
        $this->add_guest_to_event($organizer, $event, $errors);

        return $event;
    }

    public function find_event_by_id(int $event_id): ?\models\Event {
        return $this->event_repository->find_event_by_id($event_id);
    }

    public function change_event(\models\Event $changed_event, array &$errors): ?\models\Event {
        if (!$this->validate_title($changed_event->get_title(), $errors)) {
            return null;
        }

        if (!$this->validate_location($changed_event->get_location(), $errors)) {
            return null;
        }

        return $this->event_repository->change_event($changed_event);
    }

    private function is_organizer_the_same_as_celebrant(\models\User $organizer, \models\Date $date): bool {
        return $organizer->get_id() === $date->get_owner()->get_id();
    }

    private function validate_title(string $title, array &$errors): bool {
        if ($title === "") {
            array_push($errors,
                "Заглавието не бива да е празно!");

            return false;
        }

        $title_length = mb_strlen($title);

        if ($title_length > 256) {
            array_push($errors,
                "Дължината на заглавието трябва да е не повече от 256 символа!");

            return false;
        }

        return true;
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

    private function get_actual_celebrating_date_as_string(\models\Date $date): string {
        return date("Y-m-d", $this->get_actual_celebrating_date($date));
    }

    // Changes year of the date and returns it as a timestamp to compore woth
    // the current moment.
    private function get_actual_celebrating_date(\models\Date $date): int {
        $day_and_month = date("m-d", strtotime($date->get_date()));
        return strtotime(date("Y") . "-" . $day_and_month);
    }

    public function get_all_guests_of_event(\models\Event $event): array {
        return $this->event_repository->get_all_guests_for_event($event);
    }

    public function add_guest_to_event(\models\User $guest, \models\Event $event, array &$errors): void {
        if (!$this->could_user_be_guest_in($guest, $event)) {
            array_push($errors,
                "Потребителят " . $guest->get_username() . " не следва " .
                $event->get_organized()->get_username() .
                ". Затова не може да бъде гост на събитието!");

            return;
        }

        $this->event_repository->save_user_as_guest($guest, $event);
    }

    public function delete_guest_from_event(\models\User $guest, \models\Event $event, array &$errors): void {
        if (!$this->event_repository->is_user_already_guest($guest, $event)) {
            array_push($errors,
                "Потребителят " . $guest->get_username() . " не е гост на събитието!");

            return;
        }

        if ($guest->get_id() === $event->get_organizer()->get_id()) {
            array_push($errors,
                "Потребителят " . $guest->get_username() . " е организатор на събитието!");

            return;
        }

        $this->event_repository->delete_guest_from_event($guest, $event);
    }

    private function could_user_be_guest_in(\models\User $user, \models\Event $event): bool {
        $possible_events = $this->get_all_organizing_events_for_user($user);

        $found = false;
        foreach ($possible_events as $possible_event) {
            if ($event->get_id() === $possible_event->get_id()) {
                $found = true;
                break;
            }
        }

        return $found;
    }

    public function get_all_organizing_events_for_user(\models\User $user): array {
        $events = [];

        $followed_users = $this->user_service->get_all_followed_of_user($user);
        foreach ($followed_users as $followed_user) {
            $events = array_merge($events, $this->event_repository->get_all_events_organized_for($followed_user));
        }

        return $events;
    }

    public function add_gift(
        \models\Event $event,
        \models\User $assigned_guest,
        string $description,
        array &$errors
    ): ?\models\Gift {
        $description = htmlspecialchars(trim($description));

        if (!$this->validate_description($description, $errors)) {
            return null;
        }

        if (!$this->is_user_guest_of_event($assigned_guest, $event)) {
            array_push($errors,
                "Потребителят " . $assigned_guest->get_username() .
                " не е гост на събитието!");

            return null;
        }

        return $this->event_repository->add_gift_to_event(
            $event,
            $assigned_guest,
            $description
        );
    }

    public function get_all_gifts_of_event(\models\Event $event): array {
        return $this->event_repository->get_all_gifts_of_event($event);
    }

    public function change_gift(\models\Gift $gift, array &$errors): ?\models\Gift {
        if (!$this->validate_description($gift->get_description(), $errors)) {
            return null;
        }

        return $this->event_repository->change_gift($gift);
    }

    public function delete_gift(\models\Gift $gift): true {
        $this->event_repository->delete_gift_from_event($gift);
        return true;
    }

    private function validate_description(string $description, array &$errors): bool {
        if ($description === "") {
            array_push($errors,
                "Описанието на подаръка не може да е празно!");

            return false;
        }

        $description_length = mb_strlen($description);
        if ($description_length > 512) {
            array_push($errors,
                "Дължината на описанието не може да надхвърля 512 символа!");

            return false;
        }

        return true;
    }

    private function is_user_guest_of_event(\models\User $user, \models\Event $event): bool {
        return $this->event_repository->is_user_already_guest($user, $event);
    }

    public function __destruct() {
        \db\DBPool::get_instance()->release_connection($this->db_connection);
    }
}
