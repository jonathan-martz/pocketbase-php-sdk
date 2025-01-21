<?php

namespace Pb;

use PHPUnit\Framework\TestCase;

final class AuthTest extends TestCase
{

    private Collection $collection;
    private ?string $url;

    protected function setUp(): void
    {
        $this->url = getenv('POCKETBASE_URL') ?: 'https://admin.pocketbase.dev';
        $this->collection = new Collection($this->url, 'users');
    }
    public function testAuthUser(): void
    {
        $actual = $this->collection->authAsUser('support@jonathan-martz.de', 'rockt');
        $expected = '{"data":{},"message":"Failed to authenticate.","status":400}';

        $this->assertEquals($expected, trim($actual, PHP_EOL));
    }

    public function testAuthSuperUser(): void
    {
        $this->collection = new Collection($this->url, '_superusers');
        $actual = $this->collection->authAsAdmin('admin@jonathan-martz.de', 'rockt');
        $expected = '{"data":{},"message":"Failed to authenticate.","status":400}';

        $this->assertEquals($expected, trim($actual, PHP_EOL));
    }

    public function testAuthSuperUser2(): void
    {
        $this->collection = new Collection($this->url, '_superusers');

        $actual = $this->collection->authAsAdmin('admin@jonathan-martz.de', 'rockt');

        $data = json_decode($actual,true);
        $this->assertArrayHasKey('record',$data);
        $this->assertArrayHasKey('token',$data);
        $this->assertCount(8,$data['record']);
    }
}