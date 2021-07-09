<?php

namespace SenventhCode\FormGenerator;

use Illuminate\Support\ServiceProvider;

class FormGeneratorProvider extends ServiceProvider
{
    public function boot()
    {
        // Views
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'form-generator');

    }

    public function register()
    {

    }
}
