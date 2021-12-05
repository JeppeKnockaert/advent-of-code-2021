<?php

namespace App\Console\Commands\Tests;

interface Test {
    public function getResult(array $inputs): int;
}
