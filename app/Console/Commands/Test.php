<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function class_basename;
use function explode;
use function trim;

abstract class Test extends Command
{
    private int $testNumber;

    public function __construct()
    {
        $this->name = Str::lower(class_basename(static::class));
        parent::__construct();
        $this->testNumber = (int) Str::substr($this->name, 4, 1);
    }

    public function handle(): void
    {
        $this->info('Test: ' . $this->getResult($this->getTestInput()));
        $this->info('Result: ' . $this->getResult($this->getInput()));
    }

    protected function getInput(): array
    {
        return $this->getInputsForInputFileInDirectory('inputs');

    }

    protected function getTestInput(): array
    {
        return $this->getInputsForInputFileInDirectory('tests');
    }

    private function getInputsForInputFileInDirectory(string $directory): array
    {
        $fileName = $directory . '/input' . $this->testNumber;

        if (!Storage::exists($fileName)) {
            Storage::put($fileName, '');
        }

        return explode("\n", trim(Storage::get($fileName)));
    }

    abstract protected function getResult(array $inputs): string|int;
}
