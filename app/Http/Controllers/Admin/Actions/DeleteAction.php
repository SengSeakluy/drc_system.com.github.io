<?php

namespace App\Http\Controllers\Admin\Actions;

use TCG\Voyager\Actions\AbstractAction;

class DeleteAction extends AbstractAction
{
    public function getTitle()
    {
    
        return __('voyager::generic.delete');
    }

    public function getText(){
        return ('Delete');
    }
    
    public function getIcon()
    {
        return 'voyager-trash';
    }

    public function getPolicy()
    {
        return 'delete';
    }

    public function getAttributes()
    {
        return [
            'class'   => 'pull-right delete',
            'data-id' => $this->data->{$this->data->getKeyName()},
            'style'   => 'color:#ffff;background-color:#DC3545;padding:6px 16px;border-radius:7px;',
            'id'      => 'delete-'.$this->data->{$this->data->getKeyName()},
        ];
    }

    public function getDefaultRoute()
    {
        return 'javascript:;';
    }
}

