<?php

final class Loader {
    public static function load(string $class_name): void {
        $full_path = __DIR__ . "/" . $class_name . ".php";
        $full_path = str_replace("/", DIRECTORY_SEPARATOR, $full_path);
        $full_path = str_replace("\\", DIRECTORY_SEPARATOR, $full_path);

        require_once $full_path;
    }

    private function __construct() {

    }

    private function __clone() {

    }
}