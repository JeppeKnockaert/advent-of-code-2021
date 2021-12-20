<?php

namespace App\Console\Commands\Tests;

use function bindec;
use function count;
use function str_split;

class Test20a implements Test
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

        $this->minX = -2;
        $this->minY = -2;
        $this->rows = count($this->inputMatrix) + 2;
        $this->cols = count($this->inputMatrix[0]) + 2;

        $this->enhance();
        $this->enhance();

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
