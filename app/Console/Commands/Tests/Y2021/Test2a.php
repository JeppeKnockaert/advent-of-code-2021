<?php

namespace App\Console\Commands\Tests\Y2021;

use App\Console\Commands\Tests\Test;
use function explode;

class Test2a implements Test
{
    public function getResult(array $inputs): int
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
