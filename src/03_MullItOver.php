<?php

// Read input file
$file_path = __DIR__ . '/../inputs/03_input.txt';
$file = fopen($file_path, 'r');
$contents = fread($file, filesize($file_path));
$contents = trim($contents);
fclose($file);


// PART 1
$regex = '/mul\((\d+,\d+)\)/';
$matches = [];
preg_match_all($regex, $contents, $matches);
$sum = array_reduce($matches[1], function ($carry, $item) {
  [$left, $right] = array_map('intval', explode(',', $item));
  return $carry + $left * $right;
});
echo "PART 1: " . $sum . PHP_EOL;


// PART 2
$regex = "/(?<!don't.*?)mul\((\d+,\d+)\)|do\(\).*?mul\((\d+,\d+)\)/";
$matches = [];
preg_match_all($regex, $contents, $matches);
$sum = array_reduce($matches[1], function ($carry, $item) {
  [$left, $right] = array_map('intval', explode(',', $item));
  return $carry + $left * $right;
});
echo "PART 2: " . $sum . PHP_EOL;
