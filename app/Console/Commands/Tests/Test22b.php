<?php

namespace App\Console\Commands\Tests;

use function max;
use function min;
use function preg_match;
use function str_starts_with;

class Test22b implements Test
{
    public function getResult(array $inputs): int
    {
        $onRegions = [];

        foreach ($inputs as $input) {
            $turnOn = str_starts_with($input, 'on');
            $newRange = $this->getRangeForLine($input);
            $newRegions = $turnOn ? [[$newRange]] : [];

            foreach ($onRegions as $range) {
                $newRegions[] = $this->rangesHaveOverlap($range, $newRange) ? $this->splitRangeInRegions($range, $newRange) : [$range];
            }

            $onRegions = array_merge(...$newRegions);
        }

        return $this->countCoveredCoordinates($onRegions);
    }

    private function getRangeForLine(string $line): array
    {
        preg_match('/x=([\-\d]+)\.\.([\-\d]+),y=([\-\d]+)\.\.([\-\d]+),z=([\-\d]+)\.\.([\-\d]+)/', $line, $matches);

        return array_slice($matches, 1);
    }

    private function countCoveredCoordinates(array $onRegions): int
    {
        $count = 0;
        foreach ($onRegions as $onRegion) {
            $count += ($onRegion[1] - $onRegion[0] + 1) * ($onRegion[3] - $onRegion[2] + 1) * ($onRegion[5] - $onRegion[4] + 1);
        }

        return $count;
    }

    private function rangesHaveOverlap(array $range, array $otherRange): bool
    {
        return $this->coordinatesHaveOverlap($range[0], $range[1], $otherRange[0], $otherRange[1]) &&
            $this->coordinatesHaveOverlap($range[2], $range[3], $otherRange[2], $otherRange[3]) &&
            $this->coordinatesHaveOverlap($range[4], $range[5], $otherRange[4], $otherRange[5]);
    }

    private function coordinatesHaveOverlap(int $coordinateFrom, int $coordinateTo, int $otherCoordinateFrom, int $otherCoordinateTo): bool
    {
        return $otherCoordinateFrom <= $coordinateTo && $otherCoordinateTo >= $coordinateFrom;
    }

    private function splitRangeInRegions(array $range, array $otherRange): array
    {
        $newRegions = [];
        $hasUp = false;
        $hasDown = false;
        $hasLeft = false;
        $hasRight = false;

        // Up
        if ($otherRange[3] + 1 <= $range[3]) {
            $hasUp = true;
            $newRegions[] = [
                $range[0],
                $range[1],
                $otherRange[3] + 1,
                $range[3],
                $range[4],
                $range[5],
            ];
        }

        // Down
        if ($range[2] <= $otherRange[2] - 1) {
            $hasDown = true;
            $newRegions[] = [
                $range[0],
                $range[1],
                $range[2],
                $otherRange[2] - 1,
                $range[4],
                $range[5],
            ];
        }

        // Right
        if ($otherRange[1] + 1 <= $range[1]) {
            $hasRight = true;
            $newRegions[] = [
                $otherRange[1] + 1,
                $range[1],
                max($range[2], $otherRange[2] - 1) + ($hasDown ? 1 : 0),
                min($range[3], $otherRange[3] + 1) - ($hasUp ? 1 : 0),
                $range[4],
                $range[5],
            ];
        }

        // Left
        if ($range[0] <= $otherRange[0] - 1) {
            $hasLeft = true;
            $newRegions[] = [
                $range[0],
                $otherRange[0] - 1,
                max($range[2], $otherRange[2] - 1) + ($hasDown ? 1 : 0),
                min($range[3], $otherRange[3] + 1) - ($hasUp ? 1 : 0),
                $range[4],
                $range[5],
            ];
        }

        //Behind
        if ($otherRange[5] + 1 <= $range[5]) {
            $newRegions[] = [
                max($range[0], $otherRange[0] - 1) + ($hasLeft ? 1 : 0),
                min($range[1], $otherRange[1] + 1) - ($hasRight ? 1 : 0),
                max($range[2], $otherRange[2] - 1) + ($hasDown ? 1 : 0),
                min($range[3], $otherRange[3] + 1) - ($hasUp ? 1 : 0),
                $otherRange[5] + 1,
                $range[5],
            ];
        }

        //Before
        if ($range[4] <= $otherRange[4] - 1) {
            $newRegions[] = [
                max($range[0], $otherRange[0] - 1) + ($hasLeft ? 1 : 0),
                min($range[1], $otherRange[1] + 1) - ($hasRight ? 1 : 0),
                max($range[2], $otherRange[2] - 1) + ($hasDown ? 1 : 0),
                min($range[3], $otherRange[3] + 1) - ($hasUp ? 1 : 0),
                $range[4],
                $otherRange[4] - 1,
            ];
        }

        return $newRegions;
    }
}
