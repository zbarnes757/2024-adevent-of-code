<?php


/**
 * Splits a string into two parts based on a delimiter and converts each part to an integer.
 *
 * @param string $line The input string containing two integer values separated by three spaces.
 * @return array{int, int} An array containing two integers extracted from the input string.
 */
function processLine(string $line): array
{
    [$left, $right] = explode('   ', $line, limit: 2);
    return [intval($left), intval($right)];
}


// Read file inputs
$file_path = __DIR__ . '/../inputs/01_input.txt';
$file = fopen($file_path, 'r');
$contents = fread($file, filesize($file_path));
$contents = trim($contents);
fclose($file);
$lines = explode("\n", $contents);

// PART 1
// Process lines into heaps
$leftMinHeap = new SplMinHeap();
$rightMinHeap = new SplMinHeap();

foreach ($lines as $line) {
    [$left, $right] = processLine($line);
    $leftMinHeap->insert($left);
    $rightMinHeap->insert($right);
}

// Calculate sum
$sum = 0;
while (!$leftMinHeap->isEmpty() && !$rightMinHeap->isEmpty()) {
    $left = $leftMinHeap->extract();
    $right = $rightMinHeap->extract();
    $sum += abs($left - $right);
}


// Output result
echo "Part 1: " . $sum . PHP_EOL;

// PART 2
$rightCounts = [];
$leftValues = [];

foreach ($lines as $line) {
    [$left, $right] = processLine($line);
    $rightCounts[$right] = $rightCounts[$right] ?? 0;
    $rightCounts[$right]++;
    array_push($leftValues, $left);
}

$sum = array_reduce($leftValues, fn ($carry, $item) => ($item * ($rightCounts[$item] ?? 0)) + $carry, 0);

echo "Part 2: " . $sum . PHP_EOL;
