<?php

use GuzzleHttp\Exception\ClientException;
use Pb\Collection;
use PHPUnit\Framework\TestCase;

final class CollectionGetOneTest extends TestCase
{
    private string $url;
    private Collection $collection;

    protected function setUp(): void
    {
        $this->url = getenv('POCKETBASE_URL') ?: 'https://admin.pocketbase.dev';
        $this->collection = new Collection($this->url, 'users');
    }

    public function test_getOne(): void
    {
        $id = '6588yk36406qqv1';
        $actual = $this->collection->getOne($id);

        $this->assertEquals($id, $actual['id']);
        $this->assertCount(9, $actual);
    }

    public function test_getOneWrongId(): void
    {
        $id = '6588yk36406qqva';

        $this->expectException(ClientException::class);

        $this->collection->getOne($id);
    }
}