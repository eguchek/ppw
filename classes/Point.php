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
        list($lat, $lon) = explode(',', $coordinates);
        return new self(trim($lat), trim($lon));
    }

}