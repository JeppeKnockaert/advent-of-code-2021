<?php

namespace App\Console\Commands\Tests\Y2021;

use App\Console\Commands\Tests\Test;
use function preg_match;

class Test17b implements Test
{
    private int $targetXFrom;
    private int $targetXTo;
    private int $targetYFrom;
    private int $targetYTo;

    public function getResult(array $inputs): int
    {
        $matches = [];
        preg_match('/x=([\-\d]+)\.\.([\-\d]+), y=([\-\d]+)\.\.([\-\d]+)/', $inputs[0], $matches);
        $this->targetXFrom = (int)$matches[1];
        $this->targetXTo = (int)$matches[2];
        $this->targetYFrom = (int)$matches[3];
        $this->targetYTo = (int)$matches[4];

        $matches = 0;
        for ($velocityX = 0; $velocityX <= $this->targetXTo; $velocityX++) {
            for ($velocityY = $this->targetYFrom; $velocityY < -$this->targetYFrom; $velocityY++) {
                if ($this->checkIfVelocityHitsTarget(0, 0, $velocityX, $velocityY)) {
                    $matches++;
                }
            }
        }
        return $matches;
    }

    private function checkIfVelocityHitsTarget(int $posX, int $posY, int $velocityX, int $velocityY): bool
    {
        if ($posX >= $this->targetXFrom && $posX <= $this->targetXTo && $posY >= $this->targetYFrom && $posY <= $this->targetYTo) {
            return true;
        }

        if ($posX > $this->targetXTo || $posY < $this->targetYFrom) {
            return false;
        }

        $posX += $velocityX;
        $posY += $velocityY;
        $velocityX = $velocityX > 0 ? $velocityX - 1 : 0;
        $velocityY = $velocityY - 1;

        return $this->checkIfVelocityHitsTarget($posX, $posY, $velocityX, $velocityY);
    }
}
