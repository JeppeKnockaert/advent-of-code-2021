<?php

namespace App\Console\Commands\Tests;

use function array_flip;
use function array_key_exists;
use function array_key_last;
use function array_slice;
use function explode;
use function floor;
use function max;
use function min;
use function preg_split;
use function trim;

class Test4a implements Test
{
    public function getResult(array $inputs): int
    {
        $bingoInputs = array_flip(explode(',', $inputs[0]));

        $bingoPanels = [];
        $bingoWinPanels = [];

        foreach ($inputs as $index => $input) {
            if ($index === 0) {
                continue;
            }

            $inputIndex = $index - 1;
            $panelIndex = (int)floor($inputIndex / 6);
            $bingoRowIndex = $inputIndex % 6 - 1;
            $bingoRow = preg_split('/\s+/', trim($input));

            if ($bingoRowIndex === -1) {
                $bingoPanels[$panelIndex] = [];
            } else {
                $bingoPanels[$panelIndex][$bingoRowIndex] = $bingoRow;
            }

            if ($bingoRowIndex === 4) {
                $bingoWinPanels[$panelIndex] = $this->getIndexAtWhichBoardWins($bingoPanels[$panelIndex], $bingoInputs);
            }
        }

        $minNumberOfTurns = count($bingoInputs);
        $minNumberOfTurnsPanelIndex = -1;

        foreach ($bingoWinPanels as $index => $bingoWinPanel) {
            if ($bingoWinPanel < $minNumberOfTurns) {
                $minNumberOfTurnsPanelIndex = $index;
                $minNumberOfTurns = $bingoWinPanel + 1;
            }
        }

        return $this->getBoardScore(
            $bingoPanels[$minNumberOfTurnsPanelIndex],
            array_slice($bingoInputs, 0, $minNumberOfTurns, true),
        );
    }

    private function getIndexAtWhichBoardWins(array $bingoPanel, array $bingoInputs): int
    {
        $rowWinsAt = null;
        $colWinsAt = null;

        for ($y = 0; $y < 5; $y++) {
            $maxTurnIndexForRow = 0;
            $row = $bingoPanel[$y];
            for ($x = 0; $x < 5; $x++) {
                $maxTurnIndexForRow = max($bingoInputs[$row[$x]], $maxTurnIndexForRow);
            }
            $rowWinsAt = $rowWinsAt !== null ? min($rowWinsAt, $maxTurnIndexForRow) : $maxTurnIndexForRow;
        }

        for ($x = 0; $x < 5; $x++) {
            $maxTurnIndexForCol = 0;
            for ($y = 0; $y < 5; $y++) {
                $maxTurnIndexForCol = max($bingoInputs[$bingoPanel[$y][$x]], $maxTurnIndexForCol);
            }
            $colWinsAt = $colWinsAt !== null ? min($colWinsAt, $maxTurnIndexForCol) : $maxTurnIndexForCol;
        }

        return min($rowWinsAt, $colWinsAt);
    }

    private function getBoardScore(array $bingoPanel, array $bingoInputs): int
    {
        $unmarkedNumbersSum = 0;
        for ($y = 0; $y < 5; $y++) {
            for ($x = 0; $x < 5; $x++) {
                $number = $bingoPanel[$y][$x];
                if (!array_key_exists($number, $bingoInputs)) {
                    $unmarkedNumbersSum += $number;
                }
            }
        }

        return $unmarkedNumbersSum * array_key_last($bingoInputs);
    }
}
