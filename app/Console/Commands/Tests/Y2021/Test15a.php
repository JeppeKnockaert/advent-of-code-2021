<?php

namespace App\Console\Commands\Tests\Y2021;

use App\Console\Commands\Tests\Test;
use function array_filter;
use function array_key_exists;
use function array_map;
use function count;
use function min;
use function str_split;

class Test15a implements Test
{
    private array $inputMatrix;
    private array $cheapestPaths;
    private int $rows;
    private int $cols;

    public function getResult(array $inputs): int
    {
        $this->inputMatrix = array_map(fn($input) => str_split($input), $inputs);
        $this->rows = count($this->inputMatrix);
        $this->cols = count($this->inputMatrix[0]);

        for ($y = 0; $y < $this->rows; $y++) {
            $this->cheapestPaths[$y] = [];
        }

        $this->cheapestPaths[$this->rows - 1][$this->cols - 1] = (int)$this->inputMatrix[$this->rows - 1][$this->cols - 1];

        return $this->getCheapestPath(0, 0) - $this->inputMatrix[0][0];
    }

    private function getCheapestPath(int $y, int $x): int
    {
        if ($x + 1 < $this->cols && !array_key_exists($x + 1, $this->cheapestPaths[$y])) {
            $this->getCheapestPath($y, $x + 1);
        }
        if ($y + 1 < $this->rows && !array_key_exists($x, $this->cheapestPaths[$y + 1])) {
            $this->getCheapestPath($y + 1, $x);
        }

        return $this->cheapestPaths[$y][$x] = min(array_filter([
                $this->cheapestPaths[$y][$x + 1] ?? null,
                $this->cheapestPaths[$y + 1][$x] ?? null,
            ])) + $this->inputMatrix[$y][$x];
    }
}
