<?php

namespace App\Adapters;

class CategoryAdapter
{
    protected $data;

    protected $parent = 0;

    public static function factory()
    {
        return new self();
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    public function run()
    {
        $out = [];
        $links = [];
        foreach ($this->data as $item) {
            $out[] = [
                'name' => $item['name'],
                'slug' => 'slug',
                'parent_id' => $this->parent,
                'icon' => '',
                'meta_title' => $item['name'],
                'meta_description' => $item['name'],
                'type' => 0,
                'status' => 1,
            ];
            $links[] = [
                'link' => $item['link'],
                'scanned' => 0,
            ];
        }
        return [
            'data' => $out,
            'links' => $links,
        ];
    }
}
