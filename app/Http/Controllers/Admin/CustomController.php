<?php

namespace App\Http\Controllers\Admin;

use Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use TCG\Voyager\Models\Role;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Events\FileDeleted;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use TCG\Voyager\Traits\AlertsMessages;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Bus\DispatchesJobs;
use TCG\Voyager\Http\Controllers\ContentTypes\File;
use TCG\Voyager\Http\Controllers\ContentTypes\Text;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use TCG\Voyager\Http\Controllers\ContentTypes\Checkbox;
use TCG\Voyager\Http\Controllers\ContentTypes\Password;
use TCG\Voyager\Http\Controllers\ContentTypes\Timestamp;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use TCG\Voyager\Http\Controllers\ContentTypes\Coordinates;
use TCG\Voyager\Http\Controllers\ContentTypes\Relationship;
use TCG\Voyager\Http\Controllers\ContentTypes\MultipleImage;
use TCG\Voyager\Http\Controllers\ContentTypes\SelectMultiple;
use TCG\Voyager\Http\Controllers\ContentTypes\MultipleCheckbox;
use TCG\Voyager\Http\Controllers\ContentTypes\Image as ContentImage;

abstract class CustomController extends BaseController
{
    use DispatchesJobs;
    use ValidatesRequests;
    use AuthorizesRequests;
    use AlertsMessages;

    public function getSlug(Request $request)
    {
        if (isset($this->slug)) {
            $slug = $this->slug;
        } else {
            $slug = explode('.', $request->route()->getName())[1];
        }

        return $slug;
    }

