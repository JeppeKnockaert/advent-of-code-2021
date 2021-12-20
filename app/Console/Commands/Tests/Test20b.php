<?php

namespace App\Console\Commands\Tests;

use function array_key_exists;
use function bindec;
use function collect;
use function count;
use function dd;
use function dump;
use function max;
use function min;
use function str_split;

class Test20b implements Test
{
    private array $inputMatrix;
    private int $rows;
    private int $cols;

    private array $algorithm;
    private int $minY;
    private int $minX;

    public function getResult(array $inputs): int
    {
        $this->algorithm = str_split($inputs[0]);

        foreach (array_slice($inputs, 2) as $i => $input) {
            $this->inputMatrix[$i] = str_split($input);
        }

        $iterations = 50;
        $this->minX = -$iterations;
        $this->minY = -$iterations;
        $this->rows = count($this->inputMatrix) + $iterations;
        $this->cols = count($this->inputMatrix[0]) + $iterations;

        for ($i = 0; $i < $iterations; $i++) {
            $this->enhance();
        }

        return collect($this->inputMatrix)->flatten()->filter(fn($val) => $val === '#')->count();
    }

    private function enhance(): void
    {
        $newMatrix = [];
        for ($y = $this->minY; $y < $this->rows; $y++) {
            $newMatrix[$y] = [];
            for ($x = $this->minX; $x < $this->cols; $x++) {
                $newMatrix[$y][$x] = $this->getPixelValue($y, $x);
            }
        }

        $this->inputMatrix = $newMatrix;
    }

    private function getPixelValue(int $pixelY, int $pixelX): string
    {
        $binary = '';
        for ($y = $pixelY - 1; $y <= $pixelY + 1; $y++) {
            for ($x = $pixelX - 1; $x <= $pixelX + 1; $x++) {
                $yVal = $y < 0 ? max($this->minY, $y) : min($this->rows - 1, $y);
                $xVal = $x < 0 ? max($this->minX, $x) : min($this->cols - 1, $x);
                $binary .= ($this->inputMatrix[$yVal][$xVal] ?? '.') === '#' ? '1' : '0';
            }
        }

        return $this->algorithm[bindec($binary)];
    }
}
