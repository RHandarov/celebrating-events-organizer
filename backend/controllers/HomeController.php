<?php

namespace controllers;

class HomeController {
    public function show_home_page(): void {
        include("templates/header.php");
        include("templates/footer.php");
    }
}