<?php

namespace App\Console\Commands;

use function array_key_exists;
use function bindec;
use function count;
use function str_split;

class Test3b extends Test
{
    protected function getResult(array $inputs): string|int
    {
        $oxygen = $this->getNumberForBitCriteria(
            $inputs,
            fn($numbersForBitValue) => count($numbersForBitValue[0]) > count($numbersForBitValue[1])
                ? $numbersForBitValue[0]
                : $numbersForBitValue[1]
        );

        $co2 = $this->getNumberForBitCriteria(
            $inputs,
            fn($numbersForBitValue) => count($numbersForBitValue[0]) <= count($numbersForBitValue[1])
                ? $numbersForBitValue[0]
                : $numbersForBitValue[1]
        );

        return bindec($oxygen) * bindec($co2);
    }

    private function getNumberForBitCriteria(array $inputs, callable $bitCheck): string
    {
        $bits = [];
        $bitsForInputLength = count(str_split($inputs[0]));
        $remainingNumbers = $inputs;

        for ($i = 0; $i < $bitsForInputLength; $i++) {
            if (count($remainingNumbers) === 1) {
                break;
            }

            foreach ($remainingNumbers as $number) {
                $bitsForInput = str_split($number);
                $bitValue = $bitsForInput[$i];
                if (!array_key_exists($i, $bits)) {
                    $bits[$i] = [0 => [], 1 => []];
                }
                $bits[$i][$bitValue][] = $number;
            }
            $remainingNumbers = $bitCheck($bits[$i]);
        }

        return $remainingNumbers[0];
    }
}
