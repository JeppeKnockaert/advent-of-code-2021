<?php

namespace App\Console\Commands\Tests\Y2021;

use App\Console\Commands\Tests\Test;
use function array_merge;
use function array_slice;
use function count;
use function implode;
use function is_numeric;
use function preg_match;
use function str_replace;
use function str_split;
use const PHP_ROUND_HALF_DOWN;
use const PHP_ROUND_HALF_UP;

class Test18b implements Test
{
    public function getResult(array $inputs): int
    {
        $maxMagnitude = 0;
        foreach ($inputs as $inputIndex => $input) {
            $currentInput = str_split($input);
            foreach ($inputs as $otherInputIndex => $otherInput) {
                if ($otherInputIndex === $inputIndex) {
                    continue;
                }
                $magnitude = $this->getMagnitude($this->addNumbers($currentInput, str_split($otherInput)));
                $maxMagnitude = max($maxMagnitude, $magnitude);
            }
        }

        return $maxMagnitude;
    }

    private function getMagnitude(array $chars): int
    {
        $reducing = true;
        $str = implode('', $chars);

        while ($reducing === true) {
            $reducing = false;
            $matches = [];
            if (preg_match('/\[(\d+),(\d+)]/', $str, $matches)) {
                $str = str_replace($matches[0], $matches[1] * 3 + $matches[2] * 2, $str);
                $reducing = true;
            }
        }

        return (int)$str;
    }

    private function addNumbers(array $first, array $second): array
    {
        return $this->reduceNumber(array_merge(['['], $first, [','], $second, [']']));
    }

    private function reduceNumber(array $chars): array
    {
        $reducing = true;
        while ($reducing === true) {
            $reducing = false;

            $explodingPairIndex = $this->findExplodingPair($chars);
            if ($explodingPairIndex >= 0) {
                $chars = $this->processExplodingPair($chars, $explodingPairIndex);
                $reducing = true;

                continue;
            }

            $splitIndex = $this->findNumberToSplit($chars);
            if ($splitIndex >= 0) {
                $reducing = true;
                $chars = $this->processNumberSplit($chars, $splitIndex);
            }
        }

        return $chars;
    }

    private function findExplodingPair(array $chars): int
    {
        $openBrackets = 0;

        foreach ($chars as $i => $char) {
            if ($char === '[') {
                $openBrackets++;
            } else if ($char === ']') {
                $openBrackets--;
            } else if ($openBrackets >= 5) {
                return $i;
            }
        }

        return -1;
    }

    private function findNumberToSplit(array $chars): int
    {
        foreach ($chars as $i => $char) {
            if (is_numeric($char) && $char >= 10) {
                return $i;
            }
        }

        return -1;
    }

    private function processNumberSplit(array $chars, int $index): array
    {
        $number = (int)($chars[$index]);

        $left = (int)round($number / 2, 0, PHP_ROUND_HALF_DOWN);
        $right = (int)round($number / 2, 0, PHP_ROUND_HALF_UP);

        return array_merge(
            array_slice($chars, 0, $index),
            ['[', $left, ',', $right, ']'],
            array_slice($chars, $index + 1)
        );
    }

    private function processExplodingPair(array $chars, int $index): array
    {
        $left = (int)$chars[$index];
        $right = (int)$chars[$index + 2];

        for ($i = $index - 1; $i >= 0; $i--) {
            if (is_numeric($chars[$i])) {
                $chars[$i] = $left + (int)$chars[$i];

                break;
            }
        }

        $charCount = count($chars);
        for ($i = $index + 3; $i < $charCount; $i++) {
            if (is_numeric($chars[$i])) {
                $chars[$i] = $right + (int)$chars[$i];

                break;
            }
        }

        return array_merge(array_slice($chars, 0, $index - 1), [0], array_slice($chars, $index + 4));
    }
}
