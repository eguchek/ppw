<?php

namespace PPW;

class Point
{
    public $lat;
    public $lon;

    public function __construct($lat, $lon)
    {
        $this->lat = $lat;
        $this->lon = $lon;
    }

    public static function fromInput($coordinates): Point
    {
        list($lon, $lat) = explode(',', $coordinates);
        return new self($lat, $lon);
    }

}