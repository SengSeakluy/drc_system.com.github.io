<?php

namespace App\Http\Controllers\Admin\Actions;

use TCG\Voyager\Actions\AbstractAction;

class RestoreAction extends AbstractAction
{
    public function getTitle()
    {
        return __('voyager::generic.restore');
    }

    public function getText(){
        return ('Restore');
    }

    public function getIcon()
    {
        return 'voyager-refresh';
    }

    public function getPolicy()
    {
        return 'restore';
    }

    public function getAttributes()
    {
        return [
            'class'   => 'pull-right restore',
            'data-id' => $this->data->{$this->data->getKeyName()},
            'id'      => 'restore-'.$this->data->{$this->data->getKeyName()},
            'style'   => 'color:#ffff;background-color:#28A745;padding:6px 16px;border-radius:7px;',
        ];
    }

    public function getDefaultRoute()
    {
        return route('voyager.'.$this->dataType->slug.'.restore', $this->data->{$this->data->getKeyName()});
    }
}
