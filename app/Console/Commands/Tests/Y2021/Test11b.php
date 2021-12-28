<?php

namespace App\Console\Commands\Tests\Y2021;

use App\Console\Commands\Tests\Test;
use function array_map;
use function str_split;

class Test11b implements Test
{
    private array $inputMatrix = [];
    private int $rows = 0;
    private int $cols = 0;
    private array $flashed = [];
    private int $flashesInStep = 0;

    public function getResult(array $inputs): int
    {
        $this->inputMatrix = array_map(fn($input) => str_split($input), $inputs);
        $this->rows = count($this->inputMatrix);
        $this->cols = count($this->inputMatrix[0]);

        for ($i = 0; $i < 10000; $i++) {
            $this->step();
            if ($this->flashesInStep === $this->rows * $this->cols) {
                return $i + 1;
            }
        }

        return -1;
    }

    private function step(): void
    {
        $this->flashesInStep = 0;
        $this->flashed = [];

        for ($y = 0; $y < $this->rows; $y++) {
            for ($x = 0; $x < $this->cols; $x++) {
                $this->flashed[$y][$x] = false;
                $this->inputMatrix[$y][$x]++;
            }
        }

        for ($y = 0; $y < $this->rows; $y++) {
            for ($x = 0; $x < $this->cols; $x++) {
                if ($this->inputMatrix[$y][$x] > 9) {
                    $this->flash($y, $x);
                }
            }
        }
    }

    private function flash(int $y, int $x): void
    {
        $this->flashesInStep++;
        $this->flashed[$y][$x] = true;
        $this->inputMatrix[$y][$x] = 0;

        for ($i = $y - 1; $i <= $y + 1; $i++) {
            for ($j = $x -1; $j <= $x + 1; $j++) {
                if ($i !== $y || $j !== $x) {
                    $this->increaseLevel($i, $j);
                }
            }
        }
    }

    private function increaseLevel(int $y, $x): void
    {
        if ($y < 0 || $y >= $this->rows || $x < 0 || $x >= $this->cols) {
            return;
        }
        if ($this->flashed[$y][$x]) {
            return;
        }

        $this->inputMatrix[$y][$x]++;

        if ($this->inputMatrix[$y][$x] > 9) {
            $this->flash($y, $x);
        }
    }
}
