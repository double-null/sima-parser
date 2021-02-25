<?php

ini_set('max_execution_time', 300);

require_once './vendor/autoload.php';

use App\Helpers\SimaParser;
use App\Adapters\ShopCategoryAdapter;

$db = new Medoo\Medoo(database());

$parser = new SimaParser();

if (!$db->count('category_links', '*')) {
    $category = ShopCategoryAdapter::factory()->setCategory('SimaLand')->run();
    $db->insert('category_shop', $category);
    $rootCategoryId = $db->id();
    $db->insert('category_links', [
        'donor_id' => 0,
        'recepient_id' => $rootCategoryId,
        'link' => '/catalog/',
        'scanned' => 1,
    ]);
    $categories = $parser->getHighLevelCategories();
    foreach ($categories as $category) {
        $categoryObject = ShopCategoryAdapter::factory()
            ->setParent($rootCategoryId)
            ->setCategory($category['main_category'][0]['name'])
            ->run();
        $db->insert('category_shop', $categoryObject);
        $mainCategoryId = $db->id();
        $db->insert('category_links', [
            'donor_id' => $category['main_category'][0]['id'],
            'recepient_id' => $mainCategoryId,
            'link' => $category['main_category'][0]['link'],
            'scanned' => 1,
        ]);
        foreach ($category['sub_categories'] as $subCategory) {
            $categoryObject = ShopCategoryAdapter::factory()
                ->setParent($mainCategoryId)
                ->setCategory($subCategory['name'])
                ->run();
            $db->insert('category_shop', $categoryObject);
            $db->insert('category_links', [
                'donor_id' => $subCategory['id'],
                'recepient_id' => $db->id(),
                'link' => $subCategory['link'],
                'scanned' => 0,
            ]);
        }
    }
} /*elseif ($db->count('category_links', '*', ['scanned' => 0])) {
    //Парсинг категорий нижних уровней
    $category = $db->get('category_links', '*', ['scanned' => 0]);
    var_dump($category);
    $db->update('category_links', ['scanned' => 1], ['id' => $category['id']]);
    $subCategories = SimaParser::factory()
        ->setSegments($category['link'])
        ->getLowLevelCategories();
    foreach ($subCategories as $subCategory) {
        $categoryObject = ShopCategoryAdapter::factory()
            ->setParent($category['alien_id'])
            ->setCategory($subCategory['name'])
            ->run();
        $db->insert('category_shop', $categoryObject);
        $db->insert('category_links', [
            'alien_id' => $db->id(),
            'link' => $subCategory['link'],
            'scanned' => 0,
        ]);
    }
} */else {
    $category = $db->get('category_links', '*', ['scanned' => 0]);
    $productLinks = $parser
        ->setSegments($category['link'])
        ->getProductLinks($category['id']);
    //echo 1;
    //$category = $db
}
