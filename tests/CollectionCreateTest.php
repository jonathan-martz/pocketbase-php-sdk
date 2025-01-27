<?php

use GuzzleHttp\Exception\ClientException;
use Pb\Collection;
use PHPUnit\Framework\TestCase;

class CollectionCreateTest extends TestCase
{
    private string $url;
    private Collection $collection;

    protected function setUp(): void
    {
        $this->url = getenv('POCKETBASE_URL') ?: 'https://admin.pocketbase.dev';
        $this->collection = new Collection($this->url, 'users');
    }

    public function testCreateCollectionItem(){
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(403);
        $this->collection->create(['name' => 'Hallo Welt']);
    }

    public function testCreateCollectionItemAuthed(){
        $this->collection->authAsUser('admin@jmartz.de', 'rockt123?!');#
        self::assertNotEmpty($this->collection->getAuthToken());
    }

    public function testCreateCollectionItemAuthedAdmin(){
        $this->collection->authAsAdmin('admin@jonathan-martz.de', 'rockt123?!');
        $response = $this->collection->create(['email' => 'test@jonathan-martz.de','password' => 'rockt123?!','passwordConfirm' => 'rockt123?!']);
        var_dump($response);
        self::assertNotEmpty($this->collection->getAuthToken());
    }
}