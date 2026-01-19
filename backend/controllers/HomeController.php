<?php

namespace controllers;

class HomeController {
    public function show_home_page(array $params): void {
        include("templates/header.php");
        include("templates/footer.php");
    }
}
