<?php

use Pb\Client;
use Pb\Collection;
use Pb\Settings;
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
{
    private Collection $collection;
    private ?string $url;

    protected function setUp(): void
    {
        $this->url = getenv('POCKETBASE_URL') ?: 'https://admin.pocketbase.dev';
        $this->collection = new Collection($this->url, 'users');
    }

    public function test_collection(): void
    {
        $pb = new Client($this->url);

        $actual = $pb->collection('users');

        $this->assertEquals($actual, $this->collection);
    }

    public function test_settings(): void
    {
        $pb = new Client($this->url);

        $actual = $pb->settings();

        $this->assertEquals($actual, new Settings($this->url));
    }

    public function test_setToken(){
        $token = 'test123';

        $pb = new Client($this->url);
        $pb->setAuthToken($token);

        $this->assertEquals($token, $pb->getAuthToken());
        $this->assertEquals($token, $pb->token);
        $pb->token = 'test456';
        $this->assertEquals('test456', $pb->getAuthToken());
    }
}