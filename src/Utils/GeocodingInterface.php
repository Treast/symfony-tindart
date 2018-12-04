<?php

namespace App\Utils;

interface GeocodingInterface {
    public function geocode(string $address);
    public function reverseGeocode(float $latitude, float $longitude);
    public function distance(float $latitude1, float $longitude1, float $latitude2, float $longitude2);
}