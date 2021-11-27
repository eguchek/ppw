<?php

use PPW\Config;
use PPW\GoogleMapsApi;
use PPW\Helper;
use PPW\MinMaxApi;
use PPW\Point;

$fromPoint = Point::fromInput($_GET['from']);
$toPoint = Point::fromInput($_GET['to']);

$minMaxApi = new MinMaxApi();
$googleMaps = new GoogleMapsApi(Config::getGoogleApiKey());

$localMin = $minMaxApi->getLocalMin($fromPoint);
$localMax = $minMaxApi->getLocalMax($toPoint);

$nearestStopFrom = $googleMaps->getNearestStop($localMin);
$nearestStopTo = $googleMaps->getNearestStop($localMax);

$walkingToTransport = $googleMaps->getWalkingRoute($fromPoint, $nearestStopFrom);
$transportRoute = $googleMaps->getTransportationRoute($nearestStopFrom, $nearestStopTo);
$walkingToTransport = $googleMaps->getWalkingRoute($nearestStopTo, $toPoint);

echo json_encode(Helper::combineRoutes($walkingToTransport, $transportRoute, $walkingToTransport));
