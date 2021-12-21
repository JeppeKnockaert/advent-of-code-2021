<?php

namespace App\Console\Commands\Tests;

use Illuminate\Support\Str;

class Test21a implements Test
{
    public function getResult(array $inputs): int
    {
        $position = [
            ((int) Str::after($inputs[0], ' starting position: ')) - 1,
            ((int) Str::after($inputs[1], ' starting position: ')) - 1,
        ];
        $scores = [0, 0];
        $dieValue = 1;
        $player = 0;

        while ($scores[0] < 1000 && $scores[1] < 1000) {
            $dieSum = $dieValue * 3 + 3;
            $position[$player] = ($position[$player] + $dieSum) % 10;
            $scores[$player] += $position[$player] + 1;

            $dieValue += 3;
            $player = ($player + 1) % 2;
        }

        return ($dieValue - 1) * ($scores[0] < 1000 ? $scores[0] : $scores[1]);
    }
}
