<?php

require_once './vendor/autoload.php';

use App\Actions\{CategoryAction, ProductAction};
use App\Adapters\ShopCategoryAdapter;
use Medoo\Medoo;

$db = new Medoo(database());

//$parser = new SimaParser();
//$parser->getToken();

$vector = $db->get('sima_parser', '*', ['stopped' => 0]);

var_dump($vector);
echo "<pre>";

switch ($vector['action']) {
    case 'creation_root_category':
        CategoryAction::factory($vector['id'], $vector['progress'])
            ->createRootCategory();
        break;
    case 'creation_categories':
        CategoryAction::factory($vector['id'], $vector['progress'])
            ->createCategories();
        break;
    case 'import_categories':
        CategoryAction::factory($vector['id'], $vector['progress'])->importCategories();
        break;
    case 'import_products':
        ProductAction::factory($vector['id'], $vector['progress'])->importProducts();
}

echo "Done...";