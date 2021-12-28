<?php

namespace App\Console\Commands\Tests\Y2015;

use App\Console\Commands\Tests\Test;
use function mb_substr;
use function preg_replace_callback;
use function str_replace;
use function strlen;

class Test8a implements Test
{
    public function getResult(array $inputs): int
    {
        $characterLength = 0;
        $totalLength = 0;

        foreach ($inputs as $input) {
            $input = trim($input);
            $totalLength += strlen($input);
            $characterLength += $this->getNumberOfChars($input);
        }

        return $totalLength - $characterLength;
    }

    private function getNumberOfChars(string $input): int
    {
        $input = mb_substr($input, 1, -1);
        $input = str_replace(['\\\\', '\\"'], ['\\', '"'], $input);
        $input = preg_replace_callback('/\\\x([0-9a-f]{2})/', fn(array $val) => chr(hexdec($val[1])), $input);

        return strlen($input);
    }
}
