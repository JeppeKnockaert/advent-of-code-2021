<?php

namespace App\Console\Commands\Tests;

use function abs;
use function array_intersect;
use function array_key_exists;
use function array_map;
use function array_search;
use function collect;
use function count;
use function explode;
use function implode;
use function preg_match;
use function sort;
use function str_contains;

class Test19b implements Test
{
    private int $reportCount;

    private array $scannerReports = [];
    private array $sortedDistanceReports = [];
    private array $realDistanceReports = [];
    private array $scannerPositions = [];

    public function getResult(array $inputs): int
    {
        $scannerReport = -1;
        foreach ($inputs as $input) {
            $matches = [];
            if (preg_match('/--- scanner (\d+) ---/', $input, $matches)) {
                $scannerReport = (int) $matches[1];
            }
            else if (str_contains($input, ',')) {
                $this->scannerReports[$scannerReport][] = explode(',', $input);
            }
        }

        $this->reportCount = count($this->scannerReports);


        $this->scannerPositions[0] = [0, 0, 0];
        foreach ($this->scannerReports as $reportNumber => $scanReport) {
            $this->getDistancesForReport($reportNumber, $scanReport);
        }
        $this->findMatchingReport(0);

        $minDistance = 0;
        for ($i = 0; $i < $this->reportCount; $i++) {
            for ($j = $i + 1; $j < $this->reportCount; $j++) {
                $distance = $this->getDistance($this->scannerPositions[$i], $this->scannerPositions[$j]);
                $minDistance = max($distance, $minDistance);
            }
        }

        return $minDistance;
    }

    private function findMatchingReport(int $reportNumber): void
    {
        $distanceReports = $this->sortedDistanceReports[$reportNumber];

        for ($i = 0; $i < $this->reportCount; $i++) {
            if (array_key_exists($i, $this->scannerPositions) || $i === $reportNumber) {
                continue;
            }

            $matchingDistances = array_intersect($distanceReports, $this->sortedDistanceReports[$i]);
            $matchingBeacons = collect($matchingDistances)->keys()->flatMap(fn ($key) => explode('#', $key))->unique()->values();

            if ($matchingBeacons->count() >= 12) {
                $this->orientTowardsZero($matchingDistances, $reportNumber, $i);
                $this->findMatchingReport($i);
            }
        }
    }

    private function orientTowardsZero(array $matchingDistances, int $referenceReportNumber, int $reportNumber): void
    {
        $pairs = array_keys($matchingDistances);
        $firstPair = null;
        $secondPair = null;
        foreach ($pairs as $pair) {
            $explodedPair = explode('#', $pair);
            if ($firstPair !== null && ($explodedPair[0] === $firstPair[0] || $explodedPair[1] === $firstPair[1])) {
                $secondPair = $explodedPair;
                break;
            }
            $firstPair ??= $explodedPair;
        }

        $matchingDistance = $matchingDistances[implode('#', $firstPair)];
        $matchingDistance2 = $matchingDistances[implode('#', $secondPair)];
        $matchingPair = array_search($matchingDistance, $this->sortedDistanceReports[$reportNumber]);
        $matchingPair2 = array_search($matchingDistance2, $this->sortedDistanceReports[$reportNumber]);

        $beaconIndexes = explode('#', $matchingPair);
        $beaconIndexes2 = explode('#', $matchingPair2);

        $pairIndex = array_values(array_intersect($firstPair, $secondPair))[0];
        $matchingPairIndex = array_values(array_intersect($beaconIndexes, $beaconIndexes2))[0];

        $distance = $this->realDistanceReports[$referenceReportNumber][implode('#', $firstPair)];
        $otherDistance = $this->realDistanceReports[$reportNumber][$matchingPair];

        $xPos = array_search($distance[0], $otherDistance);
        $yPos = array_search($distance[1], $otherDistance);
        $zPos = array_search($distance[2], $otherDistance);

        $beaconPosition = $this->scannerReports[$referenceReportNumber][$pairIndex];
        $otherBeaconPosition = $this->scannerReports[$reportNumber][$matchingPairIndex];

        $position[0] = $beaconPosition[0] - $otherBeaconPosition[$xPos];
        $position[1] = $beaconPosition[1] - $otherBeaconPosition[$yPos];
        $position[2] = $beaconPosition[2] - $otherBeaconPosition[$zPos];

        $xValues = [];
        $referenceXValues = array_map(fn ($value) => $value[0], $this->scannerReports[$referenceReportNumber]);
        $referenceYValues = array_map(fn ($value) => $value[1], $this->scannerReports[$referenceReportNumber]);
        $referenceZValues = array_map(fn ($value) => $value[2], $this->scannerReports[$referenceReportNumber]);
        foreach ($this->scannerReports[$reportNumber] as $beacon) {
            $xValues[] = $beacon[$xPos] + $position[0];
            $yValues[] = $beacon[$yPos] + $position[1];
            $zValues[] = $beacon[$zPos] + $position[2];
        }

        $xFactor =  count(array_intersect($referenceXValues, $xValues)) < 12 ? -1 : 1;
        $yFactor =  count(array_intersect($referenceYValues, $yValues)) < 12 ? -1 : 1;
        $zFactor =  count(array_intersect($referenceZValues, $zValues)) < 12 ? -1 : 1;

        $position[0] = $beaconPosition[0] - $xFactor*$otherBeaconPosition[$xPos];
        $position[1] = $beaconPosition[1] - $yFactor*$otherBeaconPosition[$yPos];
        $position[2] = $beaconPosition[2] - $zFactor*$otherBeaconPosition[$zPos];

        foreach ($this->scannerReports[$reportNumber] as $beaconIndex => $beacon) {
            $this->scannerReports[$reportNumber][$beaconIndex] = [
                $xFactor*$beacon[$xPos] + $position[0],
                $yFactor*$beacon[$yPos] + $position[1],
                $zFactor*$beacon[$zPos] + $position[2],
            ];
        }

        $this->scannerPositions[$reportNumber] = $position;
        $this->getDistancesForReport($reportNumber, $this->scannerReports[$reportNumber]);
    }

    private function getDistancesForReport(int $reportNumber, array $scanReport): void
    {
        $distances = [];
        $realDistances = [];
        $beaconCount = count($scanReport);

        foreach ($scanReport as $i => $beacon) {
            for ($j = $i + 1; $j < $beaconCount; $j++) {
                $sortedDistances = [
                    0 => abs($beacon[0] - $scanReport[$j][0]),
                    1 => abs($beacon[1] - $scanReport[$j][1]),
                    2 => abs($beacon[2] - $scanReport[$j][2]),
                ];
                sort($sortedDistances);

                $distances[$i . '#' . $j] = implode(',', $sortedDistances);
                $realDistances[$i . '#' . $j] = [
                    abs($beacon[0] - $scanReport[$j][0]),
                    abs($beacon[1] - $scanReport[$j][1]),
                    abs($beacon[2] - $scanReport[$j][2]),
                ];
            }
        }

        $this->sortedDistanceReports[$reportNumber] = $distances;
        $this->realDistanceReports[$reportNumber] = $realDistances;
    }

    private function getDistance(array $scanner1, array $scanner2): int
    {
        return abs($scanner1[0] - $scanner2[0]) + abs($scanner1[1] - $scanner2[1]) + abs($scanner1[2] - $scanner2[2]);
    }
}
