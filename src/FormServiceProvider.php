<?php

namespace SenventhCode\FormGenerator;

use Illuminate\Support\ServiceProvider;

class FormServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewComponentsAs('form-service', [
            Fields::class
        ]);
    }

    public function register()
    {

    }
}
