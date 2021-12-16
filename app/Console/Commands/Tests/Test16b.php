<?php

namespace App\Console\Commands\Tests;

use function base_convert;
use function bindec;
use function max;
use function mb_substr;
use function min;
use function str_pad;
use function str_split;
use function str_starts_with;
use const STR_PAD_LEFT;

class Test16b implements Test
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

        return $this->translatePacket($binary);
    }

    private function translatePacket(string $binary): int
    {
        $version = bindec(mb_substr($binary, $this->position, 3));
        $this->versionSum += $version;
        $type = bindec(mb_substr($binary, $this->position + 3, 3));
        $this->position += 6;

        if ($type === 4) {
            return $this->translateLiteral($binary);
        }

        $arguments = $this->getOperatorArguments($binary);
        if ($type === 0) {
            return collect($arguments)->sum();
        }
        if ($type === 1) {
            return collect($arguments)->reduce(fn($carry, $argument) => ($carry ?? 1) * $argument);
        }
        if ($type === 2) {
            return min($arguments);
        }
        if ($type === 3) {
            return max($arguments);
        }
        if ($type === 5) {
            return $arguments[0] > $arguments[1] ? 1 : 0;
        }
        if ($type === 6) {
            return $arguments[0] < $arguments[1] ? 1 : 0;
        }
        if ($type === 7) {
            return $arguments[0] === $arguments[1] ? 1 : 0;
        }

        return 0;
    }

    private function getOperatorArguments(string $binary): array
    {
        $isZeroLengthTypeId = mb_substr($binary, $this->position, 1) === '0';
        $this->position++;
        $arguments = [];

        if ($isZeroLengthTypeId) {
            $length = bindec(mb_substr($binary, $this->position, 15));
            $this->position += 15;
            $startPosition = $this->position;

            while ($this->position < $startPosition + $length) {
                $arguments[] = $this->translatePacket($binary);
            }
        } else {
            $subpackNumber = bindec(mb_substr($binary, $this->position, 11));
            $this->position += 11;

            for ($i = 0; $i < $subpackNumber; $i++) {
                $arguments[] = $this->translatePacket($binary);
            }
        }

        return $arguments;
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
