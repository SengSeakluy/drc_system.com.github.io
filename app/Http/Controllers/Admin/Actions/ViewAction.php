<?php

namespace App\Http\Controllers\Admin\Actions;

use TCG\Voyager\Actions\AbstractAction;

class ViewAction extends AbstractAction
{
    public function getTitle()
    {
        return __('voyager::generic.view');
    }

    public function getText(){
        return ('View');
    }

    public function getIcon()
    {
        return 'voyager-eye';
    }

    public function getPolicy()
    {
        return 'read';
    }

    public function getAttributes()
    {
        return [
            'class' => ' pull-right view',
            'style'   => 'color:#ffff;background-color:#FFC107;padding:6px 16px;border-radius:7px;',
        ];
    }

    public function getDefaultRoute()
    {
        return route('voyager.'.$this->dataType->slug.'.show', $this->data->{$this->data->getKeyName()});
    }
}
