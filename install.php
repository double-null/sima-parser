<?php

require_once './vendor/autoload.php';

$db = new Medoo\Medoo(database());

$db->create('sima_parser', [
    'id' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
    'action' => ['VARCHAR(255)', 'NOT NULL'],
    'progress' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
    'stopped' => ['TINYINT(1)', 'NOT NULL'],
]);

$db->create('category_tmp',[
    'id' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
    'alien_id' => ['INT', 'NOT NULL'],
    'level' => ['TINYINT(1)', 'NOT NULL'],
    'name' => ['VARCHAR(255)', 'NOT NULL'],
    'path' => ['VARCHAR(255)', 'NOT NULL'],
]);

echo "Done...";