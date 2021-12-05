<?php

namespace App\Console\Commands\Tests;

use function explode;

class Test2b implements Test
{
    public function getResult(array $inputs): int
    {
        $horizontal = 0;
        $depth = 0;
        $aim = 0;

        foreach ($inputs as $input) {
            [$command, $count] = explode(' ', $input);
            switch ($command) {
                case 'forward':
                    $horizontal += (int)$count;
                    $depth += (int)$count * $aim;
                    break;
                case 'down':
                    $aim += (int)$count;
                    break;
                case 'up':
                    $aim -= (int)$count;
                    break;
            }
        }

        return $horizontal * $depth;
    }
}
