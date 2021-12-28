<?php

namespace App\Console\Commands\Tests\Y2021;

use App\Console\Commands\Tests\Test;
use function array_map;
use function collect;
use function count;
use function in_array;
use function str_split;

class Test10b implements Test
{
    public function getResult(array $inputs): int
    {
        $inputMatrix = array_map(fn($input) => str_split($input), $inputs);
        $scores = [];
        foreach ($inputMatrix as $inputRow) {
            $completingChars = $this->getCompletingChars($inputRow);
            $score = $this->getScoreForChars($completingChars);
            if ($score !== 0) {
                $scores[] = $score;
            }
        }
        sort($scores);

        return $scores[(count($scores) - 1) / 2];
    }

    private function getCompletingChars(array $row): array
    {
        $stack = collect();
        foreach ($row as $char) {
            if (in_array($char, ['[', '{', '(', '<'])) {
                $stack->push($char);
            } else {
                if ($stack->isEmpty()) {
                    return [];
                }
                $lastFromStack = $stack->pop();
                if (
                    ($char === ']' && $lastFromStack !== '[') ||
                    ($char === '}' && $lastFromStack !== '{') ||
                    ($char === ')' && $lastFromStack !== '(') ||
                    ($char === '>' && $lastFromStack !== '<')
                ) {
                    return [];
                }
            }
        }

        $charsToAdd = [];
        $remainingStack = $stack->reverse()->all();
        foreach ($remainingStack as $char) {
            $charsToAdd[] = match ($char) {
                '(' => ')',
                '[' => ']',
                '{' => '}',
                '<' => '>',
            };
        }

        return $charsToAdd;
    }

    private function getScoreForChars(array $chars): int
    {
        $output = 0;
        foreach ($chars as $char) {
            $output = $output * 5 + match ($char) {
                    ')' => 1,
                    ']' => 2,
                    '}' => 3,
                    '>' => 4,
                    default => 0,
                };
        }

        return $output;
    }
}
