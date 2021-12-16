<?php

namespace App\Console\Commands\Tests;

use function base_convert;
use function bindec;
use function mb_substr;
use function str_pad;
use function str_split;
use function str_starts_with;
use const STR_PAD_LEFT;

class Test16a implements Test
{
    private int $position = 0;
    private int $versionSum = 0;

    public function getResult(array $inputs): int
    {
        $hex = str_split($inputs[0]);
        $binary = '';
        foreach ($hex as $hexChar) {
            $binary .= str_pad(base_convert($hexChar, 16, 2), 4, '0', STR_PAD_LEFT);
        }
        $this->translatePacket($binary);

        return $this->versionSum;
    }

    private function translatePacket(string $binary): void
    {
        $version = bindec(mb_substr($binary, $this->position, 3));
        $this->versionSum += $version;
        $type = bindec(mb_substr($binary, $this->position + 3, 3));
        $this->position += 6;

        if ($type === 4) {
            $this->translateLiteral($binary);
        } else {
            $this->translateOperator($binary);
        }
    }

    private function translateOperator(string $binary): void
    {
        $isZeroLengthTypeId = mb_substr($binary, $this->position, 1) === '0';
        $this->position++;

        if ($isZeroLengthTypeId) {
            $length = bindec(mb_substr($binary, $this->position, 15));
            $this->position += 15;
            $startPosition = $this->position;

            while ($this->position < $startPosition + $length) {
                $this->translatePacket($binary);
            }
        } else {
            $subpackNumber = bindec(mb_substr($binary, $this->position, 11));
            $this->position += 11;

            for ($i = 0; $i < $subpackNumber; $i++) {
                $this->translatePacket($binary);
            }
        }
    }

    private function translateLiteral(string $binary): int
    {
        $group = '';
        $bin = '';

        while (!str_starts_with($group, '0')) {
            $group = mb_substr($binary, $this->position, 5);
            $bin .= mb_substr($group, 1);
            $this->position += 5;
        }

        return (int)bindec($bin);
    }
}
