<?php

namespace App\Console\Commands\Tests\Y2021;

use App\Console\Commands\Tests\Test;
use Illuminate\Support\Str;
use function array_intersect;
use function collect;
use function count;
use function explode;
use function implode;
use function str_split;
use function strlen;

class Test8b implements Test
{
    public function getResult(array $inputs): int
    {
        $output = 0;

        foreach ($inputs as $input) {
            $allNumbers = collect(explode(' ', Str::before($input, ' |')));

            $remainingNumbers = collect();
            $foundNumbers = $allNumbers->mapWithKeys(function ($number) use ($remainingNumbers) {
                $translatedNumber = $this->getNumber($number);
                if ($translatedNumber === null) {
                    $remainingNumbers->push($number);
                }

                return [$translatedNumber => $number];
            });

            $splitNr1 = str_split($foundNumbers[1]);
            $splitNr4 = str_split($foundNumbers[4]);

            $remainingNumbers->each(function (string $digit) use ($splitNr1, $splitNr4, $foundNumbers) {
                $splitDigit = str_split($digit);
                $intersectWith1 = count(array_intersect($splitNr1, $splitDigit));
                $length = strlen($digit);
                if ($length === 6 && $intersectWith1 === 1) {
                    $foundNumbers[6] = $digit;

                    return true;
                }
                if ($length === 5 && $intersectWith1 === 2) {
                    $foundNumbers[3] = $digit;

                    return true;

                }
                $intersectWith4 = count(array_intersect($splitNr4, $splitDigit));
                if ($length === 6 && $intersectWith4 === 4) {
                    $foundNumbers[9] = $digit;

                    return true;

                }
                if ($length === 6) {
                    $foundNumbers[0] = $digit;

                    return true;

                }
                if ($length === 5 && $intersectWith4 === 3) {
                    $foundNumbers[5] = $digit;

                    return true;

                }
                $foundNumbers[2] = $digit;
            });

            $mapping = $foundNumbers->mapWithKeys(fn($letters, $number) => [$this->sortString($letters) => $number]);
            $output += (int) collect(explode(' ', Str::after($input, '| ')))
                ->reduce(fn($carry, $digit) => $carry . $mapping[$this->sortString($digit)]);
        }

        return $output;
    }

    private function getNumber(string $number): ?int
    {
        return match (strlen($number)) {
            2 => 1,
            3 => 7,
            4 => 4,
            7 => 8,
            default => null,
        };
    }

    private function sortString(string $str): string
    {
        $chars = str_split($str);
        sort($chars);

        return implode($chars);
    }
}
