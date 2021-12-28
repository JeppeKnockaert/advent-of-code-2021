<?php

namespace App\Console\Commands\Tests\Y2021;

use App\Console\Commands\Tests\Test;
use function array_map;
use function collect;
use function in_array;
use function str_split;

class Test10a implements Test
{
    public function getResult(array $inputs): int
    {
        $output = 0;

        $inputMatrix = array_map(fn($input) => str_split($input), $inputs);
        foreach ($inputMatrix as $inputRow) {
            $illegalCharacter = $this->getFirstIllegalCharacter($inputRow);
            $value = match ($illegalCharacter) {
                ')' => 3,
                ']' => 57,
                '}' => 1197,
                '>' => 25137,
                default => 0,
            };
            $output += $value;
        }

        return $output;
    }

    private function getFirstIllegalCharacter(array $row): ?string
    {
        $stack = collect();
        foreach ($row as $char) {
            if (in_array($char, ['[', '{', '(', '<'])) {
                $stack->push($char);
            } else {
                if ($stack->isEmpty()) {
                    return $char;
                }
                $lastFromStack = $stack->pop();
                if (
                    ($char === ']' && $lastFromStack !== '[') ||
                    ($char === '}' && $lastFromStack !== '{') ||
                    ($char === ')' && $lastFromStack !== '(') ||
                    ($char === '>' && $lastFromStack !== '<')
                ) {
                    return $char;
                }
            }
        }

        return null;
    }
}
