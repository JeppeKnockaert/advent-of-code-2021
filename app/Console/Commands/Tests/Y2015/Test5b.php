<?php

namespace App\Console\Commands\Tests\Y2015;

use App\Console\Commands\Tests\Test;
use Illuminate\Support\Str;
use function preg_match;
use function preg_match_all;
use function str_split;

class Test5b implements Test
{
    public function getResult(array $inputs): int
    {
        $output = 0;

        foreach ($inputs as $input) {
            $output += $this->isStringNice($input) ? 1 : 0;
        }

        return $output;
    }

    private function isStringNice(string $input): bool
    {
        if (preg_match('/(.).\1/', $input) === 0) {
            return false;
        }

        return preg_match('/(..).*\1/', $input) !== 0;
    }
}