    public function insertUpdateData($request, $slug, $rows, $data)
    {
        try{
            $multi_select = [];

            // Pass $rows so that we avoid checking unused fields
            $request->attributes->add(['breadRows' => $rows->pluck('field')->toArray()]);

            /*
            * Prepare Translations and Transform data
            */
            $translations = is_bread_translatable($data)
                            ? $data->prepareTranslations($request)
                            : [];

            foreach ($rows as $row) {
                // if the field for this row is absent from the request, continue
                // checkboxes will be absent when unchecked, thus they are the exception
                if (!$request->hasFile($row->field) && !$request->has($row->field) && $row->type !== 'checkbox') {
                    // if the field is a belongsToMany relationship, don't remove it
                    // if no content is provided, that means the relationships need to be removed
                    if (isset($row->details->type) && $row->details->type !== 'belongsToMany') {
                        continue;
                    }
                }

                // Value is saved from $row->details->column row
                if ($row->type == 'relationship' && $row->details->type == 'belongsTo') {
                    continue;
                }

                $content = $this->getContentBasedOnType($request, $slug, $row, $row->details);

                if ($row->type == 'relationship' && $row->details->type != 'belongsToMany') {
                    $row->field = @$row->details->column;
                }

                /*
                * merge ex_images/files and upload images/files
                */
                if (in_array($row->type, ['multiple_images', 'file']) && !is_null($content)) {
                    if (isset($data->{$row->field})) {
                        $ex_files = json_decode($data->{$row->field}, true);
                        if (!is_null($ex_files)) {
                            $content = json_encode(array_merge($ex_files, json_decode($content)));
                        }
                    }
                }

                if (is_null($content)) {

                    // If the image upload is null and it has a current image keep the current image
                    if ($row->type == 'image' && is_null($request->input($row->field)) && isset($data->{$row->field})) {
                        $content = $data->{$row->field};
                    }

                    // If the multiple_images upload is null and it has a current image keep the current image
                    if ($row->type == 'multiple_images' && is_null($request->input($row->field)) && isset($data->{$row->field})) {
                        $content = $data->{$row->field};
                    }

                    // If the file upload is null and it has a current file keep the current file
                    if ($row->type == 'file') {
                        $content = $data->{$row->field};
                        if (!$content) {
                            $content = json_encode([]);
                        }
                    }

                    if ($row->type == 'password') {
                        $content = $data->{$row->field};
                    }
                }

                if ($row->type == 'relationship' && $row->details->type == 'belongsToMany') {
                    // Only if select_multiple is working with a relationship
                    $multi_select[] = [
                        'model'           => $row->details->model,
                        'content'         => $content,
                        'table'           => $row->details->pivot_table,
                        'foreignPivotKey' => $row->details->foreign_pivot_key ?? null,
                        'relatedPivotKey' => $row->details->related_pivot_key ?? null,
                        'parentKey'       => $row->details->parent_key ?? null,
                        'relatedKey'      => $row->details->key,
                    ];
                } else {
                    $data->{$row->field} = $content;
                }
            }

            // check insert or update
            // post for insert record
            // put for update record (use case else in condition)
            //dd(Auth::user()->merchant_id);

            if (Auth::user()->merchant_id == 0) {
                // filter data based on selected merchant
                $roleInfo = Role::where('id', Auth::user()->role_id)->first();

                // if has configure merchant filter data
                $selectedMerchantList = [];
                if ($roleInfo->merchant_list) {
                    foreach ((array) json_decode($roleInfo->merchant_list) as $index_merchant => $merchant) {
                        $selectedMerchantList[] = $index_merchant;
                    }
                }
            }

            //Mark: insert
            if($request->isMethod('post')){
                if(Auth::user()->id == config('common.superadmin_user') && Auth::user()->role_id == config('common.superadmin_role') || (isset($roleInfo->is_z1payment_technical) && $roleInfo->is_z1payment_technical>0)){
                    if(!in_array($slug,array('users','merchant','currency'))){
                        if(Schema::hasColumn($slug, 'merchant_id')){
                            $data->{"merchant_id"} = $request->get('merchant_id');
                        }
                    }
                    /**NOTE:
                     * Prevent merchant_id insert into reason
                     * check if reason code exists and unset merchant id
                    **/
                    if ($data->code && !Schema::hasColumn($slug, 'merchant_id')) unset($data->merchant_id);

                    /**NOTE:
                     * Prevent merchant_id insert into reason_children
                     * check if sub reason and unset merchant id
                    **/
                    if ($data->reason_id) if ($data->merchant_id) unset($data->merchant_id);

                    if($slug == 'integration'){
                        if(Schema::hasColumn($slug, 'secret_key')){
                            $data->{"secret_key"} = Str::random(16);
                        }
                    }

                    //get merchant list
                    $merchantList = $request->input('merchant', []);
                    if ($slug == 'roles') {
                        if (Schema::hasColumn($slug, 'merchant_list')) {
                            $data->{"merchant_list"} = json_encode($merchantList);
                        }
                    }

                }else{
                    if (Schema::hasColumn($slug, 'merchant_id') && Auth::user()->merchant_id>0) {
                        $data->{"merchant_id"} = Auth::user()->merchant_id;
                    } else {
                        if(Schema::hasColumn($slug, 'merchant_id')){
                            $data->{"merchant_id"} = $request->get('merchant_id');
                        }
                    }
                }

                // generate uuid and auto save when field uuid exist in Schema
                if(Schema::hasColumn($slug, 'uuid')){
                    $uuid = Str::uuid()->toString();
                    $data->{"uuid"} = $uuid;
                }

                if(Schema::hasColumn($slug, 'created_by') && Auth::user()->id>0){
                    $data->{"created_by"} = Auth::user()->id;
                }

                if(Schema::hasColumn($slug, 'created_at')){
                    $data->{"created_at"} = date('Y-m-d H:i:s');
                }
                if(Schema::hasTable($slug)){
                    if(Schema::hasColumn($slug, 'merchant_number')){
                        $data->{"merchant_number"} = "SRB-".Date("Ymdhis");
                    }
                }
                if(Schema::hasColumn($slug, 'is_merchant_contract')){
                    if ($data->{"brand_id"}) $data->{"is_merchant_contract"} = 1;
                }

                if(Schema::hasColumn($slug, 'brand_id')){
                    $data->{"brand_id"} = $request->brand_id;
                }
            }else{
                //Mark: Update
                if(Auth::user()->id == config('common.superadmin_user') && Auth::user()->role_id == config('common.superadmin_role') || (isset($roleInfo->is_z1payment_technical) && $roleInfo->is_z1payment_technical>0)){
                    if(!in_array($slug,array('users','merchant','currency'))){
                        if(Schema::hasColumn($slug, 'merchant_id')){
                            $data->{"merchant_id"} = $request->get('merchant_id');
                        }
                    }

                    /**NOTE:
                     * Prevent merchant_id insert into reason
                     * check if reason code exists and unset merchant id
                    **/
                    if ($data->code && !Schema::hasColumn($slug, 'merchant_id')){
                        if ($data->merchant_id) unset($data->merchant_id);
                    }

                    /**NOTE:
                     * Prevent merchant_id insert into reason_children
                     * check if sub reason and unset merchant id
                    **/
                    if ($data->reason_id && !Schema::hasColumn($slug, 'merchant_id')){
                        if ($data->merchant_id) unset($data->merchant_id);
                    }

                    //get merchant list
                    $merchantList = $request->input('merchant', []);
                    if ($slug == 'roles') {
                        if (Schema::hasColumn($slug, 'merchant_list')) {
                            $data->{"merchant_list"} = json_encode($merchantList);
                        }
                    }

                }else{
                    if (Schema::hasColumn($slug, 'merchant_id') && Auth::user()->merchant_id>0) {
                        $data->{"merchant_id"} = Auth::user()->merchant_id;
                    } else {
                        if(Schema::hasColumn($slug, 'merchant_id')){
                            $data->{"merchant_id"} = $request->get('merchant_id');
                        }
                    }
                }

                if(Schema::hasColumn($slug, 'updated_by') && Auth::user()->id>0){
                    $data->{"updated_by"} = Auth::user()->id;
                }

                if(Schema::hasColumn($slug, 'modified_by') && Auth::user()->id>0){
                    $data->{"modified_by"} = Auth::user()->id;
                }

                if(Schema::hasColumn($slug, 'modified_date')){
                    $data->{"modified_date"} = date('Y-m-d H:i:s');
                }

                if(Schema::hasColumn($slug, 'updated_at')){
                    $data->{"updated_at"} = date('Y-m-d H:i:s');
                }
            }

            if (isset($data->additional_attributes)) {
                foreach ($data->additional_attributes as $attr) {
                    if ($request->has($attr)) {
                        $data->{$attr} = $request->{$attr};
                    }
                }
            }

            $data->save();

            // Save translations
            if (count($translations) > 0) {
                $data->saveTranslations($translations);
            }

            foreach ($multi_select as $sync_data) {
                $data->belongsToMany(
                    $sync_data['model'],
                    $sync_data['table'],
                    $sync_data['foreignPivotKey'],
                    $sync_data['relatedPivotKey'],
                    $sync_data['parentKey'],
                    $sync_data['relatedKey']
                )->sync($sync_data['content']);
            }

            // Rename folders for newly created data through media-picker
            if ($request->session()->has($slug.'_path') || $request->session()->has($slug.'_uuid')) {
                $old_path = $request->session()->get($slug.'_path');
                $uuid = $request->session()->get($slug.'_uuid');
                $new_path = str_replace($uuid, $data->getKey(), $old_path);
                $folder_path = substr($old_path, 0, strpos($old_path, $uuid)).$uuid;

                $rows->where('type', 'media_picker')->each(function ($row) use ($data, $uuid) {
                    $data->{$row->field} = str_replace($uuid, $data->getKey(), $data->{$row->field});
                });
                $data->save();
                if ($old_path != $new_path && !Storage::disk(config('voyager.storage.disk'))->exists($new_path)) {
                    $request->session()->forget([$slug.'_path', $slug.'_uuid']);
                    Storage::disk(config('voyager.storage.disk'))->move($old_path, $new_path);
                    Storage::disk(config('voyager.storage.disk'))->deleteDirectory($folder_path);
                }
            }

            return $data;
        } catch (QueryException $e) {
            return redirect()->back()->withErrors("Please Review Error On This Page");
        }
    }

