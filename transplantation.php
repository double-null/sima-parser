<?php

require_once './vendor/autoload.php';

use App\Adapters\ShopCategoryAdapter;
use Medoo\Medoo;


$db = new Medoo(database());
$settings = settings();
/*
// Создание корневой категории

$categoryObject = ShopCategoryAdapter::factory()
    ->setCategory($settings['root_category_name'])
    ->run();
$db->insert('category_shop', $categoryObject);

$rootCategoryId = $db->id();

//Импорт категорий

$level = 1;

do {
    $categories = $db->select('sima_categories', '*', ['level' => $level]);
    foreach ($categories as $category) {
        if ($level == 1) {
            $parentCategoryId = $rootCategoryId;
        } else {
            $path = explode('.', $category['path']);
            $alienParentId = $path[count($path)-2];
            $parentCategoryId = $db->get('sima_categories', 'local_id',
                ['alien_id' => $alienParentId]
            );
        }
        $categoryObject = ShopCategoryAdapter::factory()
            ->setCategory($category['name'])
            ->setParent($parentCategoryId)
            ->run();
        $db->insert('category_shop', $categoryObject);
        $db->update('sima_categories', ['local_id' => $db->id()], ['id' => $category['id']]);
    }
    $level++;
} while(count($categories));

*/

// Импорт товаров

$products = $db->select('sima_products', [
        '[>]sima_categories' => ['category_id' => 'alien_id'],
    ],
    [
        'sima_products.name', 'sima_products.description', 'sima_products.price',
        'sima_categories.local_id(category_id)', 'sima_categories.name(ctg)'
    ]
);

echo "<pre>";
var_dump($products);