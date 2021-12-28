<?php

namespace App\Console\Commands\Tests\Y2021;

use App\Console\Commands\Tests\Test;
use function array_merge;
use function str_split;

class Test9b implements Test
{
    private array $inputMatrix = [];
    private int $rowCount = 0;
    private int $colCount = 0;

    public function getResult(array $inputs): int
    {
        $this->inputMatrix = array_map(fn($input) => str_split($input), $inputs);
        $this->rowCount = count($this->inputMatrix);
        $this->colCount = count($this->inputMatrix[0]);
        $basinSizes = [];

        for ($y = 0; $y < $this->rowCount; $y++) {
            $row = $this->inputMatrix[$y];
            for ($x = 0; $x < $this->colCount; $x++) {
                $cell = $row[$x];

                if (
                    ($x - 1 < 0 || $cell < $row[$x - 1]) &&
                    ($x + 1 === $this->colCount || $cell < $row[$x + 1]) &&
                    ($y - 1 < 0 || $cell < $this->inputMatrix[$y - 1][$x]) &&
                    ($y + 1 === $this->rowCount || $cell < $this->inputMatrix[$y + 1][$x])
                ) {

                    $basin = $this->discoverBasin($y, $x);
                    $basinSizes[] = count($basin);
                }
            }
        }

        rsort($basinSizes);

        return $basinSizes[0] * $basinSizes[1] * $basinSizes[2];
    }

    private function discoverBasin(int $y, int $x): array
    {
        if ($y < 0 || $y >= $this->rowCount || $x < 0 || $x >= $this->colCount || $this->inputMatrix[$y][$x] === null || $this->inputMatrix[$y][$x] === "9") {
            return [];
        }

        $this->inputMatrix[$y][$x] = null;

        return array_merge(
            [[$y, $x]],
            $this->discoverBasin($y, $x - 1),
            $this->discoverBasin($y, $x + 1),
            $this->discoverBasin($y - 1, $x),
            $this->discoverBasin($y + 1, $x),
        );
    }
}
