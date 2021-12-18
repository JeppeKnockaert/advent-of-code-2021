<?php

namespace App\Console\Commands\Tests\Y2015;

use App\Console\Commands\Tests\Test;
use function preg_match;
use function str_starts_with;

class Test6b implements Test
{
    public function getResult(array $inputs): int
    {
        $lights = [];

        for ($y = 0; $y < 1000; $y++) {
            $lights[$y] = [];
            for ($x = 0; $x < 1000; $x++) {
                $lights[$y][$x] = 0;
            }
        }

        foreach ($inputs as $input) {
            $matches = [];
            preg_match('/[a-z\ ]+(\d+),(\d+) through (\d+),(\d+)/', $input, $matches);

            if (str_starts_with($input, 'turn on')) {
                $lights = $this->updateLights($lights, $matches[1], $matches[2], $matches[3], $matches[4], fn($lightValue) => $lightValue + 1);
            } else if (str_starts_with($input, 'turn off')) {
                $lights = $this->updateLights($lights, $matches[1], $matches[2], $matches[3], $matches[4], fn($lightValue) => $lightValue > 0 ? $lightValue - 1 : 0);
            } else {
                $lights = $this->updateLights($lights, $matches[1], $matches[2], $matches[3], $matches[4], fn($lightValue) => $lightValue + 2);
            }
        }

        return $this->countLights($lights);
    }

    private function countLights(array $lights): int
    {
        $output = 0;

        for ($y = 0; $y < 1000; $y++) {
            for ($x = 0; $x < 1000; $x++) {
                $output += $lights[$y][$x];
            }
        }

        return $output;
    }

    private function updateLights(array $lights, int $fromY, int $fromX, int $toY, int $toX, callable $transform): array
    {
        for ($y = $fromY; $y <= $toY; $y++) {
            for ($x = $fromX; $x <= $toX; $x++) {
                $lights[$y][$x] = $transform($lights[$y][$x]);
            }
        }

        return $lights;
    }
}
