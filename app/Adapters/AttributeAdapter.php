<?php


namespace App\Adapters;


class AttributeAdapter
{
    protected $attributes;

    public static function factory()
    {
        return new self();
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    public function run()
    {
        $output = [];
        foreach ($this->attributes as $attribute) {
            $output[] = [
                'local_id' => 0,
                'alien_id' => $attribute->id,
                'data_type_id' => $attribute->data_type_id,
                'unit_id' => $attribute->unit_id,
                'name' => $attribute->name,
                'description' => $attribute->description,
            ];
        }
        return $output;
    }
}
