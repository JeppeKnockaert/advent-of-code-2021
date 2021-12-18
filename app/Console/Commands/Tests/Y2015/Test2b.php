<?php

namespace App\Console\Commands\Tests\Y2015;

use App\Console\Commands\Tests\Test;
use function explode;
use function str_split;

class Test2b implements Test
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

        return $sides[0] * 2 + $sides[1] * 2 + $sides[0] * $sides[1] * $sides[2];
    }
}
