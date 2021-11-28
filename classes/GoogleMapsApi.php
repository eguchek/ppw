<?php

namespace PPW;

use function http_build_url;

class GoogleMapsApi
{
    private $key;
    private $apiUrl = 'https://maps.googleapis.com/maps/api/';

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function getNearestStop(Point $fromPoint): Point
    {
        $method = 'place/nearbysearch';
        $params = [
            'location' => $fromPoint->lat . ',' . $fromPoint->lon,
            // 'sensor' => 'true',
            'key' => $this->key,
            'rankby' => 'distance',
            'types' => 'transit_station',
        ];

        $result = $this->doRequest($method, $params);

        $point = $this->retrieveNearestStop($result);

        return $point;
    }

    private function retrieveNearestStop($requestData): Point
    {
        $results = $requestData['results'];
        $topResult = array_pop($results);

        $latlon = $topResult['geometry']['location'];

        return new Point($latlon['lat'], $latlon['lng']);
    }

    public function getWalkingRoute(Point $fromPoint, Point $toPoint): array
    {
        return $this->getRoute($fromPoint, $toPoint, 'walking');
    }

    public function getTransportationRoute(Point $fromPoint, Point $toPoint): array
    {
        return $this->getRoute($fromPoint, $toPoint, 'transit');
    }

    private function getRoute(Point $fromPoint, Point $toPoint, string $mode): array
    {
        $method = 'directions';
        $params = [
            'origin' => $fromPoint->lat . ',' . $fromPoint->lon,
            'destination' => $toPoint->lat . ',' . $toPoint->lon,
            'key' => $this->key,
            'mode' => $mode
        ];

        $result = $this->doRequest($method, $params);

        return $result;
    }

    private function doRequest($method, $params)
    {
        $baseUrl = $this->apiUrl . $method . '/json';

        $url = $baseUrl . '?' . $this->prepareParams($params);

        $rawResult = file_get_contents($url);

        $result = json_decode($rawResult, true);

        if (!$result) {
            $msg = sprintf('Google Maps %s api request returned non-json response: %s', $method, $rawResult);
            throw new \RuntimeException($msg);
        }

        return $result;
    }

    private function prepareParams($params): string
    {
        $paramStrings = [];

        foreach ($params as $paramName => $paramValue) {
            $paramStrings[] = $paramName . '=' . $paramValue;
        }

        return implode('&', $paramStrings);
    }

}