<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Form;
use App\Model\ReCaptchaV3;
use GuzzleHttp\Client;

class FormServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ReCaptchaV3::class, function ($app) {
            return new ReCaptchaV3(new Client(), env('RECAPTCHA_SECRET'), \Log::getLogger(), null);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Form::component('myInput', 'components.form.myInput', ['type', 'name', 'value' => null, 'attributes' => []]);
        Form::component('myTextarea', 'components.form.myTextarea', ['name', 'value' => null, 'attributes' => []]);
        Form::component('mySelect', 'components.form.mySelect', ['name', 'options', 'value' => null, 'attributes' => []]);
        Form::component('myCheckboxList', 'components.form.myCheckboxList', ['name', 'options', 'attributes' => []]);
        Form::component('myRadioList', 'components.form.myRadioList', ['name', 'options', 'attributes' => []]);
    }
}
