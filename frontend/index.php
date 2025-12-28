<?php

if ($_SERVER["REQUEST_URI"] === "/favicon.ico") {
    exit; // for now
}

require_once("../backend/Loader.php");
spl_autoload_register("\Loader::load");

SessionManager::start();
Router::run();
