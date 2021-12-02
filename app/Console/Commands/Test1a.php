<?php

namespace App\Console\Commands\Tests;

use App\Console\Commands\Test;

class Test1a extends Test
{
    protected function getResult(array $inputs): string|int
    {
        $higherThenBefore = 0;
        $previousInput = $inputs[0];
        foreach ($inputs as $input) {
            if ($input > $previousInput) {
                $higherThenBefore++;
            }
            $previousInput = $input;
        }

        return $higherThenBefore;
    }
}
