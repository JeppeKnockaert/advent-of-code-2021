<?php

namespace App\Console\Commands\Tests;

use Illuminate\Support\Str;
use function array_key_exists;

class Test21b implements Test
{
    private array $rollDistribution = [];

    public function getResult(array $inputs): int
    {
        $position = [
            ((int) Str::after($inputs[0], ' starting position: ')) - 1,
            ((int) Str::after($inputs[1], ' starting position: ')) - 1,
        ];
        $this->setRollDistribution();

        return $this->getNumberOfWins(0, [0, 0], $position, 0, 0);
    }

    private function setRollDistribution(): void
    {
        for ($i = 1; $i <= 3; $i++) {
            for ($j = 1; $j <= 3; $j++) {
                for ($k = 1; $k <= 3; $k++) {
                    $sum = $i + $j + $k;
                    if (!array_key_exists($sum, $this->rollDistribution)) {
                        $this->rollDistribution[$sum] = 0;
                    }
                    $this->rollDistribution[$sum]++;
                }
            }
        }
    }

    private function getNumberOfWins(int $dieSum, array $scores, array $position, int $player, int $universes): int
    {
        $wins = 0;

        if ($universes !== 0) {
            $position[$player] = ($position[$player] + $dieSum) % 10;
            $scores[$player] += $position[$player] + 1;
            $player = ($player + 1) % 2;
        } else {
            $universes = 1;
        }

        if ($scores[0] < 21 && $scores[1] < 21) {
            for ($i = 3; $i <= 9; $i++) {
                $wins += $this->getNumberOfWins($i, $scores, $position, $player, $universes * $this->rollDistribution[$i]);
            }
        }

        return $scores[0] >= 21 ? $wins + $universes : $wins;
    }
}
