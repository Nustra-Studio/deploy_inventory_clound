<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Imports\BarangUpdate;

class BarangUpdates implements WithMultipleSheets
{
    /**
    * @param Collection $collection
    */
    private $start;
    private $end;
    public function __construct($start, $end)
    {
        $this->startColumn = $start;
        $this->endColumn = $end;
    }

    public function sheets(): array
    {
        return [
            "Sheet3"=> new BarangUpdate($this->startColumn, $this->endColumn)
        ];
    }
}
