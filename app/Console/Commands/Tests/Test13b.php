<?php

namespace App\Console\Commands\Tests;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use function array_key_exists;
use function count;
use function explode;

class Test13b implements Test
{
    private array $inputMatrix = [];
    private int $rows = 0;
    private int $cols = 0;

    public function getResult(array $inputs): int
    {
        foreach ($inputs as $input) {
            if (Str::startsWith($input, 'fold')) {
                $fold = explode('=', Str::after($input, 'fold along '));

                if ($fold[0] === 'y') {
                    $this->processFoldY($fold[1]);
                } else {
                    $this->processFoldX($fold[1]);
                }
            }

            if (Str::contains($input, ',')) {
                [$x, $y] = explode(',', $input);
                $this->visitCoordinate($x, $y);
            }
        }

        for ($y = 0; $y <= $this->rows; $y++) {
            for ($x = 0; $x <= $this->cols; $x++) {
                if (array_key_exists($y, $this->inputMatrix) && array_key_exists($x, $this->inputMatrix[$y])) {
                    echo '#';
                } else {
                    echo '.';
                }
            }
            echo "\n";
        }

        return 0;
    }

    private function visitCoordinate(int $x, int $y): void
    {
        if (!array_key_exists($y, $this->inputMatrix)) {
            $this->inputMatrix[$y] = [];
        }

        $this->inputMatrix[$y][$x] = true;

        if ($y > $this->rows) {
            $this->rows = $y;
        }

        if ($x > $this->cols) {
            $this->cols = $x;
        }
    }

    private function processFoldY(int $line): void
    {
        $foldIndex = $line - 1;

        for ($y = $line + 1; $y <= $this->rows; $y++) {
            for ($x = 0; $x <= $this->cols; $x++) {
                if (array_key_exists($y, $this->inputMatrix) && array_key_exists($x, $this->inputMatrix[$y])) {
                    $this->inputMatrix[$foldIndex][$x] = $this->inputMatrix[$y][$x];
                }
            }

            unset($this->inputMatrix[$y]);
            $foldIndex--;
        }

        $this->rows = $line - 1;
    }


    private function processFoldX(int $line): void
    {
        $foldIndex = $line - 1;
        for ($x = $line + 1; $x <= $this->cols; $x++) {
            for ($y = 0; $y <= $this->rows; $y++) {
                if (array_key_exists($y, $this->inputMatrix) && array_key_exists($x, $this->inputMatrix[$y])) {
                    $this->inputMatrix[$y][$foldIndex] = $this->inputMatrix[$y][$x];
                    unset($this->inputMatrix[$y][$x]);
                }
            }

            $foldIndex--;
        }

        $this->cols = $line - 1;
    }
}
