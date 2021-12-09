<?php

namespace App\Console\Commands\Tests;

use function str_split;

class Test9a implements Test
{
    public function getResult(array $inputs): int
    {
        $output = 0;

        $inputMatrix = array_map(fn ($input) => str_split($input), $inputs);
        $rowCount = count($inputMatrix);

        for ($i = 0; $i < $rowCount; $i++) {
            $row = $inputMatrix[$i];
            $colCount = count($row);
            for ($j = 0; $j < $colCount; $j++) {
                $cell = $row[$j];

                if (
                    ($j-1 < 0 || $cell < $row[$j-1]) &&
                    ($j+1 === $colCount || $cell < $row[$j+1]) &&
                    ($i-1 < 0 || $cell < $inputMatrix[$i-1][$j]) &&
                    ($i+1 === $rowCount || $cell < $inputMatrix[$i+1][$j])
                ) {
                    $output += $cell + 1;
                }
            }
        }

        return $output;
    }
}
