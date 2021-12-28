<?php

namespace App\Console\Commands\Tests\Y2021;

use App\Console\Commands\Tests\Test;
use function array_slice;
use function preg_match;
use function str_starts_with;

class Test22a implements Test
{
    public function getResult(array $inputs): int
    {
        $output = 0;

        $matrix = [];
        for ($z = -50; $z <= 50; $z++) {
            $matrix[$z] = [];
            for ($y = -50; $y <= 50; $y++) {
                $matrix[$z][$y] = [];
                for ($x = -50; $x <= 50; $x++) {
                    $matrix[$z][$y][$x] = false;
                }
            }
        }

        foreach ($inputs as $input) {
            $turnOn = str_starts_with($input, 'on');
            $range = $this->getRangeForLine($input);

            for ($z = $range[4]; $z <= $range[5]; $z++) {
                if ($z < -50 || $z > 50) {
                    continue;
                }
                for ($y = $range[2]; $y <= $range[3]; $y++) {
                    if ($y < -50 || $y > 50) {
                        continue;
                    }
                    for ($x = $range[0]; $x <= $range[1]; $x++) {
                        if ($x < -50 || $x > 50) {
                            continue;
                        }
                        $matrix[$z][$y][$x] = $turnOn;
                    }
                }
            }
        }

        for ($z = -50; $z <= 50; $z++) {
            for ($y = -50; $y <= 50; $y++) {
                for ($x = -50; $x <= 50; $x++) {
                    if ($matrix[$z][$y][$x] === true) {
                        $output++;
                    }
                }
            }
        }

        return $output;
    }

    private function getRangeForLine(string $line): array
    {
        preg_match('/x=([\-\d]+)\.\.([\-\d]+),y=([\-\d]+)\.\.([\-\d]+),z=([\-\d]+)\.\.([\-\d]+)/', $line, $matches);

        return array_slice($matches, 1);
    }

}
