<?php

enum Direction: string
{
    case UP = 'U';
    case DOWN = 'D';
    case LEFT = 'L';
    case RIGHT = 'R';
    case LEFT_UP = 'LU';
    case LEFT_DOWN = 'LD';
    case RIGHT_UP = 'RU';
    case RIGHT_DOWN = 'RD';
}


class CeresSearch
{
    /** @var list<list<string>> */
    private array $matrix;

    /** @var list<string> */
    private const sequence = ['X', 'M', 'A', 'S'];


    public function __construct(string $filePath)
    {
        $contents = file_get_contents($filePath);
        $contents = trim($contents);
        $lines = explode("\n", $contents);

        $this->matrix = array_map('str_split', $lines);
    }

    public function findInstancesOfXMAS(): int
    {
        $found = 0;

        for ($row = 0; $row < count($this->matrix); $row++) {
            for ($column = 0; $column < count($this->matrix[$row]); $column++) {
                $current = $this->matrix[$row][$column];

                if ($current == self::sequence[0]) {
                    foreach (Direction::cases() as $direction) {
                        [$newRow, $newColumn] = $this->move($direction, $row, $column);
                        if ($this->search($direction, $newRow, $newColumn, 1)) {
                            $found++;
                        }
                    }
                }
            }
        }

        return $found;
    }

    public function findInstancesOfMAS(): int
    {
        $found = 0;

        for ($row = 1; $row < count($this->matrix) - 1; $row++) {
            for ($column = 1; $column < count($this->matrix[$row]) - 1; $column++) {
                $current = $this->matrix[$row][$column];

                if ($current === 'A') {
                    $firstCross = [
                      $this->matrix[$row - 1][$column - 1],
                      $this->matrix[$row + 1][$column + 1],
                    ];

                    $secondCross = [
                      $this->matrix[$row - 1][$column + 1],
                      $this->matrix[$row + 1][$column - 1],
                    ];

                    if ($this->makesACross($firstCross, $secondCross)) {
                        $found++;
                    }
                }
            }
        }

        return $found;
    }

    // Helpers

    private function search(Direction $direction, int $row, int $column, int $currentStep): bool
    {
        if ($currentStep >= count(self::sequence)) {
            return true;
        }
        if ($row < 0 or $row >= count($this->matrix)) {
            return false;
        }
        if ($column < 0 or $column >= count($this->matrix[$row])) {
            return false;
        }

        if ($this->matrix[$row][$column] === self::sequence[$currentStep]) {
            [$newRow, $newColumn] = $this->move($direction, $row, $column);
            return $this->search($direction, $newRow, $newColumn, $currentStep + 1);
        }

        return false;
    }

    /**
     * @return array{int, int}
     */
    private function move(Direction $direction, int $row, int $column): array
    {
        return match ($direction) {
            Direction::UP => [$row - 1, $column],
            Direction::DOWN => [$row + 1, $column],
            Direction::LEFT => [$row, $column - 1],
            Direction::RIGHT => [$row, $column + 1],
            Direction::LEFT_UP => [$row - 1, $column - 1],
            Direction::LEFT_DOWN => [$row + 1, $column - 1],
            Direction::RIGHT_UP => [$row - 1, $column + 1],
            Direction::RIGHT_DOWN => [$row + 1, $column + 1]
        };
    }

    /**
     * @param array{string, string} $firstCross
     * @param array{string, string} $secondCross
     * @return bool
     */
    private function makesACross(array $firstCross, array $secondCross): bool
    {
        $legitFirstCross = in_array('M', $firstCross) && in_array('S', $firstCross);
        $legitSecondCross = in_array('M', $secondCross) && in_array('S', $secondCross);

        return $legitFirstCross and $legitSecondCross;
    }
}


$search = new CeresSearch(__DIR__ . '/../inputs/04_input.txt');
$part1 = $search->findInstancesOfXMAS();
echo "Part 1: " . $part1 . PHP_EOL;
$part2 = $search->findInstancesOfMAS();
echo "Part 2: " . $part2 . PHP_EOL;
