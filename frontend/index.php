<?php

require_once("../backend/Loader.php");
spl_autoload_register("\Loader::load");

session_set_cookie_params(0,
    "/",
    null,
    null,
    true);
session_start([
    "cookie_httponly" => "1"
]);

Router::run();
