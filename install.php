<?php

require_once './vendor/autoload.php';

$db = new Medoo\Medoo(database());

$db->create('sima_parser', [
    'id' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
    'action' => ['VARCHAR(255)', 'NOT NULL'],
    'progress' => ['INT', 'NOT NULL'],
    'stopped' => ['TINYINT(1)', 'NOT NULL'],
]);

$db->create('sima_categories', [
    'id' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
    'local_id' => ['INT', 'NOT NULL'],
    'alien_id' => ['INT', 'NOT NULL'],
    'level' => ['TINYINT(1)', 'NOT NULL'],
    'name' => ['VARCHAR(255)', 'NOT NULL'],
    'path' => ['VARCHAR(255)', 'NOT NULL'],
]);

$db->create('sima_products', [
    'id' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
    'local_id' => ['INT', 'NOT NULL'],
    'alien_id' => ['INT', 'NOT NULL'],
    'category_id' => ['INT', 'NOT NULL'],
    'name' => ['VARCHAR(255)', 'NOT NULL'],
    'description' => ['VARCHAR(255)'],
    'price' => ['FLOAT', 'NOT NULL'],
]);

$db->create('sima_attributes', [
    'id' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
    'local_id' => ['INT', 'NOT NULL'],
    'alien_id' => ['INT', 'NOT NULL'],
    'data_type_id' => ['INT', 'NOT NULL'],
    'unit_id' => ['INT', 'NOT NULL'],
    'name' => ['VARCHAR(255)', 'NOT NULL'],
    'description' => ['VARCHAR(255)'],
]);

$db->create('sima_attribute_values', [
    'id' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
    'local_id' => ['INT', 'NOT NULL'],
    'alien_id' => ['INT', 'NOT NULL'],
    'name' => ['VARCHAR(255)', 'NOT NULL'],
]);

$db->create('sima_product_attributes', [
    'id' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
    'attribute_id' => ['INT', 'NOT NULL'],
    'product_id' => ['INT', 'NOT NULL'],
    'value_id' => ['INT', 'NOT NULL'],
]);

$db->insert('sima_parser', [
    'action' => 'import_categories',
    'progress' => 1,
    'stopped' => 0,
]);

$db->insert('sima_parser', [
    'action' => 'import_products',
    'progress' => 1,
    'stopped' => 0,
]);

$db->insert('sima_parser', [
    'action' => 'import_attributes',
    'progress' => 1,
    'stopped' => 0,
]);

$db->insert('sima_parser', [
    'action' => 'import_attribute_values',
    'progress' => 1,
    'stopped' => 0,
]);

$db->insert('sima_parser', [
    'action' => 'import_product_attributes',
    'progress' => 1,
    'stopped' => 0,
]);

echo "Done...";