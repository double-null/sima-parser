<?php

namespace App\Helpers;

use Symfony\Component\DomCrawler\Crawler;

class SimaParser extends WebTraveler
{
    public $baseUrl = 'https://www.sima-land.ru';

    public $apiUrl = 'https://www.sima-land.ru/api/v5';

    private $urlSegments = '';

    public $token;

    public $pswd = 'XP4Fgaqk7PKT8m9';

    public function setSegments($segments)
    {
        $this->urlSegments = $segments;
        return $this;
    }

    public function getToken()
    {
        $headers = [
            "Accept: application/vnd.goa.error",
            "Content-Type: application/json",
        ];
        $data = [
            'email' => 'tester4585@yandex.ua',
            'password' => 'XP4Fgaqk7PKT8m9',
            'phone' => '+79381156932',
            'regulation' => true,
        ];
        $url = $this->apiUrl.'/signin';
        $response = $this->setHeaders($headers)
            ->activeJson()
            ->setData($data)
            ->setUrl($url)
            ->request();
        preg_match('~Bearer (.*)~', $response, $matches);
        $this->token = trim($matches[1]);
    }

    public function getCategories($page = 1)
    {
        $headers = [
            "Accept: application/vnd.goa.error",
            "Authorization: Bearer {$this->token}",
            "Content-Type: application/json",
        ];
        $url = $this->apiUrl.'/category?p='.$page;
        $categories = $this->setHeaders($headers)
            ->deactivePost()
            ->offResponceHeaders()
            ->setUrl($url)
            ->request();
        return json_decode($categories);
    }

    public function getHighLevelCategories()
    {
        $url = $this->baseUrl.'/catalog/';
        $crawler = new Crawler($this->setUrl($url)->request());
        return $crawler->filter('.category-wrapper')->each(
            function (Crawler $node) {
                $link = $node->filter('.novelty-item_title a')->attr('href');
                preg_match('~c_id=([0-9].*)~', $link, $output);
                $categoryId = (count($output)) ? $output[1] : 0;
                return [
                    'main_category' => [
                        [
                            'id' => $categoryId,
                            'name' => $node->filter('.novelty-item_title a')->text(),
                            'link' => $link,
                        ],
                    ],
                    'sub_categories' => $node->filter('.novelty-item_category a.link')->each(
                        function (Crawler $node) {
                            $link = $node->attr('href');
                            preg_match('~c_id=([0-9].*)~', $link, $output);
                            $categoryId = (count($output)) ? $output[1] : 0;
                            return [
                                'id' => $categoryId,
                                'name' => $node->text(),
                                'link' => $node->attr('href'),
                            ];
                        }
                    ),
                ];
            }
        );
    }

    public function getLowLevelCategories()
    {
        $url = $this->baseUrl.$this->urlSegments;
        $crawler = new Crawler($this->setUrl($url)->request());

        $categories = $crawler->filter('.category-menu__item')->each(
            function (Crawler $node) {
                if ($node->text()) {
                    return [
                        'name' => $node->text(),
                        'link' => $node->filter('a')->attr('href'),
                    ];
                }
            }
        );
        return array_diff($categories, array(null));
    }

    public function getProductLinks($categoryId)
    {
        $params = '?per-page=100&sort=price&viewtype=list&is_catalog=1';
        $segments = explode('?', $this->urlSegments);
        $page = $this->baseUrl.$segments[0].$params.'&'.$segments[1];
        var_dump($this->setUrl($page)->request());
        die;
        $crawler = new Crawler($this->setUrl($page)->request());
        $lastPage = $crawler->filter('.js-select-page-input')->attr('max');
        var_dump($lastPage);

        die;

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