    /**
     * Validates bread POST request.
     *
     * @param array  $data The data
     * @param array  $rows The rows
     * @param string $slug Slug
     * @param int    $id   Id of the record to update
     *
     * @return mixed
     */
    public function validateBread($data, $rows, $name = null, $id = null)
    {
        $rules = [];
        $messages = [];
        $customAttributes = [];
        $is_update = $name && $id;

        $fieldsWithValidationRules = $this->getFieldsWithValidationRules($rows);

        foreach ($fieldsWithValidationRules as $field) {
            $fieldRules = $field->details->validation->rule;
            $fieldName = $field->field;

            //dismiss validation rule for admin user of each merchant because it has already detected
            if(Auth::user()->id != config('common.superadmin_user') && Auth::user()->role_id != config('common.superadmin_role'))
                if($field->field == 'merchant_id')
                    continue;


            // Show the field's display name on the error message
            if (!empty($field->display_name)) {
                if (!empty($data[$fieldName]) && is_array($data[$fieldName])) {
                    foreach ($data[$fieldName] as $index => $element) {
                        if ($element instanceof UploadedFile) {
                            $name = $element->getClientOriginalName();
                        } else {
                            $name = $index + 1;
                        }

                        $customAttributes[$fieldName.'.'.$index] = $field->getTranslatedAttribute('display_name').' '.$name;
                    }
                } else {
                    $customAttributes[$fieldName] = $field->getTranslatedAttribute('display_name');
                }
            }

            // If field is an array apply rules to all array elements
            $fieldName = !empty($data[$fieldName]) && is_array($data[$fieldName]) ? $fieldName.'.*' : $fieldName;

            // Get the rules for the current field whatever the format it is in
            $rules[$fieldName] = is_array($fieldRules) ? $fieldRules : explode('|', $fieldRules);

            if ($id && property_exists($field->details->validation, 'edit')) {
                $action_rules = $field->details->validation->edit->rule;
                $rules[$fieldName] = array_merge($rules[$fieldName], (is_array($action_rules) ? $action_rules : explode('|', $action_rules)));
            } elseif (!$id && property_exists($field->details->validation, 'add')) {
                $action_rules = $field->details->validation->add->rule;
                $rules[$fieldName] = array_merge($rules[$fieldName], (is_array($action_rules) ? $action_rules : explode('|', $action_rules)));
            }
            // Fix Unique validation rule on Edit Mode
            if ($is_update) {
                foreach ($rules[$fieldName] as &$fieldRule) {
                    if (strpos(strtoupper($fieldRule), 'UNIQUE') !== false) {
                        $fieldRule = \Illuminate\Validation\Rule::unique($name)->ignore($id);
                    }
                }
            }

            // Set custom validation messages if any
            if (!empty($field->details->validation->messages)) {
                foreach ($field->details->validation->messages as $key => $msg) {
                    $messages["{$field->field}.{$key}"] = $msg;
                }
            }
        }

        return Validator::make($data, $rules, $messages, $customAttributes);
    }

