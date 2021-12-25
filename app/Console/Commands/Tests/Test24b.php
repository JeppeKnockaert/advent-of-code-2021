<?php

namespace App\Console\Commands\Tests;

class Test24b implements Test
{
    public function getResult(array $inputs): int
    {
        $output = '';
        $possibilities = $this->getPossibilities($this->parseInstructions($inputs));
        $target = 0;

        for ($key = 0; $key <= 13; $key++) {
            for ($w = 1; $w <= 9; $w++) {
                if (array_key_exists($w, $possibilities[$key][$target])) {
                    $target = $possibilities[$key][$target][$w];
                    $output .= $w;
                    break;
                }
            }
        }

        return (int)$output;
    }

    private function parseInstructions(array $inputs): array
    {
        $instructions = [[], [], [], [], [], [], [], [], [], [], [], [], [], []];
        $inputsProcessed = -1;
        foreach ($inputs as $input) {
            if (str_starts_with($input, 'inp')) {
                $inputsProcessed++;
            } else {
                preg_match('/([a-z]+) ([a-z]+) ([a-z]+)/', $input, $matches);
                preg_match('/([a-z]+) ([a-z]+) ([a-z0-9\-]+)/', $input, $matches2);
                $instruction = $matches2[1];
                $inputVar = $matches2[2];
                $value = empty($matches) ? (int)$matches2[3] : $matches[3];
                $instructions[$inputsProcessed][] = [$instruction, $inputVar, $value];
            }
        }

        return $instructions;
    }

    private function processInstructions(array $instructions, array $variables): ?array
    {
        foreach ($instructions as $instruction) {
            $value = is_int($instruction[2]) ? $instruction[2] : $variables[$instruction[2]];
            $inputVar = $instruction[1];

            if ($instruction[0] === 'add') {
                $variables[$inputVar] += $value;
            } else if ($instruction[0] === 'mul') {
                $variables[$inputVar] *= $value;
            } else if ($instruction[0] === 'div') {
                if ($value === 0) {
                    $variables = null;
                    break;
                }
                $variables[$inputVar] = (int)round($variables[$inputVar] / $value * 1.0, PHP_ROUND_HALF_DOWN);
            } else if ($instruction[0] === 'mod') {
                if ($variables[$inputVar] < 0 || $value <= 0) {
                    $variables = null;
                    break;
                }
                $variables[$inputVar] %= $value;
            } else if ($instruction[0] === 'eql') {
                $variables[$inputVar] = ($variables[$inputVar] === $value) ? 1 : 0;
            }
        }

        return $variables;
    }

    private function getPossibilities(array $instructions): array
    {
        $possibilities = [];
        $maxZ = [0, 16, 440, 11453, 297803, 11453, 297803, 11453, 440, 11452, 440, 11456, 440, 16];

        for ($key = 13; $key >= 0; $key--) {
            $instruction = $instructions[$key];
            $targets = array_flip($key !== 13 ? array_keys($possibilities[$key + 1]) : [0]);

            for ($w = 1; $w <= 9; $w++) {
                for ($z = $maxZ[$key]; $z >= 0; $z--) {
                    $solution = $this->processInstructions($instruction, ['w' => $w, 'x' => 0, 'y' => 0, 'z' => $z]);
                    if ($solution !== null && array_key_exists($solution['z'], $targets)) {
                        $possibilities[$key][$z][$w] = $solution['z'];
                    }
                }
            }
        }

        return $possibilities;
    }
}
