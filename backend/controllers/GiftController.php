<?php

namespace controllers;

use SessionManager;

class GiftController {
    private \services\EventService $event_service;
    private \services\UserService $user_service;

    public function __construct(
        \services\EventService $event_service,
        \services\UserService $user_service
    ) {
        $this->event_service = $event_service;
        $this->user_service = $user_service;
    }

    public function show_add_gift_form(array $params): void {
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

        if ($event === null) {
            header("Location: /all-events");
            exit;
        }

        $logged_user = $this->user_service->find_user_by_id(SessionManager::get_logged_user_id());

        if (!$this->is_user_guest_of_event($logged_user, $event)) {
            header("Location: /all-events");
            exit;
        }

        $edit_mode = false;

        include("templates/header.php");
        include("templates/gifts/gifts-form.php");
        include("templates/footer.php");
    }

    public function add_gift(array $params): void {
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

        if ($event === null) {
            header("Location: /all-events");
            exit;
        }

        $logged_user = $this->user_service->find_user_by_id(SessionManager::get_logged_user_id());

        if (!$this->is_user_guest_of_event($logged_user, $event)) {
            header("Location: /all-events");
            exit;
        }

        $errors = [];
        $new_gift = $this->event_service->add_gift($event, $logged_user, $_POST["description"], $errors);

        if ($new_gift === null) {
            $edit_mode = false;

            include("templates/header.php");
            include("templates/gifts/gifts-form.php");
            include("templates/footer.php");
        } else {
            header("Location: /event/" . $event_id);
            exit;
        }
    }

    private function is_user_guest_of_event(\models\User $user, \models\Event $event): bool {
        $all_guests = $this->event_service->get_all_guests_of_event($event);

        foreach ($all_guests as $guest) {
            if ($guest->get_id() === $user->get_id()) {
                return true;
            }
        }

        return false;
    }

    public function show_edit_gift_form(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: /login");
            exit;
        }

        if (count($params) === 0) {
            header("Location: /all-events");
            exit;
        }

        $gift_id = intval($params[0]);
        $gift = $this->event_service->find_gift_by_id($gift_id);

        if ($gift === null || $gift->get_assigned_guest()->get_id() !== SessionManager::get_logged_user_id()) {
            header("Location: /all-events");
            exit;
        }

        $edit_mode = true;

        include("templates/header.php");
        include("templates/gifts/gifts-form.php");
        include("templates/footer.php");
    }

    public function edit_gift(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: /login");
            exit;
        }

        if (count($params) === 0) {
            header("Location: /all-events");
            exit;
        }

        $gift_id = intval($params[0]);
        $gift = $this->event_service->find_gift_by_id($gift_id);

        if ($gift === null) {
            header("Location: /all-events");
            exit;
        }

        if ($gift->get_assigned_guest()->get_id() !== SessionManager::get_logged_user_id()) {
            header("Location: /event/" . $gift->get_event()->get_id());
            exit;
        }

        $old_description = $gift->get_description();
        $gift->set_description($_POST["description"]);

        $errors = [];
        $new_gift = $this->event_service->change_gift($gift, $errors);

        if ($new_gift === null) {
            $gift->set_description($old_description);

            $edit_mode = true;

            include("templates/header.php");
            include("templates/gifts/gifts-form.php");
            include("templates/footer.php");
        } else {
            header("Location: /event/" . $gift->get_event()->get_id());
            exit;
        }
    }

    public function delete_gift(array $params): void {
        if (!SessionManager::is_logged_in()) {
            header("Location: /login");
            exit;
        }

        if (count($params) === 0) {
            header("Location: /");
            exit;
        }

        $gift_id = intval($params[0]);
        $gift = $this->event_service->find_gift_by_id($gift_id);

        if ($gift === null || $gift->get_assigned_guest()->get_id() !== SessionManager::get_logged_user_id()) {
            header("Location: /");
            exit;
        }

        $this->event_service->delete_gift($gift);

        if (isset($_GET["back"])) {
            $url = "/event/" . $_GET["back"];
        } else {
            $url = "/all-events";
        }

        header("Location: " . $url);
        exit;
    }
}
