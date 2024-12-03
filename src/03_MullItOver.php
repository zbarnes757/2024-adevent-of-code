<?php


final class MullItOver
{
    private string $contents;
    public function __construct(string $filePath)
    {
        $file = fopen($filePath, 'r');
        $contents = fread($file, filesize($filePath));
        $this->contents = trim($contents);
        fclose($file);
    }

    public function evaluateCorruptedData(?string $contents = null)
    {
        $contents = $contents ?? $this->contents;
        $regex = '/mul\((\d+,\d+)\)/';
        $matches = [];
        preg_match_all($regex, $contents, $matches);
        return array_reduce($matches[1], function ($carry, $item) {
            [$left, $right] = array_map('intval', explode(',', $item));
            return $carry + $left * $right;
        }, 0);
    }

    public function evaluateCorruptedDataWithConditions()
    {
        $matches = [];
        preg_match_all("/(?:don't\(\)|do\(\)|mul\(\d{1,3},\s*\d{1,3}\))/", $this->contents, $matches);
        [$_, $sum] = array_reduce($matches[0], function ($carry, $item) {
            [$include, $amount] = $carry;
            if ($item == "don't()") {
                return [false, $amount];
            }

            if ($item == "do()") {
                return [true, $amount];
            }

            if (!$include) {
                return $carry;
            }

            $numbers = substr($item, 4, -1);
            [$a, $b] = array_map('intval', explode(',', $numbers));
            return [$include, $amount + $a * $b];
        }, [true, 0]);

        return $sum;
    }
}


// PART 1
$file_path = __DIR__ . '/../inputs/03_input.txt';
$mullItOver = new MullItOver(filePath: $file_path);

$sum = $mullItOver->evaluateCorruptedData();
echo "PART 1: " . $sum . PHP_EOL;


// PART 2
$sum = $mullItOver->evaluateCorruptedDataWithConditions();
echo "PART 2: " . $sum . PHP_EOL;
