<?php

namespace App\Console\Commands\Tests\Y2015;

use App\Console\Commands\Tests\Test;
use function array_key_exists;
use function explode;
use function str_split;

class Test3b implements Test
{
    // 2783 --> too high

    public function getResult(array $inputs): int
    {
        $output = 1;
        $inputs = str_split($inputs[0]);
        $housesVisited = [];
        $housesVisited[0] = [];
        $housesVisited[0][0] = true;

        $y1 = 0;
        $x1 = 0;

        $y2 = 0;
        $x2 = 0;

        foreach ($inputs as $i => $input) {
            $isSanta1 = $i%2 === 0;
            $y = $isSanta1 ? $y1 : $y2;
            $x = $isSanta1 ? $x1 : $x2;

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
                $housesVisited[$y][$x] = true;
                $output++;
            }

            if ($isSanta1) {
                $x1 = $x;
                $y1 = $y;
            } else {
                $x2 = $x;
                $y2 = $y;
            }
        }

        return $output;
    }

}
