<?php

namespace PPW;

class MinMaxApi
{
    private $apiUrl;

    public function __construct($apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }

    public function getLocalMin(Point $from): Point
    {
        $res = $this->doRequest('min', $from);

        $lastRecord = array_pop($res['path']);

        return $this->getPointFromRecord($lastRecord);
    }

    public function getLocalMax(Point $from): Point
    {
        // todo: implement MAX api
        $res = $this->doRequest('min', $from);

        $lastRecord = array_pop($res['path']);

        return $this->getPointFromRecord($lastRecord);
    }

    private function doRequest(string $method, Point $point)
    {
        $url = $this->apiUrl . '/' . $method . '/?lat=' . $point->lat . '&lng=' . $point->lon;

        $rawResult = file_get_contents($url);

        $result = json_decode($rawResult, true);

        if (!$result) {
            throw new \RuntimeException('MinMax api request returned non-json response:' . $rawResult);
        }

        return $result;
    }

    private function getPointFromRecord($record): Point
    {
        return new Point($record['latitude'], $record['longitude']);
    }
}