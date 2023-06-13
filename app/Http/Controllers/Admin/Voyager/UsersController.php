<?php

namespace App\Http\Controllers\Admin\Voyager;

use App\Models\Admin\User;
use ReflectionClass;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use TCG\Voyager\Models\Role;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Events\BreadAdded;
use App\CustomClasses\ValidateData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use TCG\Voyager\Database\Types\Type;
use TCG\Voyager\Events\BreadDeleted;
use TCG\Voyager\Events\BreadUpdated;
use App\Mail\sendEmailRandomPassword;
use TCG\Voyager\Database\Schema\Table;
use TCG\Voyager\Events\BreadDataAdded;
use Illuminate\Database\QueryException;
use TCG\Voyager\Events\BreadDataDeleted;
use TCG\Voyager\Events\BreadDataUpdated;
use Illuminate\Support\Facades\Validator;
use TCG\Voyager\Database\Schema\SchemaManager;
use App\Http\Controllers\Admin\CustomVoyagerBaseController;
use TCG\Voyager\Http\Controllers\Traits\BreadRelationshipParser;

class UsersController extends CustomVoyagerBaseController
{
    use BreadRelationshipParser;
    
    public function update(Request $request, $id)
    {  
        $slug = $this->getSlug($request);
        if(!is_numeric($id)){
            $param=explode('&',$id);
            $id=$param[0];
        }
        if($request->has('change_password_only')){   
            $message=[
                'regex' => 'The :attribute must contain at least one number and both uppercase and lowercase letters and one special character.'
            ];
            $validation=Validator::make($request->all(),[
                'current_password' => ['required'],
                'new_password' => ['required','min:8','regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/'],
                'new_confirm_password' => ['required','same:new_password'],     
            ],$message);
            if($validation->fails()){
                return redirect()->back()->withErrors($validation)->withInput();
            }
            $current_password = Auth::User()->password;           
            if(Hash::check($request->current_password, $current_password))
            {          
                $user_id = Auth::User()->id;                       
                $obj_user = User::find($user_id);
                $obj_user->password = Hash::make($request->new_password);
                $obj_user->save();
                
                return redirect()->back()->with([
                    'message'    =>"Password has been change successfuly.",
                    'alert-type' => 'success',
                ]);
            }
            else
            {           
                return redirect()->back()->with([
                    'message'    =>'Please enter correct current password',
                    'alert-type' => 'error',
                ]);   
            }
           
        }
        // check whether this user has mape with merchant or not
        if(Auth::user()->id == config('common.superadmin_user') && Auth::user()->role_id == config('common.superadmin_role')){
            $result = DB::table('users')->where('id',$id)->first();
            // if($result->merchant_id != $request->merchant_id){
            //     if(isset($result) && $result->merchant_id>0){
            //         if($count_contact_created_by_user != 0 ||$count_product_created_by_user != 0||$count_price_book_created_by_user != 0||$count_category_created_by_user != 0 || $count_price_entry_created_by_user!= 0){             
            //             return redirect()->route("voyager.users.edit", ['id' => $id])->withErrors('This user has already mape with other merchant. He/She may use this account to entry data into the system. If you switch to other merchant, the data will be mess. Please Contact System Administrator.');
            //         }
            //     }
            // }
            // if($result->merchant_id != $request->get('merchant_id')){
            //     if(isset($result) && $result->merchant_id>0){
            //         return redirect()->back()->with([
            //             'message'    => 'This user has already mape with other merchant. He/She may use this account to entry data into the system. If you switch to other merchant, the data will be mess. Please Contact System Administrator.',
            //             'alert-type' => 'error',
            //         ]);
            //     }
            // }
        } else {
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

                if(count($selectedMerchantList)>0 && (isset($roleInfo->is_z1payment_technical) && $roleInfo->is_z1payment_technical === 0)){
                    if (!isset($request->merchant_id) || $request->merchant_id == 0) {
                        $redirect = redirect()->route("voyager.{$slug}.index");
                        return $redirect->with([
                            'message'    => 'This user group can only create user for merchant and being prevented from creating user for technical. Please contact system administrator for more details.',
                            'alert-type' => 'error',
                        ]);
                    }
                }
            }
        }

        if (Auth::user()->getKey() == $id) {
            $request->merge([
                'role_id'                              => Auth::user()->role_id,
                'user_belongstomany_role_relationship' => Auth::user()->roles->pluck('id')->toArray(),
            ]);
        }

        // check record is exist and belong to  current user merchant or not
        $dataValidation = new ValidateData;
        $validateResult = $dataValidation->_checkRecordExistAndRecordOfMerchant($slug,$id);
        if(isset($validateResult) && isset($validateResult['status'])>0){
            $redirect = redirect()->route("voyager.{$slug}.index");
            return $redirect->with([
                'message'    => $validateResult['message'],
                'alert-type' => 'error',
            ]);
        }

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Compatibility with Model binding.
        $id = $id instanceof \Illuminate\Database\Eloquent\Model ? $id->{$id->getKeyName()} : $id;

