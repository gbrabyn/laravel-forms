<?php

namespace Tests\Unit\Repository;

use PHPUnit\Framework\TestCase;
use App\Repository\ProgrammingLanguagesRepository;

/**
 * @covers \App\Repository\ProgrammingLanguagesRepository
 */
class ProgrammingLanguagesRepositoryTest extends TestCase
{
    public function testReturnsArray()
    {
        $p = new ProgrammingLanguagesRepository();

        $this->assertIsArray($p->getAll());
    }
}
