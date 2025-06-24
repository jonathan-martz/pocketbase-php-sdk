<?php

namespace PocketBase;

class Client
{

    public ?string $baseurl = null;
    public ?string $token = null;
    public HttpClient $http;

    public function __construct($baseurl = null)
    {
        $this->baseurl = $baseurl ?? 'http://localhost:7090';
        $this->http = new HttpClient();
        $this->token = '';
    }

    public function collection(string $name): Collection
    {
        return new Collection($this->baseurl, $name);
    }

    public function setBaseUrl($baseurl){
        $this->baseurl = $baseurl;
    }

    public function getBaseUrl(){
        return $this->baseurl;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): void
    {
       $this->token = $token;
    }

    public function getHttp(): HttpClient
    {
        return $this->http;
    }

    public function setHttp(HttpClient $http): Client
    {
        $this->http = $http;
        return $this;
    }
}