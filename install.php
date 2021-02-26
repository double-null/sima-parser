<?php

require_once './vendor/autoload.php';

$db = new Medoo\Medoo(database());

$db->create('sima_parser', [
    'id' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
    'action' => ['VARCHAR(255)', 'NOT NULL'],
    'progress' => ['INT', 'NOT NULL'],
    'stopped' => ['TINYINT(1)', 'NOT NULL'],
]);

$db->create('category_tmp', [
    'id' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
    'local_id' => ['INT', 'NOT NULL'],
    'alien_id' => ['INT', 'NOT NULL'],
    'level' => ['TINYINT(1)', 'NOT NULL'],
    'name' => ['VARCHAR(255)', 'NOT NULL'],
    'path' => ['VARCHAR(255)', 'NOT NULL'],
]);

$db->insert('sima_parser', [
    'action' => 'creation_root_category',
    'progress' => 0,
    'stopped' => 0,
]);

$db->insert('sima_parser', [
    'action' => 'creation_categories',
    'progress' => 1,
    'stopped' => 0,
]);

$db->insert('sima_parser', [
    'action' => 'import_categories',
    'progress' => 1,
    'stopped' => 0,
]);

echo "Done...";