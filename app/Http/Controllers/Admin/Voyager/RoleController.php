<?php

namespace App\Http\Controllers\Admin\Voyager;

use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;
use App\CustomClasses\ValidateData;
use Illuminate\Database\QueryException;
use TCG\Voyager\Events\BreadDataDeleted;
use App\Http\Controllers\Admin\CustomVoyagerBaseController;

class RoleController extends CustomVoyagerBaseController
{
    //***************************************
    //                ______
    //               |  ____|
    //               | |__
    //               |  __|
    //               | |____
    //               |______|
    //
    //  Edit an item of our Data Type BR(E)AD
    //
    //****************************************

    public function edit(Request $request, $id)
    {
        $slug = $this->getSlug($request);
        $read='';
        if(!is_numeric($id)){
            $param=explode('&',$id);
            $id=$param[0];
            $read=$param[1];
                }
        // check record is exist and belong to  current user merchant or not
        $dataValidation = new ValidateData;
        $validateResult = $dataValidation->_checkRecordExistAndRecordOfMerchant($slug,$id);
        if(isset($validateResult) && isset($validateResult['status'])>0){
            $redirect = redirect()->route("voyager.{$slug}.index");
            return $redirect->with([
                'message'    => $validateResult['message'],
                'alert-type' => $validateResult['alert-type'],
            ]);
        }

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);

            // Use withTrashed() if model uses SoftDeletes and if toggle is selected
            if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
                $model = $model->withTrashed();
            }
            if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
                $model = $model->{$dataType->scope}();
            }
            $dataTypeContent = call_user_func([$model, 'findOrFail'], $id);
        } else {
            // If Model doest exist, get data from table name
            $dataTypeContent = DB::table($dataType->name)->where('id', $id)->first();
        }

        foreach ($dataType->editRows as $key => $row) {
            $dataType->editRows[$key]['col_width'] = isset($row->details->width) ? $row->details->width : 100;
        }

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'edit');

        // Check permission
        $this->authorize('edit', $dataTypeContent);

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        // Eagerload Relations
        $this->eagerLoadRelations($dataTypeContent, $dataType, 'edit', $isModelTranslatable);

        $view = 'voyager::bread.edit-add';

        if (view()->exists("voyager::$slug.edit-add")) {
            $view = "voyager::$slug.edit-add";
        }

        //get all merchant
        // $merchantList = Merchant::select("id", "name")->get();

        return Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable','read'));
    }

    //***************************************
    //
    //                   /\
    //                  /  \
    //                 / /\ \
    //                / ____ \
    //               /_/    \_\
    //
    //
    // Add a new item of our Data Type BRE(A)D
    //
    //****************************************

    public function create(Request $request)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        $dataTypeContent = (strlen($dataType->model_name) != 0)
                            ? new $dataType->model_name()
                            : false;

        foreach ($dataType->addRows as $key => $row) {
            $dataType->addRows[$key]['col_width'] = $row->details->width ?? 100;
        }

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'add');

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        // Eagerload Relations
        $this->eagerLoadRelations($dataTypeContent, $dataType, 'add', $isModelTranslatable);

        $view = 'voyager::bread.edit-add';

        if (view()->exists("voyager::$slug.edit-add")) {
            $view = "voyager::$slug.edit-add";
        }

        //get all merchant
        // $merchantList = Merchant::select("id", "name")->get();

        return Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable'));
    }
    
    // POST BR(E)AD
    public function update(Request $request, $id)
    {
        $slug = $this->getSlug($request);
        if(!is_numeric($id)){
            $param=explode('&',$id);
            $id=$param[0];
            $read=$param[1];
        }
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('edit', app($dataType->model_name));

        //Validate fields
        $val = $this->validateBread($request->all(), $dataType->editRows, $dataType->name, $id)->validate();

        $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);
        $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

        $data->permissions()->sync($request->input('permissions', []));

        if(isset($read)){
            return redirect()->route('voyager.'.$dataType->slug.'.show', $id)
                            ->with([
                                'message'    => __('voyager::generic.successfully_updated')." {$dataType->getTranslatedAttribute('display_name_singular')}",
                                'alert-type' => 'success',
                            ]); 
        }
        return redirect()
            ->route("voyager.{$dataType->slug}.index")
            ->with([
                'message'    => __('voyager::generic.successfully_updated')." {$dataType->getTranslatedAttribute('display_name_singular')}",
                'alert-type' => 'success',
            ]);
    }

    // POST BRE(A)D
    public function store(Request $request)
    {
        try {
            $slug = $this->getSlug($request);

            $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

            // Check permission
            $this->authorize('add', app($dataType->model_name));

            //Validate fields
            $val = $this->validateBread($request->all(), $dataType->addRows)->validate();

            $data = new $dataType->model_name();
            $this->insertUpdateData($request, $slug, $dataType->addRows, $data);

            $data->permissions()->sync($request->input('permissions', []));

            return redirect()
                ->route("voyager.{$dataType->slug}.index")
                ->with([
                    'message'    => __('voyager::generic.successfully_added_new')." {$dataType->getTranslatedAttribute('display_name_singular')}",
                    'alert-type' => 'success',
                ]);
        } catch (QueryException $e) {

            return redirect()->back()->withErrors($e->getPrevious()->getMessage());
        }
    }

    //***************************************
    //                _____
    //               |  __ \
    //               | |  | |
    //               | |  | |
    //               | |__| |
    //               |_____/
    //
    //         Delete an item BREA(D)
    //
    //****************************************

    public function destroy(Request $request, $id)
    {
        try {
            $slug = $this->getSlug($request);

            // check record is exist and belong to  current user merchant or not
            $dataValidation = new ValidateData;
            $validateResult = $dataValidation->_checkRecordExistAndRecordOfMerchant($slug,$id);
            if(isset($validateResult) && isset($validateResult['status'])>0){
                $redirect = redirect()->route("voyager.{$slug}.index");
                return $redirect->with([
                    'message'    => $validateResult['message'],
                    'alert-type' => $validateResult['alert-type'],
                ]);
            }

            $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

            // Init array of IDs
            $ids = [];
            if (empty($id)) {
                // Bulk delete, get IDs from POST
                $ids = explode(',', $request->ids);
            } else {
                // Single item delete, get ID from URL
                $ids[] = $id;
            }
            foreach ($ids as $id) {
                $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);

                // Check permission
                $this->authorize('delete', $data);

                $model = app($dataType->model_name);
                if (!($model && in_array(SoftDeletes::class, class_uses_recursive($model)))) {
                    $this->cleanup($dataType, $data);
                }
            }

            $displayName = count($ids) > 1 ? $dataType->getTranslatedAttribute('display_name_plural') : $dataType->getTranslatedAttribute('display_name_singular');

            $res = $data->destroy($ids);
            $data = $res
                ? [
                    'message'    => __('voyager::generic.successfully_deleted')." {$displayName}",
                    'alert-type' => 'success',
                ]
                : [
                    'message'    => __('voyager::generic.error_deleting')." {$displayName}",
                    'alert-type' => 'error',
                ];

            if ($res) {
                event(new BreadDataDeleted($dataType, $data));
            }

            return redirect()->route("voyager.{$dataType->slug}.index")->with($data);
        } catch (QueryException $e) {

            return redirect()->route("voyager.{$dataType->slug}.index")->with([
                'message'    => __('voyager::generic.error_deleting')." record is being used in another table.",
                //'message'    => __('voyager::generic.error_deleting')." {$e->getPrevious()->getMessage()}",
                'alert-type' => 'error',
            ]);
        }
    }
}
