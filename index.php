<?php

require_once './vendor/autoload.php';

use App\Actions\{CategoryAction, ParserAction};
use App\Adapters\ShopCategoryAdapter;
use Medoo\Medoo;

$db = new Medoo(database());

//$parser = new SimaParser();
//$parser->getToken();

$vector = $db->get('sima_parser', '*', ['stopped' => 0]);

var_dump($vector);

echo "<pre>";

switch ($vector['action']) {
    case 'import_categories':
        CategoryAction::factory($vector['id'], $vector['progress'])->importCategories();
        break;
    case 'import_products':
        ParserAction::factory($vector['id'], $vector['progress'])->importProducts();
        break;
    case 'import_attributes':
        ParserAction::factory($vector['id'], $vector['progress'])->importAttributes();
        break;
    case 'import_attribute_values':
        ParserAction::factory($vector['id'], $vector['progress'])->importAttributeValues();
        break;
}

echo "Done...";