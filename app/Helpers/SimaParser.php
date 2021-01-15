<?php

namespace App\Helpers;

use Symfony\Component\DomCrawler\Crawler;

class SimaParser extends WebTraveler
{
    public $baseUrl = 'https://www.sima-land.ru';

    public function getCategories()
    {
        $crawler = new Crawler($this->request());
        $categories = $crawler->filter('.novelty-item_title a')->each(
            function (Crawler $node) {
                $url = $this->baseUrl.$node->attr('href');
                $crawler = new Crawler($this->setUrl($url)->request());
                $subCategories = $crawler->filter('.category-list-li-inner a')->each(
                    function (Crawler $node) {
                        return [
                            'name' => $node->html(),
                            'link' => $node->attr('href'),
                        ];
                    }
                );
                return [
                    'name' => $node->html(),
                    'link' => $node->attr('href'),
                    'sub_categories' => $subCategories,
                ];
            }
        );
        var_dump($categories);
    }

    public function getProductLinks()
    {
        $crawler = new Crawler($this->request());
        return $crawler->filter('.catalog__item-link')->each(
            function (Crawler $node) {
                return $node->attr('href');
            }
        );
    }

    public function getProduct()
    {
        $crawler = new Crawler($this->request());
        $name = $crawler->filter('h1._2o31e')->text();
        $description = $crawler->filter('._3jMXK')->html();
        $parameters = $crawler->filter('._2qwLf ._6jrmp')->each(
            function (Crawler $node) {
                return [
                    'type' => $node->filter('h3')->text(),
                    'sub_params' => $node->filter('.gngdv')->each(
                        function (Crawler $node) {
                            return [
                                'name' => $node->filter('.property-name')->text(),
                                'value' => $node->filter('.S34I2')->text(),
                            ];
                        }
                    ),
                ];
            }
        );
        $photos = $crawler->filter('.bQJuB ._2dV0t')->each(
            function (Crawler $node) {
                return $node->filter('img')->attr('src');
            }
        );
        return [
            'name' => $name,
            'description' => $description,
            'parameters' => $parameters,
            'photos' => $photos,
        ];
    }
}
