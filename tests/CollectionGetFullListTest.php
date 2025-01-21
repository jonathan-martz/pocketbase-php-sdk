<?php

use Pb\Collection;
use PHPUnit\Framework\TestCase;

final class CollectionGetFullListTest extends TestCase
{
    private string $url;
    private Collection $collection;

    protected function setUp(): void
    {
        $this->url = getenv('POCKETBASE_URL') ?: 'https://admin.pocketbase.dev';
        $this->collection = new Collection($this->url, 'users');
    }

    public function test_getFullList_gettingArrayWithOneUser(): void
    {
        $actual = $this->collection->getFullList([],100);

        $this->assertArrayHasKey('items', $actual, 'Key "items" does not exist in the response.');
        $this->assertArrayHasKey('page', $actual, 'Key "page" does not exist in the response.');
        $this->assertArrayHasKey('perPage', $actual, 'Key "perPage" does not exist in the response.');
        $this->assertArrayHasKey('totalItems', $actual, 'Key "totalItems" does not exist in the response.');
        $this->assertArrayHasKey('totalPages', $actual, 'Key "totalPages" does not exist in the response.');
        $this->assertCount(1, $actual['items'], 'Expected no items in the response.');
    }
}