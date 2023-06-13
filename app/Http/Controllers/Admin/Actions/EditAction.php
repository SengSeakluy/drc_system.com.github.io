<?php

namespace App\Http\Controllers\Admin\Actions;

use TCG\Voyager\Actions\AbstractAction;

class EditAction extends AbstractAction
{
    public function getTitle()
    {
        return __('voyager::generic.edit');
    }

    public function getText(){
        return ('Edit');
    }

    public function getIcon()
    {
        return 'voyager-edit';
    }

    public function getPolicy()
    {
        return 'edit';
    }

    public function getAttributes()
    {
        return [
            'class' => ' pull-right edit',
            'style' => 'color:#ffff;background-color:#0D6EFD;padding:6px 16px;border-radius:7px;',
        ];
    }

    public function getDefaultRoute()
    {
        return route('voyager.'.$this->dataType->slug.'.edit', $this->data->{$this->data->getKeyName()});
    }
}
