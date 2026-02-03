<?php

namespace controllers;

use SessionManager;

class HomeController {
    private \services\UserService $user_service;
    private \services\EventService $event_service;

    public function __construct(
        \services\UserService $user_service,
        \services\EventService $event_service
    ) {
        $this->user_service = $user_service;
        $this->event_service = $event_service;
    }

    public function show_home_page(array $params): void {
        include("frontend/templates/header.php");

        if (SessionManager::is_logged_in()) {
            $logged_user = $this->user_service->find_user_by_id(SessionManager::get_logged_user_id());
            $top_20_events = $this->event_service->get_most_recent_20_organizing_events_for_user($logged_user);
            $user_gifts = $this->event_service->get_all_gifts_of_user($logged_user);

            include("frontend/templates/home-page-logged.php");
        }
        
        include("frontend/templates/footer.php");
    }
}
