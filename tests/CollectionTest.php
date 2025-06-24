<?php

use PHPUnit\Framework\TestCase;
use PocketBase\ViewModel\CustomRecordViewModel;
use PocketBase\ViewModel\RecordListViewModel;
use PocketBase\ViewModel\RecordViewModel as RecordViewModelAlias;
use PocketBase\Client as Client;
use PocketBase\Collection as Collection;
use PocketBase\HttpClient as HttpClient;

final class CollectionTest extends TestCase
{

    private Collection $collection;
    private ?Client $client = null;
    public ?HttpClient $http = null;

    public string $name = '';

    public function test_getFullList_empty()
    {
        $this->collection = new Collection(null, 'users');
        $this->collection
            ->setClient(new Client('http://localhost:7090'));
        $this->collection->setPath("/api/collections/" . $this->collection->getName() . "/records");
        $data = $this->collection
            ->getFullList(10, []);

        $this->assertEquals(RecordListViewModel::class, get_class($data));
        $this->assertCount(0, $data->getItems());
    }

    public function test_getFullList_ownUser()
    {
        $this->collection = new Collection(null, 'users');
        $token = $this->collection->authAsUser('admin@jonathan-martz.de', 'Password123');

        $this->client = new Client('http://localhost:7090');
        $this->client->setToken($token);
        $this->collection->setPath('/api/collections/' . $this->collection->getName() . '/records');
        $this->collection->setClient($this->client);

        $data = $this->collection->getFullList(10, []);
        $this->assertEquals(RecordListViewModel::class, get_class($data));
        $this->assertCount(1, $data->getItems());
    }

    public function test_getFullList_ownAdmin()
    {
        $this->collection = new Collection(null, 'users');
        $token = $this->collection->authAsAdmin('admin@jonathan-martz.de', 'Password123');

        $this->client = new Client('http://localhost:7090');
        $this->client->setToken($token);
        $this->collection->setPath('/api/collections/' . $this->collection->getName() . '/records');
        $this->collection->setClient($this->client);

        $data = $this->collection->getFullList(10, []);
        $this->assertEquals(RecordListViewModel::class, get_class($data));
        $this->assertCount(2, $data->getItems());
    }

    public function test_create_record(): void
    {
        $this->client = new \PocketBase\Client(null);
        $this->collection = $this->client->collection('users');
        $this->collection->setPath('/api/collections/users/records');

        $record = $this->collection->create([
            'email' => 'admin@jonathan-martz.de',
            'password' => 'Password123',
            'passwordConfirm' => 'Password123',
        ]);
        $this->assertEquals(CustomRecordViewModel::class, get_class($record));
    }

    public function test_update_record(): void
    {
        $this->client = new \PocketBase\Client(null);
        $this->collection = $this->client->collection('users');
        $token = $this->collection->authAsUser('admin@jonathan-martz.de', 'Password123');

        $this->client->setToken($token);
        $this->collection = $this->client->collection('test');
        $this->collection->setClient($this->client);
        $this->collection->setPath('/api/collections/test/records/');

        try {
            $record = $this->collection->update('ss05u9mnegvplds', [
                'name' => 'Test123456'
            ], $token);
        } catch (Exception $exception) {
            $this->fail('Exception: ' . $exception->getMessage());
        }
        $this->assertEquals(RecordViewModelAlias::class, get_class($record));
    }

    public function test_getone_record(): void
    {
        $this->client = new \PocketBase\Client(null);
        $this->collection = $this->client->collection('users');
        $token = $this->collection->authAsUser('admin@jonathan-martz.de', 'Password123');

        $this->client->setToken($token);
        $this->collection = $this->client->collection('test');
        $this->collection->setClient($this->client);
        $this->collection->setPath('/api/collections/test/records/');

        try {
            $record = $this->collection->getOne('ss05u9mnegvplds', $token);
        } catch (Exception $exception) {
            $this->fail('Exception: ' . $exception->getMessage());
        }
        $this->assertEquals(CustomRecordViewModel::class, get_class($record));
    }

    public function test_getlist_record(): void
    {
        $this->client = new \PocketBase\Client(null);
        $this->collection = $this->client->collection('users');
        $token = $this->collection->authAsUser('admin@jonathan-martz.de', 'Password123');

        $this->client->setToken($token);
        $this->collection = $this->client->collection('test');
        $this->collection->setClient($this->client);
        $this->collection->setPath('/api/collections/users/records/');

        try {
            $record = $this->collection->getList(1, 10);
        } catch (Exception $exception) {
            $this->fail('Exception: ' . $exception->getMessage());
        }
        $this->assertEquals(RecordListViewModel::class, get_class($record));
    }

    public function test_delete_record(): void
    {
        $this->client = new \PocketBase\Client(null);
        $this->collection = $this->client->collection('users');
        $token = $this->collection->authAsUser('admin@jonathan-martz.de', 'Password123');

        $this->client->setToken($token);
        $this->collection = $this->client->collection('test');
        $this->collection->setClient($this->client);
        $this->collection->setPath('/api/collections/test/records/');

        try {
            $this->collection->delete('65uahqp4wicc88l');
        } catch (Exception $exception) {
            $this->fail('Exception: ' . $exception->getMessage());
        }
        $this->assertTrue(true);
    }

    public function test_upload_record(): void
    {
        $this->markTestSkipped('todo');
        $this->client = new \PocketBase\Client(null);
        $this->collection = $this->client->collection('users');
        $token = $this->collection->authAsUser('admin@jonathan-martz.de', 'Password123');

        $this->client->setToken($token);
        $this->collection = $this->client->collection('test');
        $this->collection->setPath('/api/collections/users/records/');
        $this->collection->setClient($this->client);

        try {
            $record = $this->collection->getList(1, 10);
        } catch (Exception $exception) {
            $this->fail('Exception: ' . $exception->getMessage());
        }
        $this->assertEquals(RecordListViewModel::class, get_class($record));
    }
}