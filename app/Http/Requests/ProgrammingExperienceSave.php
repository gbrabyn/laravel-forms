<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Facades\App\Model\ProgrammingExperienceFormOptions as FormOptions;
use App\Repository\PersonExperience;
use App\Model\ReCaptchaV3;

class ProgrammingExperienceSave extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        /**
         * The array fields could be not set if no checkbox is checked or JS is used 
         * to take all the array fields away. Here we make sure all array fields have a
         * default value of [] "empty array" if they are not submitted or have a 
         * non-array value
         */
        
        $additionalLanguages = is_array($this->post('additionalLanguages')) ? $this->post('additionalLanguages') : [];
        $experience = is_array($this->post('experience')) ? $this->post('experience') : [];
        
        $fixedExperience = [];
        foreach($experience as $k => $job){
            $additionalLanguagesUsed    = is_array($job['additionalLanguagesUsed'] ?? null)
                                        ? $job['additionalLanguagesUsed'] : [];

            $fixedJob = [
                'languagesUsed' => is_array($job['languagesUsed'] ?? null) 
                                ?  $job['languagesUsed'] : [],

                'additionalLanguagesUsed'
                    =>  array_filter($additionalLanguagesUsed, function($value){
                            return !empty($value);
                        }), // remove empty additionalLanguagesUsed[] fields
            ];
            $fixedExperience[$k] = array_replace($job, $fixedJob);
        } 

        $this->merge([
            'languages' => is_array($this->post('languages')) ? $this->post('languages') : [],
            'additionalLanguages' 
                =>  array_filter($additionalLanguages, function($value){    // remove empty additionalLanguages[] fields
                        return !empty($value);
                    }),
            'experience' => $fixedExperience,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'fullName' => ['bail', 'required', 'max:255', $this->uniqueFullName()],
            'email' => ['bail', 'required', 'max:255', 'email'],
            'address' => ['nullable', 'string'],
            'countryId' => ['bail', 'required', Rule::in(array_keys(FormOptions::getCountries('en_US'))),],
            'languages'=> [
                'array', 
                function($attribute, $value, $fail){    // Validate that either "Programming Languages" or "Additional Languages" has entries
                    if(empty($value) && empty($this->post('additionalLanguages'))){
                        $fail(trans('messages.languagesRequired'));
                    }
                },
            ],
            'languages.*'=> ['string', 'distinct', Rule::in(FormOptions::getProgrammingLanguages())],
            'additionalLanguages' => ['array'],
            'additionalLanguages.*' => ['string', 'distinct'],
            'experience' => ['array'],
            'experience.*.companyName' => ['required', 'string'],
            'experience.*.officeLocation' => ['nullable', 'string'],
            'experience.*.officeCountryId' => ['required', Rule::in(array_keys(FormOptions::getCountries('en_US')))],
            'experience.*.startDate' => ['required', 'date_format:Y-m-d'],
            'experience.*.finishDate' => ['required', 'date_format:Y-m-d', 'after_or_equal:experience.*.startDate'],
            'experience.*.type' => ['required', Rule::in(array_keys(FormOptions::getWorkTypeOptions()))],
            'g-recaptcha-response' => [
                function($attribute, $value, $fail){    // reCAPTCHA validation
                    $validator = new ReCaptchaV3(env('RECAPTCHA_SECRET'));
                    $validator->setValue($value);
                    
                    if($validator->isValid() === false){
                        $fail(trans('messages.inputReCaptchaFail'));
                    }
                },
            ],
        ];
        
        $experience = is_array($this->post('experience')) ? $this->post('experience') : [];
        foreach(array_keys($experience) as $k){
            $rules['experience.'.$k.'.languagesUsed'] = [
                'array',
                function($attribute, $value, $fail) use($k) {   // Validate that either "Languages Used" or "Additional Languages Used" has entries
                    if(empty($value) && empty($this->input('experience.'.$k.'.additionalLanguagesUsed'))){
                        $fail(trans('messages.languagesUsedRequired'));
                    }
                },
            ];
                
            $rules['experience.'.$k.'.languagesUsed.*'] = [
                'string', 
                'distinct', 
                Rule::in(array_keys(FormOptions::getProgrammingLanguages())),
            ];
            
            $rules['experience.'.$k.'.additionalLanguagesUsed'] = ['array'];
            $rules['experience.'.$k.'.additionalLanguagesUsed.*'] = ['string', 'distinct'];
        } 
        
        return $rules;
    }
    
    private function uniqueFullName() : Rules\Unique
    {
        $rule = Rule::unique(PersonExperience::class, 'fullName')
                    ->where('sessionId', session()->getId());

        if(request()->id){
            $rule->ignore(request()->id, 'id');
        }
        
        return $rule;
    }
    
    /**
     * Customizing some of the error messages
     * 
     * @return array
     */
    public function messages()
    {
        return [
            'countryId.required' => trans('messages.inputRequired'),
            'languages.required' => trans('messages.languagesRequired'),
            'additionalLanguages.*.distinct' => trans('messages.inputDuplicated'),
            'experience.*.companyName.required' => trans('messages.inputRequired'),
            'experience.*.officeCountryId.required' => trans('messages.inputRequired'),
            'experience.*.startDate.required' => trans('messages.inputRequired'),
            'experience.*.finishDate.required' => trans('messages.inputRequired'),
            'experience.*.finishDate.after_or_equal' => trans('messages.formAfterStartDate'),
            'experience.*.type.required' => trans('messages.inputRequired'),
            'experience.*.additionalLanguagesUsed.*.distinct' => trans('messages.inputDuplicated'),
        ];
    }
}
