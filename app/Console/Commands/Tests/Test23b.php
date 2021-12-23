<?php

namespace App\Console\Commands\Tests;

class Test23b implements Test
{
    private array $positions = [
        'A' => 0,
        'B' => 1,
        'C' => 2,
        'D' => 3,
    ];

    private array $costs = [
        'A' => 1,
        'B' => 10,
        'C' => 100,
        'D' => 1000,
    ];

    private array $podCache = ['A%A%A%A#B%B%B%B#C%C%C%C#D%D%D%D$##########' => 0];

    public function getResult(array $inputs): int
    {
        $hallWay = [];
        for ($i = 0; $i <= 10; $i++) {
            $hallWay[$i] = null;
        }

        preg_match('/([A-D])#([A-D])#([A-D])#([A-D])#/', $inputs[2], $matches);
        preg_match('/([A-D])#([A-D])#([A-D])#([A-D])#/', '#D#C#B#A#', $matches2);
        preg_match('/([A-D])#([A-D])#([A-D])#([A-D])#/', '#D#B#A#C#', $matches3);
        preg_match('/([A-D])#([A-D])#([A-D])#([A-D])#/', $inputs[3], $matches4);
        $rooms = [
            [$matches[1], $matches2[1], $matches3[1], $matches4[1]],
            [$matches[2], $matches2[2], $matches3[2], $matches4[2]],
            [$matches[3], $matches2[3], $matches3[3], $matches4[3]],
            [$matches[4], $matches2[4], $matches3[4], $matches4[4]],
        ];

        return $this->movePods($rooms, $hallWay, 0);
    }

    private function movePods(array $rooms, array $hallWay, int $currentTotal): int
    {
        $minRemainingCost = -1;

        $cacheKey = $this->getCacheKey($rooms, $hallWay);
        if (array_key_exists($cacheKey, $this->podCache)) {
            $remainingCost = $this->podCache[$cacheKey];

            return $remainingCost >= 0 ? $currentTotal + $remainingCost : -1;
        }

        // Move pods in hallway to rooms.
        $podsInHallWay = $this->getPodsInHallWay($hallWay);
        foreach ($podsInHallWay as $hallWayIndex) {
            $podType = $hallWay[$hallWayIndex];
            $destinationRoom = $this->positions[$podType];
            $destinationHallWayIndex = ($destinationRoom + 1) * 2;
            $destinationIndex = $this->getDestinationIndex($rooms, $podType);

            if ($destinationIndex !== null && $this->isHallWayFree($podsInHallWay, $hallWayIndex, $destinationHallWayIndex, $hallWayIndex)) {
                $newPods = $rooms;
                $newHallWay = $hallWay;

                $destinationRoom = $this->positions[$podType];
                $newPods[$destinationRoom][$destinationIndex] = $podType;
                $newHallWay[$hallWayIndex] = null;

                $moveCost = ($destinationIndex + 1 + abs($hallWayIndex - $destinationHallWayIndex)) * $this->costs[$podType];
                $remainingCost = $this->movePods($newPods, $newHallWay, $currentTotal + $moveCost);
                $minRemainingCost = $remainingCost < 0 ? $minRemainingCost : ($minRemainingCost === -1 ? $remainingCost : min($minRemainingCost, $remainingCost));
            }
        }

        // Move pods in rooms to other rooms
        $outOfPlacePods = $this->getOutOfPlacePods($rooms);
        foreach ($outOfPlacePods as $nextPod) {
            $podType = $rooms[$nextPod[0]][$nextPod[1]];
            $destinationRoom = $this->positions[$podType];
            $hallWayIndex = ($nextPod[0] + 1) * 2;
            $destinationHallWayIndex = ($destinationRoom + 1) * 2;
            $destinationIndex = $this->getDestinationIndex($rooms, $podType);

            if ($destinationIndex !== null && $this->isHallWayFree($podsInHallWay, $hallWayIndex, $destinationHallWayIndex)) {
                $newPods = $rooms;

                $destinationRoom = $this->positions[$podType];
                $newPods[$nextPod[0]][$nextPod[1]] = null;
                $newPods[$destinationRoom][$destinationIndex] = $podType;

                $moveCost = ($nextPod[1] + $destinationIndex + 2 + abs($hallWayIndex - $destinationHallWayIndex)) * $this->costs[$podType];
                $remainingCost = $this->movePods($newPods, $hallWay, $currentTotal + $moveCost);
                $minRemainingCost = $remainingCost < 0 ? $minRemainingCost : ($minRemainingCost === -1 ? $remainingCost : min($minRemainingCost, $remainingCost));
            }
        }

        // Move pods in rooms to hallway
        foreach ($outOfPlacePods as $nextPod) {
            foreach ([0, 1, 3, 5, 7, 9, 10] as $position) {
                $hallWayIndex = ($nextPod[0] + 1) * 2;
                $destinationHallWayIndex = $position;

                if ($this->isHallWayFree($podsInHallWay, $hallWayIndex, $destinationHallWayIndex)) {
                    $podType = $rooms[$nextPod[0]][$nextPod[1]];
                    $newPods = $rooms;
                    $newHallWay = $hallWay;

                    $newPods[$nextPod[0]][$nextPod[1]] = null;
                    $newHallWay[$position] = $podType;

                    $moveCost = ($nextPod[1] + 1 + abs($hallWayIndex - $destinationHallWayIndex)) * $this->costs[$podType];
                    $remainingCost = $this->movePods($newPods, $newHallWay, $currentTotal + $moveCost);
                    $minRemainingCost = $remainingCost < 0 ? $minRemainingCost : ($minRemainingCost === -1 ? $remainingCost : min($minRemainingCost, $remainingCost));
                }
            }
        }

        $this->podCache[$cacheKey] = $minRemainingCost > 0 ? $minRemainingCost - $currentTotal : $minRemainingCost;

        return empty($outOfPlacePods) && empty($podsInHallWay) ? $currentTotal : $minRemainingCost;
    }

