<?php

namespace controllers;

use SessionManager;

class GiftController {
    private \services\EventService $event_service;

    public function __construct(\services\EventService $event_service) {
        $this->event_service = $event_service;
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