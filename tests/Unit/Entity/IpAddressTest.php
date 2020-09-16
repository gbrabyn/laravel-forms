<?php

namespace Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\IpAddress;

/** @covers \App\Entity\IpAddress */
class IpAddressTest extends TestCase
{
    private $ip = '127.0.0.1';

    public function testGet()
    {
        $ip = new IpAddress($this->ip);

        $this->assertEquals($this->ip, $ip->get());
    }

    public function testThrowsInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $ip = new IpAddress('xyz');
    }
}
