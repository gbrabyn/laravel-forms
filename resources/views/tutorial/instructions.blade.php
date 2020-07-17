@php
    $gitHubRepo = 'https://github.com/gbrabyn/laravel-forms';
@endphp

@extends('layouts.layoutTutorials')

@section('headTitle')
    Laravel Collective - Dynamic Forms Tutorial
@endsection
@push('css')
    <link rel="stylesheet" href="{{ mix('/build/tutorial.css') }}">
@endpush

@section('content')
<header class="main">
    <h1>How to Build Dynamic Forms in Laravel</h1>
</header>
<p>
    The source code for this website can be found at <a href="{{ $gitHubRepo }}">GitHub</a>.
</p>
<p>
    Below are instruction on how to set up in Laravel <a href="/en_US/programmer/create">this dynamic form here</a>.
    The form contains several dynamic sections (fields added by JavaScript). It also has dynamic elements within
    other dynamic blocks. This makes building and validating the form more complex. Some quite complex validation
    rules are applied.
</p>
<p>
    To follow the instructions below you also need to use the 
    <a href="{{ $gitHubRepo }}" target="_blank">GitHub repository</a>
    as they frequently require you to copy files or blocks of code from it.
</p>
<ul>
    <li>
        Have a working instance of Laravel 7
    </li>
    <li>
        Install Laravel Collective and create some form components
        <ul>
            <li>Composer install ("laravelcollective/html": "*")</li>
            <li>config/app.php - add Collective\Html\HtmlServiceProvider::class to 'providers' array</li>
            <li>Create app/Model directory. Add FormFieldHelper.php</li>
            <li>Create directory "resources/views/components/form" and add files:
                <ul>
                    <li>myCheckboxList.blade.php</li>
                    <li>myInput.blade.php</li>
                    <li>myRadioList.blade.php</li>
                    <li>mySelect.blade.php</li>
                    <li>myTextarea.blade.php</li>                    
                </ul>
                Each of these files adds an error message alongside the form field and adds 
                an "error" class (so it can be highlighted) to the form field if it has an error. myCheckboxList.blade.php
                and myRadioList.blade.php create a &lt;ul> list of related radio or checkboxes.
                <br>
                Using these components results in much cleaner view templates and reduces code repetition.
                <br>
                These files should be customized for your projects particular needs.
            </li>
            <li>app/Providers - add FormServiceProvider.php</li>
            <li>config/app.php - add App\Providers\FormServiceProvider::class to 'providers' array</li>
        </ul>
    </li>
    <li>
        Start building the form HTML
        <ul>
            <li>
                Create the Controller class (i.e. ProgrammerExperienceController) with the following 4
                actions.
<pre>
public function add()
{

}

public function store()
{

}

public function edit()
{

}

public function update()
{

}
</pre>
            </li>
            <li>
                In routes/web.php create routes to each of the above actions. add() and edit() should be reached 
                by GET requests method, store() by POST request and update() by Laravels PUT method.
            </li>
            <li>
                Create the view file "edit.blade.php" which will be shared by the add and edit pages.
            </li>
            <li>
                Start the form on edit.blade.php. Use Laravel Collectives Form object to create the 
                form opening and closing tags. The form should use method "post" in this case and should 
                post to the store() action.
<pre>                
&lbrace;!! Form::open(['route'=>['programmer.store'], 'method'=>'post', 'id'=>"experience"]) !!&rbrace;

&lbrace;!! Form::close() !!&rbrace;
</pre> 
            </li>
            <li>
                Add the fields "Full Name", "Email" and "Address".
            </li>
        </ul>
    </li>
    <li>
        Make form Multi-lingual
        <ul>
            <li>
                app/Http/Middleware add SetLocale.php
            </li>
            <li>
                app/Http/kernel.php add line "'setlocale' => \App\Http\Middleware\SetLocale::class" to $routeMiddleware array
            </li>
            <li>
                For your add, store, edit, update routes add '{locale}/' to the base of the 
                route path and add "'middleware' => 'setlocale'" to the route. Adjust all links (& form action) to the routes.