    private function getCacheKey(array $rooms, array $hallWay): string
    {
        return implode('#', array_map(fn($room) => implode('%', $room), $rooms)) . '$' . implode('#', $hallWay);
    }

    private function isHallWayFree(array $podsInHallWay, int $position1, int $position2, int $ownPosition = null): bool
    {
        if (empty($podsInHallWay)) {
            return true;
        }

        $startPosition = min($position1, $position2);
        $endPosition = max($position1, $position2);

        foreach ($podsInHallWay as $podIndex) {
            if ($podIndex !== $ownPosition && $podIndex >= $startPosition && $podIndex <= $endPosition) {
                return false;
            }
        }

        return true;
    }

    private function getOutOfPlacePods(array $rooms): array
    {
        $podsOutOfPlace = [];
        foreach ($rooms as $position => $room) {
            $pod0OutOfPlace = $room[0] !== null && $this->positions[$room[0]] !== $position;
            $pod1OutOfPlace = $room[1] !== null && $this->positions[$room[1]] !== $position;
            $pod2OutOfPlace = $room[2] !== null && $this->positions[$room[2]] !== $position;
            $pod3OutOfPlace = $room[3] !== null && $this->positions[$room[3]] !== $position;

            if ($pod0OutOfPlace || $pod1OutOfPlace || $pod2OutOfPlace || $pod3OutOfPlace) {
                if ($room[0] !== null && ($pod0OutOfPlace || $pod1OutOfPlace || $pod2OutOfPlace || $pod3OutOfPlace)) {
                    $podsOutOfPlace[] = [$position, 0];
                } else if ($room[1] !== null && ($pod1OutOfPlace || $pod2OutOfPlace || $pod3OutOfPlace)) {
                    $podsOutOfPlace[] = [$position, 1];
                } else if ($room[2] !== null && ($pod2OutOfPlace || $pod3OutOfPlace)) {
                    $podsOutOfPlace[] = [$position, 2];
                } else {
                    $podsOutOfPlace[] = [$position, 3];
                }
            }
        }
        return $podsOutOfPlace;
    }

    private function getPodsInHallWay(array $hallWay): array
    {
        return array_keys(array_filter($hallWay));
    }

    private function getDestinationIndex(array $rooms, string $podType): ?int
    {
        $destinationRoom = $this->positions[$podType];
        $room = array_reverse($rooms[$destinationRoom]);

        foreach ($room as $position => $pod) {
            if ($pod === null) {
                return 3 - $position;
            }

            if ($pod !== $podType) {
                return null;
            }
        }

        return null;
    }
}
