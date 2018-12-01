<?php

namespace App\Service;

use App\Utils\GeocodingInterface;
use Geocoder\Exception\Exception;
use Geocoder\Provider\Nominatim\Nominatim;
use Geocoder\Query\GeocodeQuery;
use Geocoder\StatefulGeocoder;
use Http\Adapter\Guzzle6\Client;
use Http\Client\HttpClient;
use Symfony\Component\Intl\Exception\NotImplementedException;

class OpenStreetMapGeocoder implements GeocodingInterface {

    /** @var HttpClient */
    private $httpClient;
    /** @var Nominatim */
    private $provider;
    /** @var StatefulGeocoder */
    private $geocoder;

    public function __construct()
    {
        $this->httpClient = new Client();
        $this->provider = new Nominatim($this->httpClient, 'https://nominatim.openstreetmap.org', 'fr');
        $this->geocoder = new StatefulGeocoder($this->provider, 'fr');
    }

    /**
     * Transform an address to a longitude and a latitude.
     * @param string $address
     * @return \Geocoder\Model\Coordinates|null
     */
    public function geocode(string $address)
    {
        try {
            $query = GeocodeQuery::create($address);
            $result = $this->geocoder->geocodeQuery($query);
            return $result->first()->getCoordinates();
        } catch (Exception $e) {
            return null;
        }
    }

    public function reverseGeocode(float $latitude, float $longitude)
    {
        throw new NotImplementedException('Not implemented yet.');
    }
}