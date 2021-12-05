<?php

namespace App\Console\Commands\Tests;

use function collect;
use function count;

class Test1b implements Test
{
    public function getResult(array $inputs): int
    {
        $higherThenBefore = 0;
        $windows = [];

        $previousWindowSum = 0;
        $inputCount = count($inputs);
        for ($i = 0; $i < $inputCount; $i++) {
            $input = $inputs[$i];

            if ($i >= 2) {
                $windows[$i - 2][2] = $input;
            }
            if ($i >= 1) {
                $windows[$i - 1][1] = $input;
            }
            $windows[$i][0] = $input;

            $currentWindowSum = $i >= 2 ? collect($windows[$i - 2])->sum() : 0;

            if ($i >= 3 && $currentWindowSum > $previousWindowSum) {
                $higherThenBefore++;
            }

            $previousWindowSum = $currentWindowSum;
        }

        return $higherThenBefore;
    }
}
