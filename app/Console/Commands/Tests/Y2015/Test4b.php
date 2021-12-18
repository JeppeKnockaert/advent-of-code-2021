<?php

namespace App\Console\Commands\Tests\Y2015;

use App\Console\Commands\Tests\Test;
use Illuminate\Support\Str;
use function str_split;

class Test4b implements Test
{
    public function getResult(array $inputs): int
    {
        $input = $inputs[0];

        $i = 0;
        while (true) {
            if (Str::startsWith(md5($input . $i), '000000')) {
                return $i;
            }
            $i++;
        }
    }
}
