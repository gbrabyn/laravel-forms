<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\App\Model\ProgrammingExperienceFormOptions as FormOptions;
use App\Model\ReCaptchaV3;
use App\Repository\PersonExperience;

class ProgrammerExperienceTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    const COUNTRY_ID = 12;
    const DEFAULT_LOCALE = 'en_US';
    const SESSION_TOKEN = '9UvmUmLMiOLqIxwzeZZvhTilTiwjrzp1JO8h4GYp';

    private $programmingLanguages = [
        'Assembly' => 'Assembly',
        'Bash/Shell/PowerShell' => 'Bash/Shell/PowerShell',
        'C' => 'C',
        'C#' => 'C#',
        'C++' => 'C++',
        'Go' => 'Go',
        'HTML/CSS' => 'HTML/CSS',
    ];

    private $workTypeOptions = [
        'fullTime' => 'full time',
        'partTime' => 'part time',
        'temporary' => 'contract / temporary',
    ];

    private $recaptchaResponse = 'xyz';

    /** @var PersonExperience */
    private $personExperience;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockFormOptions();
        $this->mockReCaptcha();
    }

    private function mockFormOptions(): void
    {
        FormOptions::shouldReceive('getCountries')
            ->with(self::DEFAULT_LOCALE)
            ->andReturn([8 => 'Egypt', self::COUNTRY_ID => 'Brazil', 16 => 'Canada']);

        FormOptions::shouldReceive('getCountries')
            ->with('de_DE')
            ->andReturn([8 => 'Ägypten', self::COUNTRY_ID => 'Brasilien', 16 => 'Kanada']);

        FormOptions::shouldReceive('getProgrammingLanguages')
            ->andReturn($this->programmingLanguages);

        FormOptions::shouldReceive('getWorkTypeOptions')
            ->andReturn($this->workTypeOptions);
    }

    private function mockReCaptcha(): void
    {
        $this->mock(ReCaptchaV3::class, function ($mock) {
            $mock
                ->shouldReceive('setValue')
                ->with($this->recaptchaResponse);

            $mock
                ->shouldReceive('isValid')
                ->andReturn(true);
        });
    }

    private function makeNewPerson(string $locale): PersonExperience
    {
        $faker = $this->makeFaker($locale);

        $person = PersonExperience::factory()
            ->make([
                'sessionToken' => null,
                'lastEdit' => null,
                'countryId' => self::COUNTRY_ID,
                'languages' => $faker->randomElements(
                    array_keys($this->programmingLanguages),
                    $faker->numberBetween(1, count($this->programmingLanguages))
                ),
                'additionalLanguages' => null,
                'experience' => null,
            ]);

        return $person;
    }

    private function createPersonExperience(string $locale): PersonExperience
    {
        $faker = $this->makeFaker($locale);

        $person = PersonExperience::factory()
            ->create([
                'sessionToken' => self::SESSION_TOKEN,
                'countryId' => self::COUNTRY_ID,
                'languages' => $faker->randomElements(
                    array_keys($this->programmingLanguages),
                    $faker->numberBetween(1, count($this->programmingLanguages))
                ),
                'additionalLanguages' => [],
                'experience' => [],
            ]);

        return $person;
    }

    public function testProgrammerListShouldLoad()
    {
        $response = $this->get(route('programmer.list'));

        // $response->dumpHeaders();
        // $response->dumpSession();
        // $response->dump();

        $response->assertStatus(200);
        $response->assertViewIs('programmer.list');
    }

    public function testCreateShouldLoadEnglishVersion()
    {
        $response = $this->get(route('programmer.create', ['locale' => self::DEFAULT_LOCALE]));

        $response->assertStatus(200);
        $response->assertViewIs('programmer.edit');
        $response->assertSee('Brazil');
        $response->assertSee('C#');
    }

    public function testCreateShouldLoadGermanVersion()
    {
        $response = $this->get(route('programmer.create', ['locale' => 'de_DE']));

        $response->assertStatus(200);
        $response->assertViewIs('programmer.edit');
        $response->assertSee('Ägypten');
        $response->assertSee('HTML/CSS');
    }

    /**
     * @dataProvider invalidData
     */
    public function testStoreFailsValidationWithEmptyForm($data, $expected)
    {
        $response = $this->post(route('programmer.store', ['locale' => self::DEFAULT_LOCALE]), $data);

        $response->assertSessionHasErrors($expected);
    }

    public function invalidData(): array
    {
        return [
            [[], 'fullName'],
        ];
    }

    public function testStoreSavesValidData()
    {
        $person = $this->makeNewPerson(self::DEFAULT_LOCALE);

        $this->assertDatabaseMissing('PersonExperience', ['fullName' => $person->fullName]);

        $data = $person->toArray();
        $data['g-recaptcha-response'] = $this->recaptchaResponse;

        $response = $this
            ->withSession(['sessionToken' => self::SESSION_TOKEN])
            ->post(route('programmer.store', ['locale' => self::DEFAULT_LOCALE]), $data);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('programmer.list'));
        $this->assertDatabaseHas('PersonExperience', ['fullName' => $person->fullName]);
    }

    public function testStoreSavesValidDataWithExperience()
    {
        $faker = $this->makeFaker(self::DEFAULT_LOCALE);
        $person = $this->makeNewPerson(self::DEFAULT_LOCALE);
        $person->experience = [
            [
                "companyName" => $faker->company,
                "officeLocation" => $faker->address,
                "officeCountryId" => self::COUNTRY_ID,
                "startDate" => "2020-09-02",
                "finishDate" => "2020-09-11",
                "type" => "partTime",
                "additionalLanguagesUsed" => [
                    'Scala',
                ],
            ],
        ];

        $this->assertDatabaseMissing('PersonExperience', ['fullName' => $person->fullName]);

        $data = $person->toArray();
        $data['g-recaptcha-response'] = $this->recaptchaResponse;

        $response = $this
            ->withSession(['sessionToken' => self::SESSION_TOKEN])
            ->post(route('programmer.store', ['locale' => self::DEFAULT_LOCALE]), $data);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('programmer.list'));
        $this->assertDatabaseHas('PersonExperience', ['fullName' => $person->fullName]);
    }

    public function testEditShouldLoadEnglishVersion()
    {
        $person = $this->createPersonExperience(self::DEFAULT_LOCALE);

        $this->assertDatabaseHas('PersonExperience', ['fullName' => $person->fullName]);

        $response = $this
            ->withSession(['sessionToken' => self::SESSION_TOKEN])
            ->get(
                route('programmer.edit', ['locale' => self::DEFAULT_LOCALE, 'id' => $person->id])
            );

        $response->assertStatus(200);
        $response->assertViewIs('programmer.edit');
        $response->assertSee('Egypt');
        $response->assertSee('HTML/CSS');
    }

    public function testEditShouldLoadGermanVersion()
    {
        $person = $this->createPersonExperience('de_DE');

        $response = $this
            ->withSession(['sessionToken' => self::SESSION_TOKEN])
            ->get(
                route('programmer.edit', ['locale' => 'de_DE', 'id' => $person->id])
            );

        $response->assertStatus(200);
        $response->assertViewIs('programmer.edit');
        $response->assertSee('Ägypten');
    }

    public function testUpdateFailsWithInvalidData()
    {
        $person = $this->createPersonExperience(self::DEFAULT_LOCALE);

        $this->assertDatabaseHas('PersonExperience', ['fullName' => $person->fullName]);

        $person->email = 'ZZZZZZZZ';
        $data = $person->toArray();
        $data['g-recaptcha-response'] = $this->recaptchaResponse;

        $response = $this
            ->withSession(['sessionToken' => self::SESSION_TOKEN])
            ->put(
                route('programmer.update', ['locale' => self::DEFAULT_LOCALE, 'id' => $person->id]),
                $data
            );

        $response->assertSessionHasErrors('email');
    }

    public function testUpdateSavesValidData()
    {
        $person = $this->createPersonExperience(self::DEFAULT_LOCALE);
        $newName = $person->fullName . 'Z';
        $newEmail = 'Z' . $person->email;

        $this->assertDatabaseHas('PersonExperience', [
            'fullName' => $person->fullName,
            'email' => $person->email
        ]);

        $this->assertDatabaseMissing('PersonExperience', [
            'fullName' => $newName,
            'email' => $newEmail
        ]);

        $person->fullName = $newName;
        $person->email = $newEmail;
        $data = $person->toArray();
        $data['g-recaptcha-response'] = $this->recaptchaResponse;

        $response = $this
            ->withSession(['sessionToken' => self::SESSION_TOKEN])
            ->put(
                route('programmer.update', ['locale' => self::DEFAULT_LOCALE, 'id' => $person->id]),
                $data
            );

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('programmer.list'));
        $this->assertDatabaseHas('PersonExperience', [
            'id' => $person->id,
            'fullName' => $newName,
            'email' => $newEmail,
        ]);
    }
}
