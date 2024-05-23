<?php

namespace App\Classes;

class Jug {
    // property declaration
    public int $gallons_measurement;
    public Int $state;

    function __construct(
        int $gallons_measurement,
        int $state = 0
    ) {
        $this->gallons_measurement = $gallons_measurement;
        $this->state = $state;
    }

    // method declaration
    public function fill(
        int $quantity
    ) {
        $this->state = $this->state + $quantity;
    }

    public function fillTop() {
        $this->state = $this->gallons_measurement;
    }

    public function empty() {
        $this->state = 0;
    }
}