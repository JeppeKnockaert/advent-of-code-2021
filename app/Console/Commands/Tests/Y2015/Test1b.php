<?php

namespace App\Console\Commands\Tests\Y2015;

use App\Console\Commands\Tests\Test;
use function str_split;

class Test1b implements Test
{
    public function getResult(array $inputs): int
    {
        $output = 0;
        $inputs = str_split($inputs[0]);

        foreach ($inputs as $i => $input) {
            $output += $input === '(' ? 1 : -1;
            if ($output < 0) {
                return $i + 1;
            }
        }

        return -1;
    }
}
