<?php

namespace App\CustomClasses;

use TCG\Voyager\Facades\Voyager;

class FieldsOfTable {
    public function __construct(){
        
    }

    public function getFieldsOfTable($request, $slug){
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        
        $field_relationship = [];
        $fields = [];
        $column_name = [];
        // loop through all enable column
        foreach($dataType->browseRows as $field) {
            // store field that is relationship field for use later
            if($field->type === 'relationship' && $field->field !== 'deleted_at'){
                $field_relationship[((array) $field->details)['column']] = $field->details;
                $fields[] = ((array) $field->details)['column'];
                $column_name[] = $field->getTranslatedAttribute('display_name');
            } else if($field->field !== 'deleted_at') {   // skip delete_at column
                $fields[] = $field->field;
                $column_name[] = $field->getTranslatedAttribute('display_name');
            }
        }

        return ["column_name" => $column_name, "fields" => $fields, "field_relationship" => $field_relationship];
    }
}