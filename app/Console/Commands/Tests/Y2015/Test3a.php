<?php

namespace App\Console\Commands\Tests\Y2015;

use App\Console\Commands\Tests\Test;
use function array_key_exists;
use function explode;
use function str_split;

class Test3a implements Test
{
    public function getResult(array $inputs): int
    {
        $output = 1;
        $inputs = str_split($inputs[0]);
        $housesVisited = [];
        $housesVisited[0] = [];
        $housesVisited[0][0] = 1;
        $y = 0;
        $x = 0;

        foreach ($inputs as $input) {
            if ($input === 'v') {
                $y++;
            } else if ($input === '^') {
                $y--;
            } else if ($input === '<') {
                $x--;
            } else if ($input === '>') {
                $x++;
            }

            if (!array_key_exists($y, $housesVisited)) {
                $housesVisited[$y] = [];
            }
            if (!array_key_exists($x, $housesVisited[$y])) {
                $housesVisited[$y][$x] = 0;
            }
            $housesVisited[$y][$x]++;
            if ($housesVisited[$y][$x] === 1) {
                $output++;
            }
        }

        return $output;
    }

}
