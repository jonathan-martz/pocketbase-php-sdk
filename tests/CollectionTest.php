<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class CollectionTest extends TestCase
{
    private $url;

    protected function setUp(): void
    {
        $this->url = getenv('POCKETBASE_URL') ?: 'https://admin.pocketbase.dev';
    }

    public function test_givenUrlAndCollectionNameUsers_thenCheckEmptyResult(): void
    {
        $collection = new \Pb\Collection($this->url, 'users');
        $actual = $collection->getList(1, 10);

        $this->assertTrue(array_key_exists('items', $actual), 'Key "items" does not exist in the response.');
        $this->assertTrue(array_key_exists('page', $actual), 'Key "page" does not exist in the response.');
        $this->assertTrue(array_key_exists('perPage', $actual), 'Key "perPage" does not exist in the response.');
        $this->assertTrue(array_key_exists('totalItems', $actual), 'Key "totalItems" does not exist in the response.');
        $this->assertTrue(array_key_exists('totalPages', $actual), 'Key "totalPages" does not exist in the response.');
        $this->assertCount(0, $actual['items'], 'Expected no items in the response.');
    }

    public function test_givenFilterByName_thenCheckEmptyResult(): void
    {
        $collection = new \Pb\Collection($this->url, 'users', [
            'filter' => 'name ~ "%Jonathan%"'
        ]);
        $actual = $collection->getList(1, 10);

        $this->assertTrue(array_key_exists('items', $actual), 'Key "items" does not exist in the response.');
        $this->assertTrue(array_key_exists('page', $actual), 'Key "page" does not exist in the response.');
        $this->assertTrue(array_key_exists('perPage', $actual), 'Key "perPage" does not exist in the response.');
        $this->assertTrue(array_key_exists('totalItems', $actual), 'Key "totalItems" does not exist in the response.');
        $this->assertTrue(array_key_exists('totalPages', $actual), 'Key "totalPages" does not exist in the response.');
        $this->assertCount(1, $actual['items'], 'Expected no items in the response.');
    }
}