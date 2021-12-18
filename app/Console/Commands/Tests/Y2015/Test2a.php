<?php

namespace App\Console\Commands\Tests\Y2015;

use App\Console\Commands\Tests\Test;
use function explode;
use function str_split;

class Test2a implements Test
{
    public function getResult(array $inputs): int
    {
        $output = 0;

        foreach ($inputs as $input) {
            $output += $this->calculateSize(explode('x', $input));
        }

        return $output;
    }

    private function calculateSize(array $sides): int
    {
        sort($sides);
        $side1 = $sides[0] * $sides[1];
        $side2 = $sides[0] * $sides[2];
        $side3 = $sides[1] * $sides[2];

        return 2*($side1 + $side2 + $side3) + $side1;
    }
}
