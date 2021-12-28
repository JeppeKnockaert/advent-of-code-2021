<?php

namespace App\Console\Commands\Tests\Y2015;

use App\Console\Commands\Tests\Test;
use function base_convert;
use function bindec;
use function preg_match;
use function str_pad;
use function str_split;
use const STR_PAD_LEFT;

class Test7a implements Test
{
    public function getResult(array $inputs): int
    {
        $values = [];

        while (!empty($inputs)) {
            $newInputs = [];

            foreach ($inputs as $input) {
                if (preg_match('/^(\d+) -> ([a-z]+)/', $input, $matches) === 1) {
                    $values[$matches[2]] = (int)$matches[1];
                }
                if (preg_match('/^([a-z]+) -> ([a-z]+)/', $input, $matches) === 1) {
                    $value1 = preg_match('/[a-z]+/', $matches[1]) === 1 ? ($values[$matches[1]] ?? null) : $matches[1];
                    if ($value1 === null) {
                        $newInputs[] = $input;
                    } else {
                        $values[$matches[2]] = $value1;
                    }
                } else if (preg_match('/([a-z\d]+) ([A-Z]+) ([a-z\d]+) -> ([a-z]+)/', $input, $matches) === 1) {
                    $value1 = preg_match('/[a-z]+/', $matches[1]) === 1 ? ($values[$matches[1]] ?? null) : (int)$matches[1];
                    $value2 = preg_match('/[a-z]+/', $matches[3]) === 1 ? ($values[$matches[3]] ?? null) : (int)$matches[3];

                    if ($value1 === null || $value2 === null) {
                        $newInputs[] = ($value1 ?? $matches[1]) . ' ' . $matches[2] . ' ' . ($value2 ?? $matches[3]) . ' -> ' . $matches[4];
                    } else {
                        if ($matches[2] === 'AND') {
                            $values[$matches[4]] = bindec($this->dec2bin($value1) & $this->dec2bin($value2));
                        } else if ($matches[2] === 'OR') {
                            $values[$matches[4]] = bindec($this->dec2bin($value1) | $this->dec2bin($value2));
                        } else if ($matches[2] === 'LSHIFT') {
                            $values[$matches[4]] = $value1 << $value2;
                        } else if ($matches[2] === 'RSHIFT') {
                            $values[$matches[4]] = $value1 >> $value2;
                        }
                    }
                } else if (preg_match('/NOT ([a-z]+) -> ([a-z]+)/', $input, $matches) === 1) {
                    $value1 = preg_match('/[a-z]+/', $matches[1]) === 1 ? ($values[$matches[1]] ?? null) : (int)$matches[1];
                    if ($value1 === null) {
                        $newInputs[] = $input;
                    } else {
                        $values[$matches[2]] = bindec($this->not($this->dec2bin($value1)));
                    }
                }
            }
            $inputs = $newInputs;
        }

        return $values['a'];
    }

    private function dec2bin(int $dec): string
    {
        return str_pad(base_convert($dec, 10, 2), 16, '0', STR_PAD_LEFT);
    }

    private function not(string $binary): string
    {
        $value = '';
        foreach (str_split($binary) as $char) {
            $value .= $char === '0' ? '1' : '0';
        }

        return $value;
    }
}
