<?php

namespace App\Exports;

use App\Merchant;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PreviousThirtyDayExport implements FromCollection,ShouldAutoSize,WithHeadings,WithStyles,WithTitle
{
    protected $datas;
    protected $title;
    public function __construct(array $datas ,$title)
    {
        $this->datas = $datas;
        $this->title = $title;
    }

    public function headings(): array
    {
        return [
            'No.',
            'Total By Day',
            'Day',
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect($this->datas);
    }
    public function title(): string
    {
        return $this->title;
    }
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],

            // // Styling a specific cell by coordinate.
            // 'B2' => ['font' => ['italic' => true]],

            // // Styling an entire column.
            // 'C'  => ['font' => ['size' => 16]],
        ];
    }
}
