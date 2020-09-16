<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Model\ProgrammingExperienceFormOptions as FormOptions;
use App\Repository\PersonExperience;
use App\Http\Requests\ProgrammingExperienceSave;
use GBrabyn\LaravelFormHelpers\AttributeTemplate;

/**
 *
 * @author G Brabyn
 */
class ProgrammerExperienceController extends Controller
{

    public function index()
    {
        return view('programmer/list', [
            'people' => PersonExperience::where(['sessionToken' => session('sessionToken')])
                ->orderBy('fullName', 'asc')->get(),
        ]);
    }

    public function add(FormOptions $formOptions, AttributeTemplate $attrTemp)
    {
        return view('programmer/edit', [
            'countries' => $formOptions->getCountries(request('locale', 'en_US')),
            'languages' => $formOptions->getProgrammingLanguages(),
            'workTypes' => $formOptions->getWorkTypeOptions(),
            'attributeTemplate' => $attrTemp,
        ]);
    }

    public function store(ProgrammingExperienceSave $request)
    {
        $validatedData = $this->savePrepared($request->validated());
        $save = array_merge($validatedData, ['sessionToken' => session('sessionToken')]);
        PersonExperience::create($save)->save();

        return redirect()->route('programmer.list');
    }

    private function savePrepared(array $data): array
    {
        unset($data['g-recaptcha-response']);
        $data['lastEdit'] = date('Y-m-d H:i:s');
        $data['additionalLanguages'] = array_values($data['additionalLanguages']);
        $data['experience'] = array_values($data['experience']);

        foreach ($data['experience'] as &$experience) {
            $experience['additionalLanguagesUsed'] = array_values($experience['additionalLanguagesUsed']);
        }

        return $data;
    }

    public function edit(FormOptions $formOptions)
    {
        // Customize to your purposes
        $personExp = PersonExperience::where([
            'sessionToken' => session('sessionToken'),
            'id' => request('id')
        ])->firstOrFail();

        return view('programmer/edit', [
            'personExperience' => $personExp,
            'countries' => $formOptions->getCountries(request('locale', 'en_US')),
            'languages' => $formOptions->getProgrammingLanguages(),
            'workTypes' => $formOptions->getWorkTypeOptions(),
        ]);
    }

    public function update(ProgrammingExperienceSave $request)
    {
        $personExp = PersonExperience::where(['sessionToken' => session('sessionToken'), 'id' => request('id')])->firstOrFail(); // Customize to your needs
        $validatedData = $this->savePrepared($request->validated());
        $personExp->update($validatedData);

        return redirect()->route('programmer.list');
    }
}
