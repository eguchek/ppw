<?php

namespace PPW;

class GoogleMapsApi
{
    private $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function getNearestStop(Point $fromPoint): Point
    {

    }

    public function getWalkingRoute(Point $fromPoint, Point $toPoint): Point
    {

    }

    public function getTransportationRoute(Point $fromPoint, Point $toPoint): Point
    {

    }

    private function doRequest()
    {

    }

}