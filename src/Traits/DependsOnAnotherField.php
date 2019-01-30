<?php

namespace Hubertnnn\LaravelNova\Fields\DynamicSelect\Traits;

trait DependsOnAnotherField
{
    protected $dependsOn = null;
    protected $dependentValues = [];

    public function dependsOn($field)
    {
        $this->dependsOn = $field;

        return $this;
    }

    protected function getDependsOn()
    {
        return value($this->dependsOn);
    }

    protected function extractDependentValues($model)
    {
        if($this->dependsOn === null) {
            $this->dependentValues = [];
        } else {
            $attribute = $this->getDependsOn();
            $value = $this->resolveAttribute($model, $attribute);

            $this->dependentValues = [$attribute => $value];
        }
    }
}