<pre>
Route::group([
    'prefix' => '{locale}/programmer',
    'name' => 'programmer.',
    'where' => ['locale' => '^(en_US|de_DE)$'],
    'middleware' => 'setlocale'
    ], function() {
    
        Route::get('/create', 'ProgrammerExperienceController@add')->name('programmer.create');
        Route::get('/{id}/edit', 'ProgrammerExperienceController@edit')->where(['id'=>'[0-9]+'])->name('programmer.edit');
        Route::post('/', 'ProgrammerExperienceController@store')->name('programmer.store');
        Route::put('/{id}', 'ProgrammerExperienceController@update')->where(['id'=>'[0-9]+'])->name('programmer.update');
});
</pre>
            </li>
            <li>
                Rename directory resources/lang/en to resources/lang/en_US. Create directory resources/lang/de_DE or 
                directories for whichever languages you want to use. 
            </li>
            <li>
                In resources/lang/en_US add the messages.php file (validation.php should already exist). 
                In resources/lang/de_DE add the messages.php and validation.php files.
            </li>
            <li>
                Implement the translator for existing form labels and submit button.<br>
                &commat;lang('messages.fullName'), &commat;lang('messages.email'), 
                &commat;lang('messages.address') &amp; &commat;lang('messages.formSave')
            </li>
            <li>
                Create language switch links:
                <ul>
                    <li>
                        Create file app/helpers.php. Add function languageSwitch().
                    </li>
                    <li>
                        In composer.json autoload section add:
<pre>
"files": [
    "app/helpers.php"
]</pre>
                        Run command: "composer dump-autoload"
                    </li>
                    <li>
                        In app/Providers/AppServiceProvider boot() method add:
<pre>
Blade::directive('languageSwitch', function () {
    return "&lt;?php echo languageSwitch(); ?&gt;";
});</pre>
                    </li>
                    <li>
                        In edit.blade.php create a language switch button by adding "&commat;languageSwitch()"
                    </li>                    
                </ul>
            </li>
        </ul>
    </li>
    <li>
        Make options Countries, Programming Languages and Work Types available to the form
        <ul>
            <li>
                Create Repository classes for Countries and Programming languages.<br>
                \App\Repository\CountriesRepository<br>
                \App\Repository\ProgrammingLanguagesRepository
            </li>
            <li>
                Create \App\Model\ProgrammingExperienceFormOptions class
            </li>
            <li>
                Inject \App\Model\ProgrammingExperienceFormOptions as a dependency into the controller add()
                method and use this object to send "countries", "languages" and "workTypes" to the view. Used as 
                options in the form.
<pre>
public function add(\App\Model\ProgrammingExperienceFormOptions $formOptions)
{
    return view('programmer/edit', [
        'countries' => $formOptions->getCountries('en_US'),
        'languages' => $formOptions->getProgrammingLanguages(),
        'workTypes' => $formOptions->getWorkTypeOptions(),
    ]);
}
</pre>
            </li>
        </ul>
    </li>
    <li>
        Add to edit.blade.php fields Country and Programming Languages
    </li>
    <li>
        Build the dynamic "Additional Languages" section
        <ul>
            <li>
                In composer.json require - "laminas/laminas-escaper": "~2.5"<br>
                Command: composer update
            </li>
            <li>
                If not already created, create the file app/helpers.php<br>
                <ul>
                    <li>
                        Create file app/helpers.php.
                    </li>
                    <li>
                        In composer.json autoload section add:
<pre>
"files": [
    "app/helpers.php"
]</pre>
                        Run command: "composer dump-autoload"
                    </li>
                </ul>
            </li>
            <li>
                Add to app/helpers.php the line:<br>
                use Illuminate\Database\Eloquent;<br>
                and functions attributeTemplate() and formIterator()
            </li>
            <li>
                In App\Providers\AppServiceProvider boot() method add:
