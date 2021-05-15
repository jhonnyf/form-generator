<?php

namespace SenventhCode\FormGenerator;

use Illuminate\Support\ServiceProvider;
use SenventhCode\FormGenerator\Components\Fields;

class FormServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'form-service');
        $this->loadViewComponentsAs('form-service', [
            Fields::class,
        ]);
    }

    public function register()
    {

    }
}
