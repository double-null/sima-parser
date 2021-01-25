<?php

ini_set('max_execution_time', 300);

require_once './vendor/autoload.php';

use App\Helpers\SimaParser;
use App\Adapters\CategoryAdapter;

$db = new Medoo\Medoo(database());

$parser = new SimaParser();

if (!$db->count('category_links', '*')) {
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

