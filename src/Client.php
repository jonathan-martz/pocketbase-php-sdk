<?php

namespace Pb;

class Client
{
    private string $url;
    private string $token = '';

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function collection(string $collection): Collection
    {
        return new Collection($this->url ,$collection, $this->token);
    }

    public function settings(): Settings
    {
        return new Settings($this->url, $this->token);
    }

    public function setAuthToken(string $token): void
    {
        $this->token = $token;
    }
}
