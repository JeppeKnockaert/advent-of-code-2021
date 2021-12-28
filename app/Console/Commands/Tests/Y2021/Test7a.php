<?php

namespace App\Console\Commands\Tests\Y2021;

use App\Console\Commands\Tests\Test;
use function collect;
use function explode;

class Test7a implements Test
{
    public function getResult(array $inputs): int
    {
        $positions = collect(explode(',', $inputs[0]));
        $maxPos = $positions->max();
        $smallestSum = -1;
        for ($i = 0; $i < $maxPos; $i++) {
            $sum = $positions->sum(fn ($pos) => abs($pos-$i));
            $smallestSum = $smallestSum !== -1 ? min($smallestSum, $sum) : $sum;
        }

        return $smallestSum;
    }
}
