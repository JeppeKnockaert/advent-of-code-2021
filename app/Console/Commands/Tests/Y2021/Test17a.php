<?php

namespace App\Console\Commands\Tests\Y2021;

use App\Console\Commands\Tests\Test;
use function preg_match;

class Test17a implements Test
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

        $highestY = 0;
        for ($velocityX = 0; $velocityX < $this->targetXTo; $velocityX++) {
            for ($velocityY = 0; $velocityY < -$this->targetYFrom; $velocityY++) {
                $maxPosY = $this->checkIfVelocityHitsTarget(0, 0, $velocityX, $velocityY);
                $highestY = max($maxPosY, $highestY);
            }
        }

        return $highestY;
    }

    private function checkIfVelocityHitsTarget(int $posX, int $posY, int $velocityX, int $velocityY, int $maxPosY = 0): int
    {
        if ($posX >= $this->targetXFrom && $posX <= $this->targetXTo && $posY >= $this->targetYFrom && $posY <= $this->targetYTo) {
            return $maxPosY;
        }

        if ($posX > $this->targetXTo || $posY < $this->targetYTo) {
            return -1;
        }

        $posX += $velocityX;
        $posY += $velocityY;
        $velocityX = $velocityX > 0 ? $velocityX - 1 : 0;
        $velocityY = $velocityY - 1;
        $maxPosY = max($maxPosY, $posY);

        return $this->checkIfVelocityHitsTarget($posX, $posY, $velocityX, $velocityY, $maxPosY);
    }
}
