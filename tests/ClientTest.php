<?php

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PocketBase\Client;
use PocketBase\Collection;
use PocketBase\HttpClient;

final class ClientTest extends TestCase
{

    private Collection $collection;
    private ?Client $client = null;
    public  ?HttpClient $http = null;

    public function test_collection(): void
    {
        $this->client = new Client();
        $this->collection = $this->client->collection('_superusers');
        $this->assertEquals(Collection::class, get_class($this->collection));
    }

    public function test_authAsUser(): void
    {
        $this->client = new Client();
        $this->collection = $this->client->collection('users');
        $this->collection->setPath('/api/collections/users/auth-with-password');

        $token = $this->collection->authAsUser('admin@jonathan-martz.de', 'Password123');
        $this->assertNotEmpty($token);
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    // @sk
    public function test_authAsAdmin(): void
    {
        $this->client = new Client();
        $this->collection = $this->client->collection('_superusers');
        $this->collection->setPath('/api/collections/' . $this->collection->getName() . '/auth-with-password');

        $token = $this->collection->authAsUser('admin@jonathan-martz.de', 'Password123');
        $this->assertNotEmpty($token);
    }
}