<?php

namespace App\Model;

use App\Repository\{CountriesRepository, ProgrammingLanguagesRepository};
use Illuminate\Contracts\Translation\Translator;

/**
 * Provides Form options for ProgrammingExperienceController edit form
 *
 * @author G Brabyn
 */
class ProgrammingExperienceFormOptions
{
    /** @var CountriesRepository */
    private $countriesRepository;

    /** @var ProgrammingLanguagesRepository */
    private $languagesRepository;

    /** @var Translator */
    private $translator;

    public function __construct(
        CountriesRepository $countriesRepository,
        ProgrammingLanguagesRepository $languagesRepository,
        Translator $translator
    ) {
        $this->countriesRepository = $countriesRepository;
        $this->languagesRepository = $languagesRepository;
        $this->translator = $translator;
    }

    public function getCountries(string $locale): array
    {
        $ret = [];
        foreach ($this->countriesRepository->getAll($locale) as $c) {
            $ret[$c['id']] = $c['name'];
        }

        return $ret;
    }

    public function getProgrammingLanguages(): array
    {
        return \array_combine($this->languagesRepository->getAll(), $this->languagesRepository->getAll());
    }

    public function getWorkTypeOptions(): array
    {
        return [
            'fullTime' => $this->translator->get('messages.full time'),
            'partTime' => $this->translator->get('messages.part time'),
            'temporary' => $this->translator->get('messages.contract / temporary'),
        ];
    }
}