<pre>Blade::directive('attributeTemplate', function ($expression) {
    return "&lt;?php echo attributeTemplate({$expression}); ?&gt;";
});</pre>
            </li>
            <li>
                Create a partial view blade file for the items that will be added to "Additional Languages".<br>
                Example: resources/views/programmer/_list-row.blade.php<br>
                Note:
                <ul>
                    <li>
                        We are relying on the way PHP converts submitted field names with square brackets into arrays.
                        The HTML name attribute for the field here is name="additionalLanguages[]". This will give us an
                        "additionalLanguages" array in the PHP if any of these rows are present when the form is submitted.
                    </li>
                    <li>
                        There are 2 different names used in creating this row. The name belonging to the attributes
                        is used to create the actual value for the name attribute in the HTML, e.g. name="additionalLanguages[]".
                        The other $name (e.g. additionalLanguages[0]) is used for retrieving the value for the field and could 
                        be used to retrieve errors for that field.
                    </li>
                </ul>
            </li>
            <li>
                Add "Additional Languages" to edit.blade.php<br>
                NOTE:
                <uL>
                    <li>
                        The function attributeTemplate() was used to create a value for the data-template attribute.
                        The data-template attribute will be used by JavaScript to add new rows to the "Additional Languages". 
                        Laravel does not come with a built-in HTML attribute escaper, hence the need for the 
                        attributeTemplate() function.
                    </li>
                    <li>
                        Any existing "Additional Languages" are iterated through with help from the formIterator() function. 
                        If the form has been submitted then it iterates through the submitted values (from old() or session)
                        otherwise if an Eloquent\Model is present then it iterates through values from it. The last option 
                        is to iterate through an empty array.
                    </li>
                </ul>
            </li>
            <li>
                Create the JavaScript (resources/js/programmer/edit.js) for the edit.blade.php and link to it.
            </li>
        </ul>
    </li>
    <li>
        Create Job Experience dynamic section
        <ul>
            <li>
                Create the partial view blade template used to input each IT job (experience) the programmer has had
                (i.e. resources/views/programmer/_experience.blade.php).
                <br> 
                Each job will become part of the experience[] array. Each job in the array will be given a unique integer key.
                Example: experience[0] will be an array containing data on the first job 
                (experience[0][companyName], experience[0][officeLocation], etc). It in turn contains a couple of arrays
                (experience[0][languagesUsed][] and experience[0][additionalLanguagesUsed][]). 
            </li>
            <li>
                Add the "Work Experience" section to edit.blade.php. 
                <ul>
                    <li>
                        The function formIterator() is used while iterating through 
                        the entered work experience and adding the _experience.blade.php for each job.
                    </li>
                    <li>
                        _experience.blade.php needs to be assigned variables: 'k' is a unique integer key for each experience;
                        'countries', 'languages' and 'workTypes' are for select, radio and checkbox options.
                    </li>
                    <li>
                        Add a button that will have the JavaScript "Add Experience" (or Job) event attached to it.
                    </li>
                </ul>
            </li>
        </ul>
    </li>
    <li>
        Make the _experience.blade.php template available to JavaScript so as to add new Job Experience blocks
        <ul>
            <li>
                To the HTML container that is parent to the "Work Experience" section add a data attribute for the 
                _experience.blade.php template and use the attributeTemplate() function to give it a value, 
                e.g. data-template="&commat;attributeTemplate('programmer._experience', ['k'=>'__index1__', 'countries'=>$countries, 'languages'=>$languages, 'workTypes'=>$workTypes])"
                <br>
                The 'k' variable (above) is assigned a placeholder that JavaScript will replace with an integer 
                key when adding a new job experience block to the form.
            </li>
            <li>
                Add to /app/helpers.php the function nextKey().
            </li>
            <li>
                In app/Providers/AppServiceProvider boot() method add:
<pre>Blade::directive('nextKey', function ($expression) {
    return "&lt;?php echo nextKey({$expression}); ?&gt;";
});</pre>
            </li>
            <li>
                To the HTML container that is parent to the "Work Experience" section add the data attribute:<br>
                data-nextkey="&lbrace;!! nextKey(($personExperience ?? null), 'experience') !!&rbrace;"<br>
                <br>
                The purpose of this is to allow JavaScript to know which array key to use when adding a new 
                job experience block. Each integer key must be unique so it needs to know the minimum integer 
                it can increment from.
            </li>
            <li>
                Also add to the HTML container that is parent to the "Work Experience" section the following 
                data attributes:<br>
                data-translate-remove-tag="&lbrace;&lbrace; trans('messages.confirmRemoveTag') &rbrace;&rbrace;"<br>
                data-translate-remove-job="&lbrace;&lbrace; trans('messages.confirmRemoveJob') &rbrace;&rbrace;"<br>
                These are used by JavaScript to give confirm() messages in the appropriate language.
            </li>
            <li>
                Add the necessary JavaScript for adding and removing Job Experience blocks.
            </li>
        </ul>
    </li>
    <li>
        Add reCAPTCHA
        <ul>
            <li>
                Follow the instructions from reCAPTCHA to install Version 2 onto the form
            </li>
            <li>
                To show an error message when reCAPTCHA fails to verify the user is human place the following 
                code at the top of the form
