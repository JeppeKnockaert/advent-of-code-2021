<?php

namespace App\Console\Commands\Tests;

use function array_key_exists;
use function explode;
use function in_array;

class Test12b implements Test
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

    private function visitGraph(string $node, array $path, ?string $visitedTwice = null): void
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
                $this->visitGraph($destination, $path, $visitedTwice);
            } else if ($visitedTwice === null && !in_array($destination, ['start', 'end'])) {
                $this->visitGraph($destination, $path, $destination);
            }
        }
    }
}
