<?php

namespace App\Console\Commands\Tests;

use Illuminate\Support\Collection;
use function str_repeat;

class Test6a implements Test
{
    private Collection $ages;

    public function getResult(array $inputs): int
    {
        $this->ages = collect(explode(',', $inputs[0]));

        for ($i = 0; $i < 256; $i++) {
            $this->ageFish();
        }

        return count($this->ages);
    }

    private function ageFish(): void
    {
        $newFish = 0;
        $this->ages->transform(function ($age) use (&$newFish) {
            if ($age === 0) {
                $newFish++;

                return 6;
            }

            return $age - 1;
        });

        for ($i = 0; $i < $newFish; $i++) {
            $this->ages->push(8);
        }
    }
}
