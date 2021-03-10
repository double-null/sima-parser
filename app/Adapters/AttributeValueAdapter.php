<?php

namespace App\Adapters;

class AttributeValueAdapter
{
    protected $values;

    public static function factory()
    {
        return new self();
    }

    public function setValues($values)
    {
        $this->values = $values;
        return $this;
    }

    public function run()
    {
        $output = [];
        foreach ($this->values as $attrValue) {
            $output[] = [
                'local_id' => 0,
                'alien_id' => $attrValue->id,
                'name' => $attrValue->name,
            ];
        }
        return $output;
    }
}
