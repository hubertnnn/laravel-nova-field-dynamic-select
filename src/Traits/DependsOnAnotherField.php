<?php

namespace Hubertnnn\LaravelNova\Fields\DynamicSelect\Traits;

use Closure;

trait DependsOnAnotherField
{
    protected $dependsOn = null;

    public function dependsOn($field)
    {
        $this->dependsOn = $field;

        return $this;
    }

    protected function getDependsOn()
    {
        return value($this->dependsOn);
    }
}
