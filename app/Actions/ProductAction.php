<?php

namespace App\Actions;

use App\Helpers\SimaParser;
use Medoo\Medoo;

class ProductAction
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
        $page = 1;
        $products = $this->provider->getProducts($page);
        var_dump($products);
    }
}