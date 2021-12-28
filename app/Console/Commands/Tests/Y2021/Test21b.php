<?php

namespace App\Console\Commands\Tests\Y2021;

use App\Console\Commands\Tests\Test;
use Illuminate\Support\Str;
use function implode;

class Test21b implements Test
{
    private array $rollDistribution = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    private array $winsCaching = [];

    public function getResult(array $inputs): int
    {
        $position = [
            ((int)Str::after($inputs[0], ' starting position: ')) - 1,
            ((int)Str::after($inputs[1], ' starting position: ')) - 1,
        ];
        $this->setRollDistribution();

        return $this->getNumberOfWins([0, 0], $position, 0);
    }

    private function setRollDistribution(): void
    {
        for ($i = 1; $i <= 3; $i++) {
            for ($j = 1; $j <= 3; $j++) {
                for ($k = 1; $k <= 3; $k++) {
                    $this->rollDistribution[$i + $j + $k]++;
                }
            }
        }
    }

    private function getNumberOfWins(array $scores, array $position, int $player): int
    {
        $wins = 0;

        if ($scores[0] < 21 && $scores[1] < 21) {
            $newPlayer = ($player + 1) % 2;

            for ($i = 3; $i <= 9; $i++) {
                $newPosition = $position;
                $newScores = $scores;

                $newPosition[$player] = ($newPosition[$player] + $i) % 10;
                $newScores[$player] += $newPosition[$player] + 1;

                $key = implode('#', $newScores) . '.' . implode('#', $newPosition) . '.' . $newPlayer;
                $newWins = $this->winsCaching[$key] ?? $this->getNumberOfWins($newScores, $newPosition, $newPlayer);
                $this->winsCaching[$key] = $newWins;

                $wins += $this->rollDistribution[$i] * $newWins;
            }
        }

        return $scores[0] >= 21 ? $wins + 1 : $wins;
    }
}
