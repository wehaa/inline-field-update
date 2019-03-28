<?php

namespace Wehaa\Liveupdate;

use Laravel\Nova\Fields\Field;
use Config;

class Liveupdate extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'liveupdate';

    protected function resolveAttribute($resource, $attribute)
    {
        $this->setResourceId(data_get($resource, 'id'));

        return parent::resolveAttribute($resource, $attribute);
    }

    protected function setResourceId($id)
    {
        return $this->withMeta(['id' => $id, 'nova_path' => Config::get('nova.path')]);
    }
}
