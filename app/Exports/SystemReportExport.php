<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Support\Responsable;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class SystemReportExport implements  
                            FromCollection,
                            WithHeadings,
                            WithTitle
{
    use Exportable;
    protected $dataContents;
    protected $header;
    protected $temporaryTableName;
    public function __construct($header, array $dataContents, $temporaryTableName)
    {
        $this->dataContents = $dataContents;
        $this->header = $header;
        $this->temporaryTableName = $temporaryTableName;
    }

    public function headings(): array
    {
        return $this->header;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        return collect($this->dataContents);
    }
    public function title(): string
    {
        return $this->temporaryTableName.'_'.now();
    }

}
