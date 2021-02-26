<?php

require_once './vendor/autoload.php';

use App\Actions\CategoryAction;
use App\Adapters\ShopCategoryAdapter;
use App\Adapters\TmpCategoryAdapter;
use App\Helpers\SimaParser;
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
}
die;
if ($vector['action'] == 'creation_categories') {

}

if ($vector['action'] == 'import_categories') {
    $level = $vector['progress'];
    $categories = $db->select('category_tmp', '*', ['level' => $level]);
    foreach ($categories as $category) {
        $categoryObject = ShopCategoryAdapter::factory()
            ->setCategory($category['name'])
            ->setParent(1);
        var_dump($categoryObject);
        die;
    }

    //var_dump($categories);
}

echo "Done...";