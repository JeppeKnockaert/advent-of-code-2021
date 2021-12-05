<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function app;
use function explode;
use function trim;

class Test extends Command
{
    private int $testNumber = 0;
    private string $identifier = '';

    protected $signature = 'test {identifier} {--skip-test}';

    public function handle(): void
    {
        $this->identifier = $this->argument('identifier');
        $this->testNumber = (int) Str::substr($this->identifier, 0, Str::length($this->identifier) - 1);

        if (!$this->option('skip-test')) {
            $this->info('Test: ' . $this->getResult($this->getTestInput()));
        }

        $this->info('Result: ' . $this->getResult($this->getInput()));
    }

    private function getResult(array $input): int
    {
        return app(__NAMESPACE__ . '\Tests\\' . 'Test' . $this->identifier)->getResult($input);
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
}
