<?php

ini_set('max_execution_time', 300);

require_once './vendor/autoload.php';

use App\Helpers\SimaParser;

$parser = new SimaParser();

$domain = 'https://www.sima-land.ru/catalog/';

$parser->setUrl($domain)->getCategories();

/*
$categoryUrl = 'https://www.sima-land.ru/sale/detskie-tyubingi-i-naduvnye-sanki/?catalog=filter&c_id=50841&per-page=20&sort=price&viewtype=list';
$parser->setUrl($categoryUrl)->getProductLinks();

$productUrl = 'https://www.sima-land.ru/2351964/oblozhka-dlya-medicinskoy-karty-zvezdy/';
$productUrl = 'https://www.sima-land.ru/1773277/igrushka-dlya-igry-v-pesochnice-8-vidov-miks/';
$productUrl = 'https://www.sima-land.ru/3698255/interaktivnaya-sobaka-lyubimyy-schenok-hodit-laet-poet-pesenku-vilyaet-hvostom-3/';

$products = $parser->setUrl($productUrl)->getProduct();
*/

