<?php

declare(strict_types=1);

namespace App;

use App\Cell;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Board extends Model
{
    /**
     * Number of rows in the board.
     */
    public int $rows;

    /**
     * Number of columns in the board.
     */
    public int $columns;

    /**
     * Number of mines in the board.
     */
    public int $mines;

    /**
     * App\Cell collection in the board.
     */
    public Collection $cells;

    /**
     * Build a new board.
     */
    function __construct(int $rows, int $columns, int $mines)
    {
        if ($rows < 1) {
            throw new Exception('rows parameter must be a positive integer.');
        }

        if ($columns < 1) {
            throw new Exception('columns parameter must be a positive integer.');
        }

        if ($mines < 1) {
            throw new Exception('mines parameter must be a positive integer.');
        }

        $this->rows = $rows;
        $this->columns = $columns;
        $this->mines = $mines;

        // Allocate cells in board.
        $this->cells = collect();
        $position = 0;

        for ($i = 1; $i <= $this->rows; ++$i) {
            for ($j = 1; $j <= $this->columns; ++$j) {
                $this->cells->push(
                    new Cell($i, $j, ++$position)
                );
            }
        }

        // Mine the board.
        $this->mine();

        // Fill values of cells that are adjacent to each mine.
        $this->cells->whereStrict('value', 0)
            ->each(function ($item, $key) {
                $this->calculateAdjacentCellsValues($item);
            });
    }

    /*
     * Put mines in board.
     */
    public function mine()
    {
        // Get random cells positions for mines in the board.
        $minedCells = array_rand(
            range(1, $this->rows * $this->columns),
            $this->mines
        );

        $this->cells
            ->whereInStrict('position', $minedCells)
            ->sortBy('position')
            ->transform(function ($item, $key) {
                $item->mine();
            });
    }

    /**
     * Iterates over each mined cell and sets or increments the value of any,
     * non mined, adjacent cells.
     */
    public function calculateAdjacentCellsValues(Cell $cell)
    {
        for ($i = ($cell->row - 1); $i <= ($cell->row + 1); ++$i) {
            // Check for valid row boundaries.
            if ($i < 1 || $i > $this->rows) {
                continue;
            }

            for ($j = ($cell->column - 1); $j <= ($cell->column + 1); ++$j) {
                // Check for valid column boundaries.
                if ($j < 1 || $j > $this->columns) {
                    continue;
                }

                // Discard any (including current) mined cells.
                $this->cells
                    ->whereStrict('row', $i)
                    ->whereStrict('column', $j)
                    ->where('value', '!==', 0)
                    ->transform(function ($item, $key) {
                        $item->increaseAdjacentCellValue();
                    });
            }
        }
    }
}
