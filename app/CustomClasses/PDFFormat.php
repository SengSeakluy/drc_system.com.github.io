<?php

namespace App\CustomClasses;

use TCG\Voyager\Facades\Voyager;

class PDFFormat {

    public function init()
    {
        set_time_limit(0);
        ini_set('memory_limit', '256M');
    }

    public function __construct(){
        
    }

    public function preparePDFFormat($request, $slug){
        $multiFilter = new MultiFilter;
        $fieldsOfTable = new FieldsOfTable;
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        $fieldsOfTable = $fieldsOfTable->getFieldsOfTable($request, $slug);
        $fields = $fieldsOfTable['fields'];
        $column_name = $fieldsOfTable['column_name'];
        $field_relationship = $fieldsOfTable['field_relationship'];
        
        $filter_data = $multiFilter->filter($request, $fields, $dataType->model_name, $field_relationship);
        
        $output=' 
            <style>
                th,td {font-size:14px;border:1px solid;padding:5px; }
                table{ width:100%;border-collapse:collapse;border:0px;}
            </style> 
            <h2 align="center">'.ucfirst($slug).'</h2>
                <table width="100%">
                    <thead style="background-color: #8ac6ff;">
                        <tr>';
                        $output .= "<th>No.</th>";
                    for($i = 0; $i < count($column_name); $i++) {
                        $output .= "<th>".$column_name[$i]."</th>";
                    }
                    $output .= "</tr>";
                    $output .= "</thead>";
                    foreach ($filter_data as $key => $row){
                        $output.='<tr>';
                        $output.='<td">'.($key + 1).'</td>';
                        for($j = 0; $j < count($fields); $j++) {
                            $value = (isset($row->{$fields[$j]}) && $row->{$fields[$j]} !== '') ? $row->{$fields[$j]} : 'N/A' ;
                            $output .= "<td>".$value."</td>";
                        }
                        $output.='</tr>';
                    }
                    $output.='</table>';
                return $output;
    }
}