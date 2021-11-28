<?php

namespace PPW;

class Helper
{

    public static function combineRoutes($walkFrom, $transport, $walkTo): array
    {

        $walkFromSteps = self::extractSteps($walkFrom);
        $transportSteps = self::extractSteps($transport);
        $walkToSteps = self::extractSteps($walkTo);

        $steps = [...$walkFromSteps, ...$transportSteps, ...$walkToSteps];
        return $steps;
    }

    private static function extractSteps($route)
    {
        $result = [];
        $steps = $route['routes'][0]['legs'][0]['steps'];

        self::steps2arr($steps, $result);
            
        return $result;
    }

    private static function steps2arr($steps, &$result)
    {
        foreach ($steps as $step) {
            if (isset($step['steps'])) {
                self::steps2arr($step['steps'], $result);
            } else {
                $result[] = [
                    'lat' => $step['start_location']['lat'],
                    'lon' => $step['start_location']['lng'],
                    'mode' => $step['travel_mode'],
                    'line' => $step['transit_details']['line']['short_name'] ?? '',
                ];

                if ($step['travel_mode'] == 'TRANSIT') {
                    $result[] = [
                        'lat' => $step['end_location']['lat'],
                        'lon' => $step['end_location']['lng'],
                        'mode' => 'TRANSIT_END',
                        'line' => $step['line']['short_name'] ?? '',
                    ];
                }
            }
        }
    }
}