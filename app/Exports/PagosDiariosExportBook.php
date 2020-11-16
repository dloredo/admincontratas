<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PagosDiariosExportBook implements WithMultipleSheets
{
    use Exportable;

    protected $dates_chunks;
    
    public function __construct($dates_chunks)
    {
        $this->dates_chunks = $dates_chunks;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->dates_chunks as $chunk) {
            $sheets[] = new PagosDiariosExport($chunk);
        }

        return $sheets;
    }
}
