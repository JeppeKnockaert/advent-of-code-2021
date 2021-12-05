<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use function array_key_exists;
use function explode;

class Test5a extends Test
{
    private array $coveredPoints = [];
    private int $numberOfPointsGreaterThan2 = 0;

    protected function getResult(array $inputs): string|int
    {
        $this->coveredPoints = [];
        $this->numberOfPointsGreaterThan2 = 0;

        foreach ($inputs as $input) {
            $from = explode(',', Str::before($input, ' -> '));
            $to = explode(',', Str::after($input, ' -> '));
            if ($from[0] === $to[0] || $from[1] === $to[1]) {
                $this->coverPoints($from, $to);
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
