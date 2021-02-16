<?php

namespace App\Adapters;

class ShopCategoryAdapter
{
    private $categoryName;
    private $parent = 0;

    public static function factory()
    {
        return new self();
    }

    public function setCategory($categoryName)
    {
        $this->categoryName = $categoryName;
        return $this;
    }

    public function setParent($parentCategoryId)
    {
        $this->parent = $parentCategoryId;
        return $this;
    }

    public function run()
    {
        return [
            'name' => $this->categoryName,
            'slug' => 'slug',
            'parent_id' => $this->parent,
            'icon' => '',
            'meta_title' => $this->categoryName,
            'meta_description' => $this->categoryName,
            'type' => 0,
            'status' => 1,
        ];
    }
}