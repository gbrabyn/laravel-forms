<?php
namespace App\Model;

use App\Repository\{CountriesRepository, ProgrammingLanguagesRepository};

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

    public function __construct(
        CountriesRepository $countriesRepository,
        ProgrammingLanguagesRepository $languagesRepository
    ){
        $this->countriesRepository = $countriesRepository;
        $this->languagesRepository = $languagesRepository;
    }
    
    public function getCountries(string $locale) : array
    {
        $ret = [];
        foreach($this->countriesRepository->getAll($locale) as $c){
            $ret[ $c['id'] ] = $c['name'];
        }
        
        return $ret;
    }
    
    public function getProgrammingLanguages() : array
    {
        return \array_combine($this->languagesRepository->getAll(), $this->languagesRepository->getAll());
    }
    
    public function getWorkTypeOptions() : array
    {
        return [
            'fullTime' => trans('messages.full time'),
            'partTime' => trans('messages.part time'),
            'temporary' => trans('messages.contract / temporary'),         
        ];
    }
}
