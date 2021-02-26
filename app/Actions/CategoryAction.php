<?php

namespace App\Actions;

use App\Adapters\ShopCategoryAdapter;
use App\Adapters\TmpCategoryAdapter;
use App\Helpers\SimaParser;
use Medoo\Medoo;

class CategoryAction
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

    public function createRootCategory()
    {
        $categoryName = settings()['root_category_name'];
        $category = ShopCategoryAdapter::factory()->setCategory($categoryName)->run();
        $this->db->insert('category_shop', $category);
        $this->db->update('sima_parser', [
            'progress' => $this->db->id(),
            'stopped' => 1,
        ], ['id' => $this->id]);
    }

    public function createCategories()
    {
        $page = $this->progress;
        $categories = $this->provider->getCategories($page);
        if($categories->status == 401) die;
        if($categories->status != 404) {
            $tmpCategories = TmpCategoryAdapter::factory()
                ->setCategories($categories)
                ->run();
            $this->db->insert('category_tmp', $tmpCategories);
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

    public function importCategories()
    {
        $level = $this->progress;
        $categories = $this->db->select('category_tmp', '*', ['level' => $level]);
        if ($level == 1) {
            $parent = $this->db->get('sima_parser', ['progress'], [
                'action' => 'creation_root_category'
            ])['progress'];
        }
        foreach ($categories as $category) {
            if ($level != 1) {
                $path = explode('.', $category['path']);
                $alienParent = $path[$level-2];
                $parent = (int)$this->db->get('category_tmp',
                    ['local_id'],
                    ['alien_id' => $alienParent]
                )['local_id'];
            }
            $categoryObject = ShopCategoryAdapter::factory()
                ->setCategory($category['name'])
                ->setParent($parent)
                ->run();
            $this->db->insert('category_shop', $categoryObject);
            $this->db->update('category_tmp',
                ['local_id' => $this->db->id()],
                ['alien_id' => $category['alien_id']]
            );
        }
        $this->db->update('sima_parser', ['progress' => ++$level], ['id' => $this->id]);
    }
}