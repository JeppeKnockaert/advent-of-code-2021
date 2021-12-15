<?php

namespace App\Console\Commands\Tests;

use function array_filter;
use function array_key_exists;
use function array_map;
use function count;
use function min;
use function str_split;

class Test15b implements Test
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

        for ($y = 0; $y < $this->rows * 5; $y++) {
            $this->cheapestPaths[$y] = [];
        }
        $this->completeMatrixHorizontal();
        $this->completeMatrixVertical();

        $this->rows *= 5;
        $this->cols *= 5;

        $this->cheapestPaths[$this->rows - 1][$this->cols - 1] = (int)$this->inputMatrix[$this->rows - 1][$this->cols - 1];

        $this->getCheapestPath(0, 0);
        $foundCheaperPath = true;
        while ($foundCheaperPath === true) {
            $foundCheaperPath = $this->findCheaperPath();
        }

        return $this->cheapestPaths[0][0] - $this->inputMatrix[0][0];
    }

    private function completeMatrixHorizontal(): void
    {
        for ($y = $this->rows; $y < $this->rows * 5; $y++) {
            $this->inputMatrix[$y] = [];
            for ($x = 0; $x < $this->cols; $x++) {
                $this->inputMatrix[$y][$x] = $this->inputMatrix[$y - $this->rows][$x] + 1;
                if ($this->inputMatrix[$y][$x] === 10) {
                    $this->inputMatrix[$y][$x] = 1;
                }
            }
        }
    }

    private function completeMatrixVertical(): void
    {
        for ($y = 0; $y < $this->rows * 5; $y++) {
            for ($x = $this->cols; $x < $this->cols * 5; $x++) {
                $this->inputMatrix[$y][$x] = $this->inputMatrix[$y][$x - $this->cols] + 1;
                if ($this->inputMatrix[$y][$x] === 10) {
                    $this->inputMatrix[$y][$x] = 1;
                }
            }
        }
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

    private function findCheaperPath(): bool
    {
        $improvementFound = false;

        for ($y = 0; $y < $this->rows; $y++) {
            for ($x = 0; $x < $this->cols; $x++) {
                $oldValue = $this->cheapestPaths[$y][$x];
                $this->cheapestPaths[$y][$x] = min(array_filter([
                        $this->cheapestPaths[$y][$x] ?? null,
                        $this->cheapestPaths[$y][$x + 1] ?? null,
                        $this->cheapestPaths[$y + 1][$x] ?? null,
                        $this->cheapestPaths[$y - 1][$x] ?? null,
                        $this->cheapestPaths[$y][$x - 1] ?? null,
                    ])) + $this->inputMatrix[$y][$x];

                if ($oldValue > $this->cheapestPaths[$y][$x]) {
                    $improvementFound = true;
                }
            }
        }

        return $improvementFound;
    }
}
