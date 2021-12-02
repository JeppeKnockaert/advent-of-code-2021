<?php

namespace App\Console\Commands;

use function explode;

class TestTemplate extends Test
{
    protected function getResult(array $inputs): string|int
    {
        $output = 0;

        foreach ($inputs as $input) {
            // TODO
        }

        return $output;
    }
}
