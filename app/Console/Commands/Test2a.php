<?php

namespace App\Console\Commands\Tests;

use App\Console\Commands\Test;
use function explode;

class Test2a extends Test
{
    protected function getResult(array $inputs): string|int
    {
        $horizontal = 0;
        $depth = 0;

        foreach ($inputs as $input) {
            [$command, $count] = explode(' ', $input);
            switch ($command) {
                case 'forward':
                    $horizontal += (int)$count;
                    break;
                case 'down':
                    $depth += (int)$count;
                    break;
                case 'up':
                    $depth -= (int)$count;
                    break;
            }
        }

        return $horizontal * $depth;
    }
}
