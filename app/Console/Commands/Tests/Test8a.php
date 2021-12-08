<?php

namespace App\Console\Commands\Tests;

use Illuminate\Support\Str;
use function explode;

class Test8a implements Test
{
    public function getResult(array $inputs): int
    {
        $output = 0;

        foreach ($inputs as $input) {
            $output += collect(explode(' ', Str::after($input, '| ')))
                ->filter(fn ($digit) => in_array(strlen($digit), [2, 4, 3, 7]))
                ->count();
        }

        return $output;
    }
}
