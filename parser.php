<?php

ini_set('max_execution_time', 300);

require_once './vendor/autoload.php';

use App\Helpers\SimaParser;
use App\Adapters\{CategoryAdapter,ShopCategoryAdapter};

$db = new Medoo\Medoo(database());

$parser = new SimaParser();

if (!$db->count('category_links', '*')) {
    $category = ShopCategoryAdapter::factory()->setCategory('SimaLand')->run();
    $db->insert('category_shop', $category);
    $rootCategoryId = $db->id();
    $db->insert('category_links', [
        'alien_id' => $rootCategoryId,
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
            'alien_id' => $mainCategoryId,
            'link' => $category['main_category'][0]['link'],
            'scanned' => 1,
        ]);
        foreach ($category['sub_categories'] as $subCategory) {
            $categoryObject = ShopCategoryAdapter::factory()
                ->setParent($rootCategoryId)
                ->setCategory($subCategory['name'])
                ->run();
            $db->insert('category_shop', $categoryObject);
            $categoryId = $db->id();
            $db->insert('category_links', [
                'alien_id' => $categoryId,
                'link' => $subCategory['link'],
                'scanned' => 0,
            ]);
        }
    }
} else {
    echo 1;
}

die;
/*
    // Парсинг списка категорий из каталога
    $domain = 'https://www.sima-land.ru/catalog/';
    $categories = $parser->setUrl($domain)->getCategories();

    foreach ($categories as $category) {
        $structuredData = CategoryAdapter::factory()
            ->setData($category['main_category'])
            ->run();
        $db->insert('category_links', $structuredData['links']);
        $db->insert('category_shop', $structuredData['data']);
        $category_id = $db->id();
        $structuredData = CategoryAdapter::factory()
            ->setData($category['sub_categories'])
            ->setParent($category_id)
            ->run();
        $db->insert('category_links', $structuredData['links']);
        $db->insert('category_shop', $structuredData['data']);
    }
} else {
    $category_links = $db->select('category_links', '*', [
        'scanned' => 0,
        'LIMIT' => 10,
    ]);
    var_dump($category_links);
    die;
}


/*
$categoryUrl = 'https://www.sima-land.ru/sale/detskie-tyubingi-i-naduvnye-sanki/?catalog=filter&c_id=50841&per-page=20&sort=price&viewtype=list';
$parser->setUrl($categoryUrl)->getProductLinks();

$productUrl = 'https://www.sima-land.ru/2351964/oblozhka-dlya-medicinskoy-karty-zvezdy/';
$productUrl = 'https://www.sima-land.ru/1773277/igrushka-dlya-igry-v-pesochnice-8-vidov-miks/';
$productUrl = 'https://www.sima-land.ru/3698255/interaktivnaya-sobaka-lyubimyy-schenok-hodit-laet-poet-pesenku-vilyaet-hvostom-3/';

$products = $parser->setUrl($productUrl)->getProduct();
*/

