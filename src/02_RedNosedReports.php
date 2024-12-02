<?php

// Read input file
$file_path = __DIR__ . '/../inputs/02_input.txt';
$file = fopen($file_path, 'r');
$contents = fread($file, filesize($file_path));
$contents = trim($contents);
fclose($file);
$lines = explode("\n", $contents);

// PART 1
$count = 0;

function isSafe(array $numbers): bool
{
    $increasing = true;
    $decreasing = true;

    for ($i = 0; $i < count($numbers) - 1; $i++) {
        $current = $numbers[$i];
        $next = $numbers[$i + 1];
        $diff = abs($next - $current);

        if ($diff > 3 or $diff < 1) return false;

        if ($next > $current) {
            $decreasing = false;
        } else {
            $increasing = false;
        }

        if (!$increasing and !$decreasing) return false;
    }

    return true;
}

foreach ($lines as $line) {
    /** @var list<int> $numbers */
    $numbers = array_map('intval', explode(' ', $line));

    if (isSafe($numbers)) $count++;
}

echo "PART 1: " . $count . PHP_EOL;

// PART 2
function isSafeWithDampener(array $numbers): bool
{
  if (isSafe($numbers)) return true;

  // Try removing one level at a time and recheck safety
  for ($i = 0; $i < count($numbers); $i++) {
      $remaining = $numbers;
      array_splice($remaining, $i, 1); // Remove the i-th level

      if (isSafe($remaining)) return true;
  }

  return false; // Unsafe even with one level removed
}

$count = 0;
foreach ($lines as $line) {
    /** @var list<int> $numbers */
    $numbers = array_map('intval', explode(' ', $line));

    if (isSafeWithDampener($numbers)) $count++;
}

echo "PART 1: " . $count . PHP_EOL;