<pre>
&commat;error('g-recaptcha-response')
    &lt;p class="error">&lbrace;&lbrace; $message &rbrace;&rbrace;&lt;/p>
&commat;enderror</pre>
            </li>
            <li>
                How to validate the reCAPTCHA will be shown later in the validation section.
            </li>
        </ul>
    </li>
    <li>Building the form HTML is complete. We now move on to validating the form for the controller store() method.</li>
    
    
    
    <li>
        Run command "php artisan make:model PersonExperience" to create the file
        /app/PersonExperience.php. Fill in the details of this Eloquent\Model. In my case
        I have moved it into the Repository directory.
    </li>
    <li>
        Create the class App\Model\ReCaptchaV3.
    </li>
    <li>
        Run command "php artisan make:request ProgrammingExperienceSave" to create the file
        /app/Http/Requests/ProgrammingExperienceSave.php with a skeleton ProgrammingExperienceSave
        class in it. This class will contain the form validation logic.
        <br><br>
        Add the contents from the <a href="{{ $gitHubRepo }}" target="_blank">GitHub Repository</a>
        to this file. The code here is quite self-explanatory if you are already familiar with writing
        <a href="https://laravel.com/docs/7.x/validation" target="_blank">Laravel Validation logic</a>.
    </li>
    <li>
        Above the Controller (i.e. ProgrammerExperienceController) add the lines:
<pre>
use App\Repository\PersonExperience;
use App\Http\Requests\ProgrammingExperienceSave;
</pre>
        Then inside the Controller add the method savePrepared(). This method would
        very much need to be customised to your database set up.
<pre>
private function savePrepared(array $data) : array
{
    unset($data['g-recaptcha-response']);
    $data['lastEdit'] = date('Y-m-d H:i:s');
    $data['additionalLanguages'] = array_values($data['additionalLanguages']);
    $data['experience'] = array_values($data['experience']);

    foreach($data['experience'] AS &$experience){
        $experience['additionalLanguagesUsed'] = array_values($experience['additionalLanguagesUsed']);
    }

    return $data;
}
</pre>
        Then change the store() action to:
<pre>
public function store(ProgrammingExperienceSave $request)
{
    $validatedData = $this->savePrepared($request->validated());
    $save = array_merge($validatedData, ['sessionId'=>session()->getId()]);
    PersonExperience::create($save)->save();

    return redirect()->route('programmer.list');
}
</pre>
        Because the request object is type-hinted (i.e. ProgrammingExperienceSave) as an 
        argument for the store() action, Laravel validates the request before the 
        controller method is called. If validation fails, a redirect response will 
        be generated to send the user back to their previous location. The errors 
        will also be flashed to the session so they are available for display. 
    </li>
    <li>We now move on to being able to edit an existing record</li>
    <li>
        Change the edit() action to:
<pre>
public function edit(FormOptions $formOptions)
{
    // Customize to your purposes
    $personExp = PersonExperience::where(['sessionId'=>session()->getId(), 'id'=>request('id')])->firstOrFail();

    return view('programmer/edit', [
        'personExperience' => $personExp,
        'countries' => $formOptions->getCountries('en_US'),
        'languages' => $formOptions->getProgrammingLanguages(),
        'workTypes' => $formOptions->getWorkTypeOptions(),
    ]); 
}
</pre>
    </li>
    <li>
        Change the update() action to:
<pre>
public function update(ProgrammingExperienceSave $request)
{
    $personExp = PersonExperience::where(['sessionId'=>session()->getId(), 'id'=>request('id')])->firstOrFail(); // Customize to your needs
    $validatedData = $this->savePrepared($request->validated());
    $personExp->update($validatedData);

    return redirect()->route('programmer.list');
}
</pre>
    </li>
    <li>
        In the view file "edit.blade.php" replace the code that creates the form opening tags with: 
<pre>
&commat;if(!empty($personExperience))
&lbrace;!! Form::model($personExperience, [
                'route'=>['programmer.update', 'id'=>$personExperience->id, app()->getLocale()], 
                'method'=>'put', 
                'id'=>"experience"
    ]) 
!!&rbrace;
&commat;else
&lbrace;!! Form::open(['route'=>['programmer.store', 'locale'=>app()->getLocale()], 'method'=>'post', 'id'=>"experience"]) !!&rbrace;
&commat;endif
</pre>
    </li>
    <li>Finished!</li>
</ul>

@endsection