<?php

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
        $expected = [
            'avatar' => '',
            'collectionId' => '_pb_users_auth_',
            'collectionName' => 'users',
            'created' => '2025-01-21 21:22:47.002Z',
            'emailVisibility' => false,
            'id' => '6588yk36406qqv1',
            'name' => 'Jonathan Martz',
            'updated' => '2025-01-21 21:22:47.002Z',
            'verified' => true
        ];

        $this->assertEquals($expected, $actual);
        $this->assertCount(9, $actual);
    }

    public function test_getOneWrongId(): void
    {
        $id = '6588yk36406qqva';
        $actual = $this->collection->getOne($id);
        $expected = [
            'data' => [],
            'message' => "The requested resource wasn't found.",
            'status' => 404,
        ];

        $this->assertEquals($expected, $actual);
        $this->assertCount(3,$actual);
    }
}