<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithTitle;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TotalRecordExport implements FromCollection,ShouldAutoSize,WithHeadings,WithStyles,WithTitle,Responsable
{
    use Exportable;
    protected $top_merchant;
    protected $header;

    public function __construct(array $top_merchant , $header)
    {
        $this->top_merchant = $top_merchant;
        $this->header = $header;
    }

    public function headings(): array
    {
        return [
            [
                "Total Record"
            ],
            $this->header,
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        return collect($this->top_merchant);
    }
   
    public function title(): string
    {
        return 'Total Record';
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:E1')->getFont()->setSize(16);
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A:E')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getColumnDimension('A')->setWidth(13);
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
            2    => ['font' => ['bold' => true]],
        ];
    }
}
