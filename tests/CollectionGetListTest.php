<?php declare(strict_types=1);

use Pb\Collection;
use PHPUnit\Framework\TestCase;

final class CollectionGetListTest extends TestCase
{
    private string $url;
    private Collection $collection;

    protected function setUp(): void
    {
        $this->url = getenv('POCKETBASE_URL') ?: 'https://admin.pocketbase.dev';
        $this->collection = new Collection($this->url, 'users');
    }

    public function test_getList_gettingArrayWithOneItem(): void
    {
        $actual = $this->collection->getList(1, 10);

        $this->assertArrayHasKey('items', $actual, 'Key "items" does not exist in the response.');
        $this->assertArrayHasKey('page', $actual, 'Key "page" does not exist in the response.');
        $this->assertArrayHasKey('perPage', $actual, 'Key "perPage" does not exist in the response.');
        $this->assertArrayHasKey('totalItems', $actual, 'Key "totalItems" does not exist in the response.');
        $this->assertArrayHasKey('totalPages', $actual, 'Key "totalPages" does not exist in the response.');
        $this->assertCount(1, $actual['items'], 'Expected no items in the response.');
    }

    public function test_getListWithContainsMartz(): void
    {
        $actual = $this->collection->getList(1, 10, ['filter'=>'name~"%Martz%"']);

        $this->assertArrayHasKey('items', $actual, 'Key "items" does not exist in the response.');
        $this->assertArrayHasKey('page', $actual, 'Key "page" does not exist in the response.');
        $this->assertArrayHasKey('perPage', $actual, 'Key "perPage" does not exist in the response.');
        $this->assertArrayHasKey('totalItems', $actual, 'Key "totalItems" does not exist in the response.');
        $this->assertArrayHasKey('totalPages', $actual, 'Key "totalPages" does not exist in the response.');
        $this->assertCount(1, $actual['items'], 'Expected no items in the response.');
    }

    public function test_getListStartsWithMartz(): void
    {
        $actual = $this->collection->getList(1, 10, ['filter'=>'name~"Martz%"']);

        $this->assertArrayHasKey('items', $actual, 'Key "items" does not exist in the response.');
        $this->assertArrayHasKey('page', $actual, 'Key "page" does not exist in the response.');
        $this->assertArrayHasKey('perPage', $actual, 'Key "perPage" does not exist in the response.');
        $this->assertArrayHasKey('totalItems', $actual, 'Key "totalItems" does not exist in the response.');
        $this->assertArrayHasKey('totalPages', $actual, 'Key "totalPages" does not exist in the response.');
        $this->assertCount(0, $actual['items'], 'Expected no items in the response.');
    }

    public function test_getListStartWithJonathan(): void
    {
        $actual = $this->collection->getList(1, 10, ['filter'=>'name~"Jonathan%"']);

        $this->assertArrayHasKey('items', $actual, 'Key "items" does not exist in the response.');
        $this->assertArrayHasKey('page', $actual, 'Key "page" does not exist in the response.');
        $this->assertArrayHasKey('perPage', $actual, 'Key "perPage" does not exist in the response.');
        $this->assertArrayHasKey('totalItems', $actual, 'Key "totalItems" does not exist in the response.');
        $this->assertArrayHasKey('totalPages', $actual, 'Key "totalPages" does not exist in the response.');
        $this->assertCount(1, $actual['items'], 'Expected no items in the response.');
    }

    public function test_getListCheckingDifferentName(): void
    {
        $actual = $this->collection->getList(1, 10, ['filter'=>'name~"%Sibylle%"']);

        $this->assertArrayHasKey('items', $actual, 'Key "items" does not exist in the response.');
        $this->assertArrayHasKey('page', $actual, 'Key "page" does not exist in the response.');
        $this->assertArrayHasKey('perPage', $actual, 'Key "perPage" does not exist in the response.');
        $this->assertArrayHasKey('totalItems', $actual, 'Key "totalItems" does not exist in the response.');
        $this->assertArrayHasKey('totalPages', $actual, 'Key "totalPages" does not exist in the response.');
        $this->assertCount(0, $actual['items'], 'Expected no items in the response.');
    }
}