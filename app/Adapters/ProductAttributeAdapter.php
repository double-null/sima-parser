<?php

namespace App\Adapters;

class ProductAttributeAdapter
{
    public $data;

    public static function factory()
    {
        return new self();
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function run()
    {
        $output = [];
        foreach ($this->data as $item) {
            $output[] = [
                'attribute_id' => $item->attribute_id,
                'product_id' => $item->item_id,
                'value_id' => $item->option_value,
            ];
        }
        return $output;
    }
}