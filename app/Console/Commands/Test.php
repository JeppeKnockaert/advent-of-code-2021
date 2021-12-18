<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use function app;
use function explode;
use function file_exists;
use function file_put_contents;
use function trim;

class Test extends Command
{
    private int $testNumber = 0;
    private string $identifier = '';
    private ?int $year;

    protected $signature = 'test {identifier} {--skip-test} {--year=}';

    public function handle(): void
    {
        $this->year = $this->option('year');
        $this->identifier = $this->argument('identifier');
        $this->testNumber = (int) Str::substr($this->identifier, 0, Str::length($this->identifier) - 1);

        if (!$this->option('skip-test')) {
            $this->info('Test: ' . $this->getResult($this->getTestInput()));
        }

        $this->info('Result: ' . $this->getResult($this->getInput()));
    }

    private function getResult(array $input): int
    {
        $yearNamespace = $this->year ? 'Y' .$this->year . '\\': '';
        return app(__NAMESPACE__ . '\Tests\\' . $yearNamespace . 'Test' . $this->identifier)->getResult($input);
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
        $directory = $this->year ? $this->year . '/' . $directory: $directory;
        $fileName = __DIR__ . '/../../../storage/' . $directory . '/input' . $this->testNumber;

        if (!file_exists($fileName)) {
            file_put_contents($fileName, '');
        }

        return explode("\n", trim(file_get_contents($fileName)));
    }
}
