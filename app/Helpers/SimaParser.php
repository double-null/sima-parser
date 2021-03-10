<?php

namespace App\Helpers;

class SimaParser extends WebTraveler
{
    public $apiUrl = 'https://www.sima-land.ru/api/v5';

    public $token;

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

    public function getProducts($page = 1)
    {
        $headers = [
            "Accept: application/vnd.goa.error",
            "Authorization: Bearer {$this->token}",
            "Content-Type: application/json",
        ];
        $url = $this->apiUrl.'/item?p='.$page;
        $products = $this->setHeaders($headers)
            ->deactivePost()
            ->offResponceHeaders()
            ->setUrl($url)
            ->request();
        return json_decode($products);
    }

    public function getAttributes($page = 1)
    {
        $headers = [
            "Accept: application/vnd.goa.error",
            "Authorization: Bearer {$this->token}",
            "Content-Type: application/json",
        ];
        $url = $this->apiUrl.'/attribute?p='.$page;
        $products = $this->setHeaders($headers)
            ->deactivePost()
            ->offResponceHeaders()
            ->setUrl($url)
            ->request();
        return json_decode($products);
    }

    public function getAttributeValues($page = 1)
    {
        $headers = [
            "Accept: application/vnd.goa.error",
            "Authorization: Bearer {$this->token}",
            "Content-Type: application/json",
        ];
        $url = $this->apiUrl.'/option?p='.$page;
        $products = $this->setHeaders($headers)
            ->deactivePost()
            ->offResponceHeaders()
            ->setUrl($url)
            ->request();
        return json_decode($products);
    }


    public function getProductAttributes($page = 1)
    {
        $headers = [
            "Accept: application/vnd.goa.error",
            "Authorization: Bearer {$this->token}",
            "Content-Type: application/json",
        ];
        $url = $this->apiUrl.'/item-attribute?p='.$page;
        $products = $this->setHeaders($headers)
            ->deactivePost()
            ->offResponceHeaders()
            ->setUrl($url)
            ->request();
        return json_decode($products);
    }
}