    public function getContentBasedOnType(Request $request, $slug, $row, $options = null)
    {
        switch ($row->type) {
            /********** PASSWORD TYPE **********/
            case 'password':
                return (new Password($request, $slug, $row, $options))->handle();
            /********** CHECKBOX TYPE **********/
            case 'checkbox':
                return (new Checkbox($request, $slug, $row, $options))->handle();
            /********** MULTIPLE CHECKBOX TYPE **********/
            case 'multiple_checkbox':
                return (new MultipleCheckbox($request, $slug, $row, $options))->handle();
            /********** FILE TYPE **********/
            case 'file':
                return (new File($request, $slug, $row, $options))->handle();
            /********** MULTIPLE IMAGES TYPE **********/
            case 'multiple_images':
                return (new MultipleImage($request, $slug, $row, $options))->handle();
            /********** SELECT MULTIPLE TYPE **********/
            case 'select_multiple':
                return (new SelectMultiple($request, $slug, $row, $options))->handle();
            /********** IMAGE TYPE **********/
            case 'image':
                return (new ContentImage($request, $slug, $row, $options))->handle();
            /********** DATE TYPE **********/
            case 'date':
            /********** TIMESTAMP TYPE **********/
            case 'timestamp':
                return (new Timestamp($request, $slug, $row, $options))->handle();
            /********** COORDINATES TYPE **********/
            case 'coordinates':
                return (new Coordinates($request, $slug, $row, $options))->handle();
            /********** RELATIONSHIPS TYPE **********/
            case 'relationship':
                return (new Relationship($request, $slug, $row, $options))->handle();
            /********** ALL OTHER TEXT TYPE **********/
            default:
                return (new Text($request, $slug, $row, $options))->handle();
        }
    }

    public function deleteFileIfExists($path)
    {
        if (Storage::disk(config('voyager.storage.disk'))->exists($path)) {
            Storage::disk(config('voyager.storage.disk'))->delete($path);
            event(new FileDeleted($path));
        }
    }

    /**
     * Get fields having validation rules in proper format.
     *
     * @param array $fieldsConfig
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getFieldsWithValidationRules($fieldsConfig)
    {
        return $fieldsConfig->filter(function ($value) {
            if (empty($value->details)) {
                return false;
            }

            return !empty($value->details->validation->rule);
        });
    }
}
