<?php


namespace App\Adapters;


class ProductAdapter
{
    protected $products;

    public static function factory()
    {
        return new self();
    }

    public function setProducts($products)
    {
        $this->products = $products;
        return $this;
    }

    public function run()
    {
        $output = [];
        foreach ($this->products as $product) {
            $output[] = [
                'local_id' => 0,
                'alien_id' => $product->id,
                'category_id' => $product->category_id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
            ];
        }
        return $output;
    }
}
