<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class CollectionTest extends TestCase
{
    public function test(): void
    {
        $expected = [];

        $url = 'https://admin.pocketbase.dev';
        $collection = new \Pb\Collection($url, 'users');
        $actual = $collection->getList(1,10);

        $this->assertSame($expected, $actual);
    }
}
