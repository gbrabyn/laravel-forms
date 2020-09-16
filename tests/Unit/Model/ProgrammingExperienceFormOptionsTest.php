<?php

namespace Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use App\Repository\{CountriesRepository, ProgrammingLanguagesRepository};
use Illuminate\Contracts\Translation\Translator;
use App\Model\ProgrammingExperienceFormOptions;

/** @covers \App\Model\ProgrammingExperienceFormOptions */
class ProgrammingExperienceFormOptionsTest extends TestCase
{
    private $countries,
        $languagesRepository,
        $translator;

    public function setUp(): void
    {
        parent::setUp();

        $this->makeCountries();
        $this->makeLanguagesRepository();
        $this->makeTranslator();
    }

    private function makeCountries(): void
    {
        $countries = $this->createStub(CountriesRepository::class);
        $testMap = [
            ['en_US', [
                array(
                    'id'        => 340,
                    'name'      => 'Honduras',
                ),
                array(
                    'id'        => 348,
                    'name'      => 'Hungary',
                ),
            ]],
            ['de_DE', [
                array(
                    'id'        => 804,
                    'name'      => 'Ukraine',
                ),
                array(
                    'id'        => 348,
                    'name'      => 'Ungarn',
                ),
            ]]
        ];
        $countries
            ->method('getAll')
            ->will($this->returnValueMap($testMap));

        $this->countries = $countries;
    }

    private function makeLanguagesRepository(): void
    {
        $languagesRepository = $this->createStub(ProgrammingLanguagesRepository::class);
        $languagesRepository->method('getAll')
            ->willReturn(['aaa', 'bbb', 'ccc']);

        $this->languagesRepository = $languagesRepository;
    }

    private function makeTranslator(): void
    {
        $translator = $this->createStub(Translator::class);
        $translator->method('get')
            ->will($this->returnArgument(0));

        $this->translator = $translator;
    }

    private function getFormOptions(): ProgrammingExperienceFormOptions
    {
        return new ProgrammingExperienceFormOptions(
            $this->countries,
            $this->languagesRepository,
            $this->translator
        );
    }

    public function testGetCountries()
    {
        $formOptions = $this->getFormOptions();

        $this->assertSame([340 => 'Honduras', 348 => 'Hungary'], $formOptions->getCountries('en_US'));
        $this->assertSame([804 => 'Ukraine', 348 => 'Ungarn'], $formOptions->getCountries('de_DE'));
    }

    public function testGetCountriesUsesRepositoryOnce()
    {
        $countriesMock = $this->createMock(CountriesRepository::class);
        $countriesMock
            ->expects($this->once())
            ->method('getAll')
            ->with($this->equalTo('de_DE'));

        $formOptions = new ProgrammingExperienceFormOptions(
            $countriesMock,
            $this->languagesRepository,
            $this->translator
        );
        $formOptions->getCountries('de_DE');
    }

    public function testGetProgrammingLanguages()
    {
        $formOptions = $this->getFormOptions();

        $expected = ['aaa' => 'aaa', 'bbb' => 'bbb', 'ccc' => 'ccc'];
        $this->assertSame($expected, $formOptions->getProgrammingLanguages());
    }

    public function testGetWorkTypeOptions()
    {
        $formOptions = $this->getFormOptions();
        $workTypeOptions = $formOptions->getWorkTypeOptions();

        $this->assertIsArray($workTypeOptions);

        $first = array_slice($workTypeOptions, 0, 1, true);
        $this->assertSame(['fullTime' => 'messages.full time'], $first);
    }
}
