<?php

namespace App\Adapters;

class TmpCategoryAdapter
{
    private $categories;

    public static function factory()
    {
        return new self();
    }

    public function setCategories($categories) {
        $this->categories = $categories;
        return $this;
    }

    public function run()
    {
        $out = [];
        foreach ($this->categories as $category) {
            $out[] = [
                'local_id' => 0,
                'alien_id' => $category->id,
                'name' => $category->name,
                'level' => $category->level,
                'path' => $category->path,
            ];
        }
        return $out;
    }
}