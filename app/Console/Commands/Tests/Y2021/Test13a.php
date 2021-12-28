<?php

namespace App\Console\Commands\Tests\Y2021;

use App\Console\Commands\Tests\Test;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use function array_key_exists;
use function count;
use function explode;

class Test13a implements Test
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

                break;
            }

            if (Str::contains($input, ',')) {
                [$x, $y] = explode(',', $input);
                $this->visitCoordinate($x, $y);
            }
        }


        return count(Arr::flatten($this->inputMatrix));
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
    }
}
