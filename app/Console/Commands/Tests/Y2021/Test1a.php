<?php

namespace App\Console\Commands\Tests\Y2021;

use App\Console\Commands\Tests\Test;

class Test1a implements Test
{
    public function getResult(array $inputs): int
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
