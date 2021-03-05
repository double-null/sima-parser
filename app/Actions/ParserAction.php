<?php

namespace App\Actions;

use App\Adapters\AttributeAdapter;
use App\Adapters\ProductAdapter;
use App\Helpers\SimaParser;
use Medoo\Medoo;

class ParserAction
{
    protected $id;

    protected $progress = 0;

    private $db;

    private $provider;

    public static function factory($id, $progress)
    {
        return new self($id, $progress);
    }

    public function __construct($id, $progress)
    {
        $this->id = $id;
        $this->progress = $progress;
        $this->db = new Medoo(database());
        $this->provider = new SimaParser();
        $this->provider->getToken();
    }

    public function importProducts()
    {
        $page = $this->progress;
        $products = $this->provider->getProducts($page);
        if($products->status == 401) die;
        if($products->status != 404) {
            $productObject = ProductAdapter::factory()
                ->setProducts($products)
                ->run();
            $this->db->insert('sima_products', $productObject);
            $this->db->update('sima_parser',
                ['progress' => ++$page],
                ['id' => $this->id]
            );
        } else {
            $this->db->update('sima_parser',
                ['stopped' => 1],
                ['id' => $this->id]
            );
        }
    }

    public function importAttributes()
    {
        $page = $this->progress;
        $attributes = $this->provider->getAttributes($page);
        if($attributes->status == 401) die;
        if($attributes->status != 404) {
            $attributeObject = AttributeAdapter::factory()
                ->setAttributes($attributes)
                ->run();
            $this->db->insert('sima_attributes', $attributeObject);
            $this->db->update('sima_parser',
                ['progress' => ++$page],
                ['id' => $this->id]
            );
        } else {
            $this->db->update('sima_parser',
                ['stopped' => 1],
                ['id' => $this->id]
            );
        }
    }

    public function importAttributeValues()
    {
        $page = $this->progress;
        $options = $this->provider->getAttributeValues($page);
        var_dump($options);
        die;
        if($attributes->status == 401) die;
        if($attributes->status != 404) {
            $attributeObject = AttributeAdapter::factory()
                ->setAttributes($attributes)
                ->run();
            $this->db->insert('sima_attributes', $attributeObject);
            $this->db->update('sima_parser',
                ['progress' => ++$page],
                ['id' => $this->id]
            );
        } else {
            $this->db->update('sima_parser',
                ['stopped' => 1],
                ['id' => $this->id]
            );
        }
    }

    /**
     * Получение связей Атрибут-значение
     */
    public function importProductAttributes()
    {

    }
}
