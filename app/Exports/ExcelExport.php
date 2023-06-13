<?php

namespace App\Exports;

use App\CustomClasses\MultiFilter;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExcelExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, Responsable
{

    use Exportable;
    public function __construct($slug, $class, $header,$field, $request ,$field_relationship = [])
    {
        $this->class = $class;
        $this->field = $field;
        $this->header = [[ucfirst($slug)], $header];
        $this->request = $request;
        $this->field_relationship = $field_relationship;
    }
    
    public function headings():array{
        return $this->header;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $multiFilter = new MultiFilter;
        $filter_data = $multiFilter->filter($this->request, $this->field, $this->class, $this->field_relationship);
        
        return $filter_data;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:'.$sheet->getHighestColumn().'1');
        $sheet->getStyle('A1:'.$sheet->getHighestColumn().'1')->getFont()->setSize(16);
        $sheet->getStyle('A2:'.$sheet->getHighestColumn().'2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('8ac6ff');
        $sheet->getStyle('A:'.$sheet->getHighestColumn())->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        return [
            1    => ['font' => ['bold' => true]],
            2    => ['font' => ['bold' => true]]
        ];
    }
}