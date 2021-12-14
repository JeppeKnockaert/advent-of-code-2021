<?php

namespace App\Console\Commands\Tests;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use function array_key_exists;
use function array_slice;
use function collect;
use function count;
use function explode;
use function str_split;
use function strlen;

class Test14b implements Test
{
    private Collection $templates;

    public function getResult(array $inputs): int
    {
        $this->templates = collect(array_slice($inputs, 2))
            ->map(fn($input) => explode(' -> ', $input))
            ->mapWithKeys(fn($input) => [$input[0] => $input[1]]);

        $newPairs = $this->getPairCounters($inputs[0]);
        for ($i = 0; $i < 40; $i++) {
            $newPairs = $this->processInputString($newPairs);
        }

        $letterCounters = [];
        foreach ($newPairs as $newPair => $count) {
            $pair = str_split($newPair);
            if (!array_key_exists($pair[0], $letterCounters)) {
                $letterCounters[$pair[0]] = 0;
            }
            $letterCounters[$pair[0]] += $count;
        }
        $letterCounters[Str::substr($inputs[0], strlen($inputs[0]) - 1)]++;

        return collect($letterCounters)->max() - collect($letterCounters)->min();
    }

    private function getPairCounters(string $inputString): array
    {
        $inputString = str_split($inputString);
        $length = count($inputString);

        $pairs = [];
        for ($i = 0; $i < $length - 1; $i++) {
            $pair = $inputString[$i] . $inputString[$i + 1];
            if (!array_key_exists($pair, $pairs)) {
                $pairs[$pair] = 0;
            }
            $pairs[$pair]++;
        }

        return $pairs;
    }

    private function processInputString(array $inputPairs): array
    {
        $newPairs = [];
        foreach ($inputPairs as $inputPair => $count) {
            $translation = $this->templates->get($inputPair);

            if (!array_key_exists($inputPair[0] . $translation, $newPairs)) {
                $newPairs[$inputPair[0] . $translation] = 0;
            }
            $newPairs[$inputPair[0] . $translation] += $count;

            if (!array_key_exists($translation . $inputPair[1], $newPairs)) {
                $newPairs[$translation . $inputPair[1]] = 0;
            }
            $newPairs[$translation . $inputPair[1]] += $count;
        }

        return $newPairs;
    }
}
