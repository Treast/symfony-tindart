<?php

namespace App\Utils;

interface GeocodingInterface {
    public function geocode(string $address);
    public function reverseGeocode(float $latitude, float $longitude);
}