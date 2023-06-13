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

class customerTransactionExport implements  
                            FromCollection,
                            ShouldAutoSize,
                            WithHeadings,
                            WithStyles,
                            WithTitle,
                            Responsable,
                            WithColumnWidths,
                            WithEvents,
                            WithCustomStartCell
{
    use Exportable;
    protected $datas;
    protected $header;
    protected $date;
    protected $user;
    protected $num_row;

    public function __construct(array $datas  ,$header,$date,$user,$amount)
    {
        $this->datas = $datas;
        $this->num_row = count($datas) + 16;
        $this->header = $header;
        $this->date = $date;
        $this->user = $user;
        $this->amount = $amount;
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

        return collect($this->datas);
    }
    public function title(): string
    {
        return 'statement';
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A15:E15')->getFont()->setSize(12);
        $sheet->getStyle('A14')->getFont()->setSize(14);
        $sheet->getStyle('C3')->getFont()->setSize(22);
        $sheet->getStyle('A'.$this->num_row)->getFont()->setSize(12);
         $sheet->mergeCells('A1:A5');
         $sheet->mergeCells('C3:D3');
         $sheet->mergeCells('C4:D4');
         $sheet->mergeCells('C5:D5');
         $sheet->mergeCells('C8:D8');
         $sheet->mergeCells('A14:D14');
        $sheet->getStyle('1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C3:C5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('D15')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('D9:D11')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('D'.$this->num_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('A14:D14')->applyFromArray([
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['argb' => '0001230'],
                ],
            ]
        ]);
        $sheet->getStyle('C8:D8')->applyFromArray([
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['argb' => '0001230'],
                ],
            ]
        ]);
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
            3    => ['font' => ['bold' => true]],
            15   => ['font' => ['bold' => true]],
            8   => ['font' => ['bold' => true]],
            14   => ['font' => ['bold' => true]],
            $this->num_row   => ['font' => ['bold' => true]],
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 27,
            'B' => 90,            
            'D' => 15,           
        ];
    }
    
    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event){
                $event->sheet->setCellValue('C3' , 'PAYMENT STATEMENT');
                $event->sheet->setCellValue('C4' , 'For period: '.$this->date['date_from'].'-'.$this->date['date_to']);
                $event->sheet->setCellValue('C5' , 'Generated: '.$this->date['generated_at']);
                $event->sheet->setCellValue('C8' , 'ACCOUNT DETAILS');
                $event->sheet->setCellValue('C9' , 'First Name');
                $event->sheet->setCellValue('D9' , $this->user['first_name']);
                $event->sheet->setCellValue('C10' , 'Last Name');
                $event->sheet->setCellValue('D10' , $this->user['last_name']);
                $event->sheet->setCellValue('C11' , 'Phone Number');
                $event->sheet->setCellValue('D11' , ' '.$this->user['phone']);
                $event->sheet->setCellValue('A14' , 'ACTIVITY HISTORY');
            },
            AfterSheet::class => function(AfterSheet $event) {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo');
                $drawing->setPath(public_path('logo/z1.png'));
                $drawing->setHeight(80);
                $drawing->setOffsetX(5);
                $drawing->setOffsetY(5);
                $drawing->setCoordinates('A2');
                $drawing->setWorksheet($event->sheet->getDelegate());
                $event->sheet->setCellValue('A'.$this->num_row , 'Total Amount');
                $event->sheet->setCellValue('D'.$this->num_row , $this->amount.' USD');
            },
        ];
    }
    public function startCell(): string
    {
        return 'A15';
    }
}
