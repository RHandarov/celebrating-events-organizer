<?php

namespace models;

class Date {
    private string $date;
    private string $title;

    public function __construct(string $date, string $title) {
        $this->date = $date;
        $this->title = $title;
    }

    public function get_date(): string {
        return $this->date;
    }

    public function get_title(): string {
        return $this->title;
    }
}