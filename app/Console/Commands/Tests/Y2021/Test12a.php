<?php

namespace App\Console\Commands\Tests\Y2021;

use App\Console\Commands\Tests\Test;
use function App\Console\Commands\Tests\ctype_upper;
use function array_key_exists;
use function explode;
use function in_array;

class Test12a implements Test
{
    private array $nodes = [];
    private array $paths = [];

    public function getResult(array $inputs): int
    {
        foreach ($inputs as $input) {
            $nodes = explode('-', $input);
            if (!array_key_exists($nodes[0], $this->nodes)) {
                $this->nodes[$nodes[0]] = [];
            }
            $this->nodes[$nodes[0]][] = $nodes[1];

            if (!array_key_exists($nodes[1], $this->nodes)) {
                $this->nodes[$nodes[1]] = [];
            }
            $this->nodes[$nodes[1]][] = $nodes[0];
        }

        $this->visitGraph('start', []);

        return count($this->paths);
    }

    private function visitGraph(string $node, array $path): void
    {
        $path[] = $node;

        if ($node === 'end') {
            $this->paths[] = $path;
            return;
        }

        $destinations = $this->nodes[$node];

        foreach ($destinations as $destination) {
            $smallCaveVisited = in_array($destination, $path);
            $isBigCave = ctype_upper($destination);

            if (!$smallCaveVisited || $isBigCave) {
                $this->visitGraph($destination, $path);
            }
        }
    }
}
