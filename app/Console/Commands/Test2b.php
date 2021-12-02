<?php

namespace App\Console\Commands\Tests;

use App\Console\Commands\Test;
use function explode;

class Test2b extends Test
{
    protected function getResult(array $inputs): string|int
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
