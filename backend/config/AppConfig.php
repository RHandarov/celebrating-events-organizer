<?php

namespace config;

class AppConfig {
    private static ?self $instance = null;

    public static function get_instance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private readonly array $properties;

    private function __construct() {
        $this->properties = require_once("config_properties.php");
    }

    public function __get($name): mixed {
        return $this->properties[$name];
    }

    private function __clone() {
        
    }
}