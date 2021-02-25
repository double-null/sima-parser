<?php

namespace App\Helpers;

class WebTraveler
{
    protected $url;

    protected $data;

    protected $postRequest = 0;

    protected $jsonRequest = 0;

    protected $responseHeader = true;

    protected $httpCode;

    protected $redirectUrl;

    protected $headers = null;

    public static function factory()
    {
        $class = static::class;
        return new $class;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function setHeaders($headers)
    {
        $this->headers = $headers;
        return $this;
    }

    public function activePost()
    {
        $this->postRequest = 1;
        return $this;
    }

    public function deactivePost()
    {
        $this->postRequest = 0;
        return $this;
    }

    public function onResponceHeaders()
    {
        $this->responseHeader = true;
        return $this;
    }

    public function offResponceHeaders()
    {
        $this->responseHeader = false;
        return $this;
    }

    public function activeJson()
    {
        $this->postRequest = 1;
        $this->jsonRequest = 1;
        return $this;
    }

    public function request()
    {
        $curl = curl_init($this->url);
        if ($this->postRequest == 1) {
            $data = ($this->jsonRequest == 1) ?
                json_encode ($this->data, JSON_UNESCAPED_UNICODE) :
                http_build_query($this->data);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,10);
        curl_setopt($curl,CURLOPT_HEADER, $this->responseHeader);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        if ($this->headers) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        }
        $response = curl_exec($curl);
        $this->httpCode = curl_getinfo($curl)['http_code'];
        $this->redirectUrl = curl_getinfo($curl)['redirect_url'];
        curl_close($curl);
        return $response;
    }
}
