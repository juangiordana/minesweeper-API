<?php

declare(strict_types=1);

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;

class Cell extends Model
{
    /**
     * Cell X coordinate.
     */
    public int $row;

    /**
     * Cell Y coordinate.
     */
    public int $column;

    /**
     * Cell position (cell # from left to right, top to bottom).
     */
    public int $position;

    /**
     * Cell value:
     * null => Cell is empty (default).
     * 0    => Cell contains a mine.
     * > 0  => Cell has # adjacent mines.
     */
    public ?int $value = null;

    /**
     * Initialize an App\Cell object with row and column coordinates, position
     * in the App\Board and a default value of empty (null).
     */
    public function __construct(int $row, int $column, int $position)
    {
        if ($row < 1) {
            throw new Exception('row parameter must be a positive integer.');
        }

        if ($column < 1) {
            throw new Exception('column parameter must be a positive integer.');
        }

        if ($position < 1) {
            throw new Exception('position parameter must be a positive integer.');
        }

        $this->row = $row;
        $this->column = $column;
        $this->position = $position;
    }

    /**
     * Set Cell value.
     */
    protected function setValue(int $value) : void
    {
        if ($value < 0) {
            throw new Exception('Allowed cell values must integers equal to, or greater than zero.');
        }
        $this->value = $value;
    }

    /**
     * Put mine in Cell.
     */
    public function mine() : void
    {
        if ($this->value !== null) {
            throw new Exception('Cell value has previously been set.');
        }
        $this->setValue(0);
    }

    /**
     * Increase adjacent mines counter.
     */
    public function increaseAdjacentCellValue() : void
    {
        if ($this->value === 0) {
            throw new Exception('Mined cells cannot be altered.');
        }
        $this->setValue($this->value + 1);
    }
}
