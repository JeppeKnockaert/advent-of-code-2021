<?php

namespace App\Console\Commands\Tests\Y2021;

use App\Console\Commands\Tests\Test;
use Illuminate\Support\Collection;
use function array_key_exists;
use function array_slice;
use function collect;
use function count;
use function explode;
use function str_split;

class Test14a implements Test
{
    private Collection $templates;

    public function getResult(array $inputs): int
    {
        $this->templates = collect(array_slice($inputs, 2))
            ->map(fn($input) => explode(' -> ', $input))
            ->mapWithKeys(fn($input) => [$input[0] => $input[1]]);


        $newString = $inputs[0];
        for ($i = 0; $i < 10; $i++) {
            $newString = $this->processInputString($newString);
        }

        $letterCounters = [];
        $letters = str_split($newString);

        $maxLetterCounter = 0;
        foreach ($letters as $letter) {
            if (!array_key_exists($letter, $letterCounters)) {
                $letterCounters[$letter] = 0;
            }
            $letterCounters[$letter]++;

            $maxLetterCounter = max($letterCounters[$letter], $maxLetterCounter);
        }

        return $maxLetterCounter - collect($letterCounters)->min();
    }

    private function processInputString(string $inputString): string
    {
        $newString = '';
        $inputString = str_split($inputString);
        $length = count($inputString);
        for ($i = 0; $i < $length - 1; $i++) {
            $pair = $inputString[$i] . $inputString[$i + 1];
            $translation = $this->templates->get($pair);
            $newString .= $inputString[$i] . $translation;
        }
        $newString .= $inputString[$length - 1];

        return $newString;
    }
}