        $model = app($dataType->model_name);
        if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
            $model = $model->{$dataType->scope}();
        }
        if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
            $data = $model->withTrashed()->findOrFail($id);
        } else {
            $data = $model->findOrFail($id);
        }
        // Check permission
        $this->authorize('edit', $data);
        // check if user edit but password Leave empty to keep the same and clear required in validateBread
        if($request->password == ""){
            foreach($dataType->editRows->all() as $key => $val){
                if($val->field == 'password'){
                    unset($request['password']);
                    $val->required=0;
                    $val->details=null;
                }
            }
        }
        if(isset($request->parent_name)){
            foreach($dataType->editRows->all() as $key => $val){
                if($val->field == 'role_id'){
                    $val->details=null;
                    break;
                }
            }
        }
        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->editRows, $dataType->name, $id)->validate();
        $this->insertUpdateData($request, $slug, $dataType->editRows, $data);
        event(new BreadDataUpdated($dataType, $data));
        if (auth()->user()->can('browse', app($dataType->model_name))) {
            if(isset($request->parent_name) && !$request->read){
                $parent_name=explode('/',$request->parent_name);
                return redirect()->action(
                    ['App\Http\Controllers\Voyager\IntegrationController', 'show'], ['id' =>$parent_name[1]]
                )->with([
                    'message'    => __('voyager::generic.successfully_updated')." {$dataType->getTranslatedAttribute('display_name_singular')}",
                    'alert-type' => 'success',
                ]);
            }else{
                $redirect = redirect()->route("voyager.{$dataType->slug}.index");
            }
        } else {
            $redirect = redirect()->back();
        }
        if(isset($request->read)){
            if(isset($request->parent_name)){
                $redirect= redirect()->route('voyager.'.$dataType->slug.'.show', $id.'?name='.$request->parent_name); 
            }else{
                 $redirect= redirect()->route('voyager.'.$dataType->slug.'.show', $id); 
            }        }
        return $redirect->with([
            'message'    => __('voyager::generic.successfully_updated')." {$dataType->getTranslatedAttribute('display_name_singular')}",
            'alert-type' => 'success',
        ]);
    }
    /**
     * POST BRE(A)D - Store data.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));
        if(isset($request->parent_name)){
            foreach($dataType->addRows->all() as $key => $val){
                if($val->field == 'role_id'){
                    $val->details=null;
                    break;
                }
            }
        }

        // check whether this user has mape with merchant or not
        if (Auth::user()->id != config('common.superadmin_user') && Auth::user()->role_id != config('common.superadmin_role')) {
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

                if(count($selectedMerchantList)>0 && (isset($roleInfo->is_z1payment_technical) && $roleInfo->is_z1payment_technical === 0)){
                    if (!isset($request->merchant_id) || $request->merchant_id == 0) {
                        $redirect = redirect()->route("voyager.{$slug}.index");
                        return $redirect->with([
                            'message'    => 'This user group can only create user for merchant and being prevented from creating user for technical. Please contact system administrator for more details.',
                            'alert-type' => 'error',
                        ]);
                    }
                }
            }
        }
        
        //check create user for integration or user login
        if(!isset($request->parent_name)){
            //random password
            $request->merge(['password' => Str::random(10)]);
        }
        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->addRows)->validate();
        //check create user
        // if(!isset($request->parent_name)){
        //     $this->send_mail($request,$slug);
        // }

        $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new $dataType->model_name());
        if(isset($request->parent_name)){
            $user =User::find($data->id); 
                $user->integration_id = $data['integration_id' ]= $request->integration_id;
                $user->type = $data['type' ]= $request->type;
                $user->save();
        }
        event(new BreadDataAdded($dataType, $data));
        if (!$request->has('_tagging')) {
            if (auth()->user()->can('browse', $data)) {
                if(isset($request->parent_name)){
                    $parent_name=explode('/',$request->parent_name);
                    return redirect()->route("voyager.integration.show", ['id' =>$parent_name[1]]
                    )->with([
                        'message'    => __('voyager::generic.successfully_added_new')." {$dataType->getTranslatedAttribute('display_name_singular')}",
                        'alert-type' => 'success',
                    ]);
                }else{
                    $redirect = redirect()->route("voyager.{$dataType->slug}.show", ['id' => $data->id]);
                }
            } else {
                $redirect = redirect()->back();
            }

            return $redirect->with([
                'message'    => __('voyager::generic.successfully_added_new')." {$dataType->getTranslatedAttribute('display_name_singular')}",
                'alert-type' => 'success',
            ]);
        } else {
            return response()->json(['success' => true, 'data' => $data]);
        }
    }
    //***************************************
    //                ___
    //               | __\
    //               ||  ||
    //               ||__||
    //               |___/
    //
    //         Delete an item 
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
            if(isset($request->name)){
                return redirect()->route('voyager.integration.show',['id'=>explode('/',request()->name)[1]])->with($data);
            }
            if(isset($request->its_parent)){
                return redirect()->route('voyager.integration.show',['id'=>$request->its_parent])->with($data);
            }
            return redirect()->route("voyager.{$dataType->slug}.index")->with($data);
        } catch (QueryException $e) {

            return redirect()->route("voyager.{$dataType->slug}.index")->with([
                'message'    => __('voyager::generic.error_deleting')." {$e->getPrevious()->getMessage()}",
                'alert-type' => 'error',
            ]);
        }
    }
    
    //send email
    // public function send_mail($request){
    //     $to_email = $request->email;
    //     $details = [
    //         'email'=>$request->email,
    //         'password'=>$request->password
    //     ];
    //     Mail::to($to_email)->send(new sendEmailRandomPassword($details));
    //  }
}
