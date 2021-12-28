<?php

namespace App\Console\Commands\Tests\Y2021;

use App\Console\Commands\Tests\Test;
use Illuminate\Support\Str;
use function collect;
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
