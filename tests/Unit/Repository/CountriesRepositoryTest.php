<?php

namespace Tests\Unit\Repository;

use PHPUnit\Framework\TestCase;
use App\Repository\CountriesRepository;

/**
 * @covers \App\Repository\CountriesRepository
 */
class CountriesRepositoryTest extends TestCase
{
    public function testEnglishLocaleReturnsArray(): array
    {
        $r = new CountriesRepository();
        $countries = $r->getAll('en_US');

        $this->assertIsArray($countries);

        return $countries;
    }

    /**
     * @depends testEnglishLocaleReturnsArray
     */
    public function testEnglishLocaleReturnsEnglishCountryNames($countries)
    {
        $hungary = array_values(array_filter($countries, function ($data) {
            return $data['id'] == 348;
        }));

        $this->assertCount(1, $hungary);
        $this->assertEquals('Hungary', $hungary[0]['name']);
    }

    public function testGermanLocaleReturnsArray(): array
    {
        $r = new CountriesRepository();
        $countries = $r->getAll('de_DE');

        $this->assertIsArray($countries);

        return $countries;
    }

    /**
     * @depends testGermanLocaleReturnsArray
     */
    public function testGermanLocaleReturnsGermanCountryNames($countries)
    {
        $hungary = array_values(array_filter($countries, function ($data) {
            return $data['id'] == 348;
        }));

        $this->assertCount(1, $hungary);
        $this->assertEquals('Ungarn', $hungary[0]['name']);
    }

    public function testInvalidLocaleThrowsInvalidArgumentException()
    {
        $r = new CountriesRepository();

        $this->expectException(\InvalidArgumentException::class);
        $r->getAll('xyz');
    }
}
