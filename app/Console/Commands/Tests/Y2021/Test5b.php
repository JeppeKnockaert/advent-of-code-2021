<?php

namespace App\Console\Commands\Tests\Y2021;

use App\Console\Commands\Tests\Test;
use Illuminate\Support\Str;
use function array_key_exists;
use function explode;
use function max;
use function min;

class Test5b implements Test
{
    private array $coveredPoints = [];
    private int $numberOfPointsGreaterThan2 = 0;

    public function getResult(array $inputs): int
    {
        foreach ($inputs as $input) {
            $from = explode(',', Str::before($input, ' -> '));
            $to = explode(',', Str::after($input, ' -> '));

            if ($from[0] === $to[0] || $from[1] === $to[1]) {
                $this->coverPoints($from, $to);
            } else {
                $this->coverDiagonal($from, $to);
            }
        }

        return $this->numberOfPointsGreaterThan2;
    }

    private function coverPoints(array $from, array $to): void
    {
        $x1 = min((int)$from[0], (int)$to[0]);
        $x2 = max((int)$from[0], (int)$to[0]);

        $y1 = min((int)$from[1], (int)$to[1]);
        $y2 = max((int)$from[1], (int)$to[1]);

        for ($y = $y1; $y <= $y2; $y++) {
            for ($x = $x1; $x <= $x2; $x++) {
                $this->coverPoint($x, $y);
            }
        }
    }

    private function coverDiagonal(array $from, array $to): void
    {
        $xDirection = $from[0] > $to[0] ? -1 : 1;
        $yDirection = $from[1] > $to[1] ? -1 : 1;

        $distance = abs($from[0] - $to[0]);

        for ($i = 0; $i <= $distance; $i++) {
            $y = $from[1] + $i * $yDirection;
            $x = $from[0] + $i * $xDirection;
            $this->coverPoint($x, $y);
        }
    }

    private function coverPoint(int $x, int $y): void
    {
        if (!array_key_exists($y, $this->coveredPoints)) {
            $this->coveredPoints[$y] = [];
        }

        if (!array_key_exists($x, $this->coveredPoints[$y])) {
            $this->coveredPoints[$y][$x] = 0;
        } else if ($this->coveredPoints[$y][$x] === 1) {
            $this->numberOfPointsGreaterThan2++;
        }

        $this->coveredPoints[$y][$x]++;
    }
}
