<?php

namespace App\Console\Commands\Tests\Y2021;

use App\Console\Commands\Tests\Test;

class Test25a implements Test
{
    private array $inputMatrix;
    private int $rows;
    private int $cols;

    public function getResult(array $inputs): int
    {
        $this->inputMatrix = array_map(fn($input) => str_split($input), $inputs);
        $this->rows = count($this->inputMatrix);
        $this->cols = count($this->inputMatrix[0]);

        $steps = 0;
        while ($this->moveHerd()) {
            $steps++;
        }

        return $steps + 1;
    }

    private function moveHerd(): bool
    {
        $moves = 0;
        $newMatrix = [];
        for ($y = 0; $y < $this->rows; $y++) {
            $newMatrix[$y] = [];
            for ($x = 0; $x < $this->cols; $x++) {
                $newX = $x + 1 < $this->cols ? $x + 1 : 0;
                if ($this->inputMatrix[$y][$x] === '>' && $this->inputMatrix[$y][$newX] === '.') {
                    $newMatrix[$y][$x] = '.';
                    $newMatrix[$y][$newX] = '>';
                    $moves++;
                } else if (!array_key_exists($x, $newMatrix[$y])) {
                    $newMatrix[$y][$x] = $this->inputMatrix[$y][$x];
                }
            }
        }
        $this->inputMatrix = $newMatrix;

        $newMatrix = [];
        for ($y = 0; $y < $this->rows; $y++) {
            for ($x = 0; $x < $this->cols; $x++) {
                if (!array_key_exists($y, $newMatrix)) {
                    $newMatrix[$y] = [];
                }

                $newY = $y + 1 < $this->rows ? $y + 1 : 0;
                if ($this->inputMatrix[$y][$x] === 'v' && $this->inputMatrix[$newY][$x] === '.') {
                    $newMatrix[$y][$x] = '.';
                    $newMatrix[$newY][$x] = 'v';
                    $moves++;
                } else if (!array_key_exists($x, $newMatrix[$y])) {
                    $newMatrix[$y][$x] = $this->inputMatrix[$y][$x];
                }
            }
        }
        $this->inputMatrix = $newMatrix;

        return $moves > 0;
    }
}
