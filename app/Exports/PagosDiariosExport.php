<?php

namespace App\Exports;

use App\PagosContratas;
use Maatwebsite\Excel\Concerns\FromCollection;
use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class PagosDiariosExport implements WithEvents
{

    public $fechas;

    function __construct($fechas)
    {
        $this->fechas = $fechas;    
    }

    public function registerEvents(): array
    {
        $style = [
            'font' => [
                'bold' => true
            ],
            "alignment" => [
                "horizontal" => Alignment::HORIZONTAL_CENTER,
                "vertical" => Alignment::VERTICAL_CENTER
            ]
        ];

        $arrayFechas = $this->fechas;
        return [
            AfterSheet::class => function (AfterSheet $event) use ($arrayFechas) {

                for($i = 1; $i<12; $i++)
                {
                    $event->sheet->getRowDimension($i)->setRowHeight(24);
                }
                $event->sheet->getColumnDimension("A")->setWidth(5.6);
                $event->sheet->getColumnDimension("C")->setWidth(5.6);
                $event->sheet->getColumnDimension("E")->setWidth(5.6);
                $event->sheet->getColumnDimension("G")->setWidth(5.6);
                $event->sheet->getColumnDimension("I")->setWidth(5.6);
                $event->sheet->getColumnDimension("K")->setWidth(5.6);
                $event->sheet->getColumnDimension("M")->setWidth(5.6);
                $event->sheet->getColumnDimension("O")->setWidth(5.6);

                $event->sheet->getColumnDimension("B")->setWidth(13.71);
                $event->sheet->getColumnDimension("D")->setWidth(13.71);
                $event->sheet->getColumnDimension("F")->setWidth(13.71);
                $event->sheet->getColumnDimension("H")->setWidth(13.71);
                $event->sheet->getColumnDimension("J")->setWidth(13.71);
                $event->sheet->getColumnDimension("L")->setWidth(13.71);
                $event->sheet->getColumnDimension("N")->setWidth(13.71);
                $event->sheet->getColumnDimension("P")->setWidth(13.71);

                $event->sheet->mergeCells('A1:B1');
                $event->sheet->mergeCells('C1:D1');
                $event->sheet->mergeCells('E1:F1');
                $event->sheet->mergeCells('G1:H1');
                $event->sheet->mergeCells('I1:J1');
                $event->sheet->mergeCells('K1:L1');
                $event->sheet->mergeCells('M1:N1');
                $event->sheet->mergeCells('O1:P1');

                $months = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio" , "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

                $chars = [ 0 => ["A","C","E","G","I","K","M","O"],
                           1  => ["B","D","F","H","J","L","N","P"]];

                $fontStyle = [
                                'bold'  => true,
                                'name'  => 'Verdana'
                            ];
                
                $lastRow = 11;
                $rowIndex = 1;
                $charIndex = 0;

                for($i = 0; $i<80; $i++)
                {
                    $cellPrimary = $chars[0][$charIndex].$rowIndex;
                    $cellSecondary = $chars[1][$charIndex].$rowIndex;

                    if($i < sizeof($arrayFechas))
                    {
                        $date = Carbon::createFromFormat('Y-m-d',$arrayFechas[$i]['fecha_pago']);

                        if($i == 0)
                        {
                            $actualMonth = $date->month;
                        }
    
                        if($rowIndex == 1){
                            $fontStyle['size'] = 12;
                            $event->sheet->setCellValue($cellPrimary, $months[$date->month-1]);
                            $event->sheet->getStyle($cellPrimary)->applyFromArray(["font" => $fontStyle]);
                            $i--;
                        }
                        else{
                            $event->sheet->setCellValue($cellPrimary, intval($date->format("d")));
                        }
                        
                        if($rowIndex != 1 && $date->month != $actualMonth)
                        {
                            if($rowIndex == 2)
                            {
                                $event->sheet->setCellValue($chars[0][$charIndex]."1", $months[$date->month-1]);
                                $event->sheet->getStyle($chars[0][$charIndex]."1")->applyFromArray(["font" => $fontStyle]);
                            }
                            else
                            {
                                $fontStyle['color'] = ['rgb' => '999999'];
                                $fontStyle['size'] = 10;
    
                                $event->sheet->setCellValue($cellSecondary, $months[$date->month-1]);
                                $event->sheet->getStyle($cellSecondary)->applyFromArray(["font" => $fontStyle]);
    
                                unset($fontStyle['color']);
                            }
                            
                            $actualMonth = $date->month;
                        }
    
                        
                    }
                    else
                    {
                        if($rowIndex == 1){
                            $i--;
                        }
                        else{
                            $event->sheet->setCellValue($cellPrimary, "X");
                        }

                    }


                    if($lastRow == $rowIndex)
                    {
                        $rowIndex = 1;
                        $charIndex++;
                    }
                    else
                    {
                        $rowIndex++;
                    }
                }
            },
        ];
    }
}
