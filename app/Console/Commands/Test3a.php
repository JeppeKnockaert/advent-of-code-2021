<?php

namespace App\Console\Commands;

use function array_key_exists;
use function collect;
use function count;
use function str_split;

class Test3a extends Test
{
    protected function getResult(array $inputs): string|int
    {
        $bits = [];

        $bitsForInputLength = count(str_split($inputs[0]));
        foreach ($inputs as $input) {
            $bitsForInput = str_split($input);
            for ($i = 0; $i < $bitsForInputLength; $i++) {
                $bitValue = $bitsForInput[$i];
                if (!array_key_exists($i, $bits)) {
                    $bits[$i] = [];
                }
                if (!array_key_exists($bitValue, $bits[$i])) {
                    $bits[$i][$bitValue] = 0;
                }
                $bits[$i][$bitValue]++;
            }
        }

        $gamma = collect($bits)
            ->map(fn($bit) => $bit[0] > $bit[1] ? '0' : '1')
            ->reduce(fn($carry, $item) => $carry . $item);

        $epsilon = collect($bits)
            ->map(fn($bit) => $bit[0] > $bit[1] ? '1' : '0')
            ->reduce(fn($carry, $item) => $carry . $item);

        return bindec($gamma) * bindec($epsilon);
    }
}
