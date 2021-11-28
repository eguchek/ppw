<?php

use PPW\Config;
use PPW\GoogleMapsApi;
use PPW\Helper;
use PPW\MinMaxApi;
use PPW\Point;

require __DIR__ . '/../vendor/autoload.php';
//echo '[{"lat":50.1048675,"lon":14.475787,"mode":"WALKING","line":""},{"lat":50.10465989999999,"lon":14.4746806,"mode":"WALKING","line":""},{"lat":50.1041317,"lon":14.4747262,"mode":"WALKING","line":""},{"lat":50.1035208,"lon":14.4727763,"mode":"WALKING","line":""},{"lat":50.10337190000001,"lon":14.4587238,"mode":"WALKING","line":""},{"lat":50.07854409999999,"lon":14.4577601,"mode":"WALKING","line":""},{"lat":50.0785662,"lon":14.4580098,"mode":"WALKING","line":""},{"lat":50.081816,"lon":14.4567329,"mode":"WALKING","line":""},{"lat":50.082028,"lon":14.4579844,"mode":"WALKING","line":""},{"lat":50.08244999999999,"lon":14.45774,"mode":"TRANSIT","line":"95"},{"lat":50.10449999999999,"lon":14.47317,"mode":"TRANSIT_END","line":""},{"lat":50.1045017,"lon":14.4731299,"mode":"WALKING","line":""},{"lat":50.1042207,"lon":14.4731835,"mode":"WALKING","line":""},{"lat":50.1043366,"lon":14.474626,"mode":"WALKING","line":""},{"lat":50.10465989999999,"lon":14.4746806,"mode":"WALKING","line":""},{"lat":50.1048675,"lon":14.475787,"mode":"WALKING","line":""},{"lat":50.10465989999999,"lon":14.4746806,"mode":"WALKING","line":""},{"lat":50.1041317,"lon":14.4747262,"mode":"WALKING","line":""},{"lat":50.1035208,"lon":14.4727763,"mode":"WALKING","line":""},{"lat":50.10337190000001,"lon":14.4587238,"mode":"WALKING","line":""}]';
//die();
$_GET['from'] = '50.07906260302069, 14.454009429942156';
$_GET['to'] = '50.10332442690876, 14.4502353005806';

$fromPoint = Point::fromInput($_GET['from']);
$toPoint = Point::fromInput($_GET['to']);

$minMaxApi = new MinMaxApi(Config::getMinMaxApiUrl());
$googleMaps = new GoogleMapsApi(Config::getGoogleApiKey());

$localMin = $minMaxApi->getLocalMin($fromPoint);
$localMax = $minMaxApi->getLocalMax($toPoint);

$nearestStopFrom = $googleMaps->getNearestStop($localMin);
$nearestStopTo = $googleMaps->getNearestStop($localMax);

$walkingToTransport = $googleMaps->getWalkingRoute($fromPoint, $nearestStopFrom);
$transportRoute = $googleMaps->getTransportationRoute($nearestStopFrom, $nearestStopTo);
$walkingToTransport = $googleMaps->getWalkingRoute($nearestStopTo, $toPoint);

$res = json_encode(Helper::combineRoutes($walkingToTransport, $transportRoute, $walkingToTransport));

header('Content-type: application/json');
echo $res;
