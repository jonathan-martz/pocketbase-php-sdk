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
        // $this->expectException(ClientException::class);
        // $this->collection->create(['name' => 'Hallo Welt']);
    }

    public function testCreateCollectionItemAuthed(){
        $this->collection->authAsUser('admin@jmartz.de', 'rockt123?!');
        self::assertNotEmpty($this->collection::$token);
        // $response = $this->collection->create(['name' => 'Hallo Welt']);
        // var_dump($response);
    }
}