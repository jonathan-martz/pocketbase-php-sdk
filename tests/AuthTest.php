<?php

use GuzzleHttp\Exception\ClientException;
use Pb\Collection;
use PHPUnit\Framework\TestCase;

final class AuthTest extends TestCase
{

    private Collection $collection;
    private ?string $url;

    protected function setUp(): void
    {
        $this->url = getenv('POCKETBASE_URL') ?: 'https://admin.pocketbase.dev';
        $this->collection = new Collection($this->url, 'users');
    }
    public function testAuthUser(): void
    {
        $this->expectException(ClientException::class);
        $this->collection->authAsUser('support@jonathan-martz.de', 'rockt');
    }

    public function testAuthSuperUser(): void
    {
        $this->collection = new Collection($this->url, '_superusers');
        $this->expectException(ClientException::class);
        $this->collection->authAsAdmin('admin@jonathan-martz.de', 'rockt');
    }

    public function testAuthSuperUser2(): void
    {
        $this->collection = new Collection($this->url, '_superusers');

        $this->expectException(ClientException::class);
        $this->collection->authAsAdmin('admin@jmartz.de', 'rockt123?!');
    }
}