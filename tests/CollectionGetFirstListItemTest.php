<?php

use Pb\Collection;
use Pb\Exception\FirstListItemNotFoundException;
use PHPUnit\Framework\TestCase;

final class CollectionGetFirstListItemTest extends TestCase
{
    private Collection $collection;

    protected function setUp(): void
    {
        $url = getenv('POCKETBASE_URL') ?: 'https://admin.pocketbase.dev';
        $this->collection = new Collection($url, 'users');
    }

    /**
     * @throws FirstListItemNotFoundException
     */
    public function test_getOne(): void
    {
        $id = '6588yk36406qqv1';
        $actual = $this->collection->getFirstListItem('id="'.$id.'"');

        $this->assertEquals($id, $actual['id']);
        $this->assertCount(9, $actual);
    }

    public function test_getOneWrongId(): void
    {
        $id = '6588yk36406qqvb';
        $this->expectException(FirstListItemNotFoundException::class);
        $this->collection->getFirstListItem('id="'.$id.'"');
    }
}