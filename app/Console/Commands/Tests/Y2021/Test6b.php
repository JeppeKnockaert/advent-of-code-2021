<?php

namespace App\Console\Commands\Tests\Y2021;

use App\Console\Commands\Tests\Test;

class Test6b implements Test
{
    private array $countByAge = [0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0];

    public function getResult(array $inputs): int
    {
        $ages = explode(',', $inputs[0]);
        foreach ($ages as $age) {
            $this->countByAge[$age]++;
        }

        for ($i = 0; $i < 256; $i++) {
            $this->ageFish();
        }

        $sum = 0;
        foreach ($this->countByAge as $ageCount) {
            $sum += $ageCount;
        }

        return $sum;
    }

    private function ageFish(): void
    {
        $newAges = [0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0];
        for ($i = 0; $i <= 8; $i++) {
            if ($i === 0) {
                $newAges[8] += $this->countByAge[$i];
            }
            $newAge = $i > 0 ? $i - 1 : 6;
            $newAges[$newAge] += $this->countByAge[$i];
        }

        $this->countByAge = $newAges;
    }
}
