<?php

namespace App\Console\Commands\Tests\Y2015;

use App\Console\Commands\Tests\Test;
use function mb_substr;
use function preg_replace;
use function str_replace;
use function strlen;

class Test8b implements Test
{
    public function getResult(array $inputs): int
    {
        $escapedLength = 0;
        $totalLength = 0;

        foreach ($inputs as $input) {
            $input = trim($input);
            $totalLength += strlen($input);
            $escapedLength += $this->escapeChars($input);
        }

        return $escapedLength - $totalLength;
    }

    private function escapeChars(string $input): int
    {
        $input = mb_substr($input, 1, -1);
        $input = str_replace(['\\\\', '\\"'], ['\\\\\\\\', '\\\\\\"'], $input);
        $input = preg_replace('/\\\x([0-9a-f]{2})/', '\\\\\\x$1', $input);

        return strlen($input) + 6;
    }
}
