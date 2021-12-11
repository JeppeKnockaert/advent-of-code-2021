<?php

namespace App\Console\Commands\Tests;

use function array_map;
use function count;
use function str_split;

class TestTemplate implements Test
{
    private array $inputMatrix;
    private int $rows;
    private int $cols;

    public function getResult(array $inputs): int
    {
        $output = 0;

        $this->inputMatrix = array_map(fn($input) => str_split($input), $inputs);
        $this->rows = count($this->inputMatrix);
        $this->cols = count($this->inputMatrix[0]);

        for ($y = 0; $y < $this->rows; $y++) {
            for ($x = 0; $x < $this->cols; $x++) {
                // TODO
            }
        }

        foreach ($inputs as $input) {
            // TODO
        }

        return $output;
    }
}
