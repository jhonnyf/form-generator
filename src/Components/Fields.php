<?php

namespace SenventhCode\FormGenerator;

use Illuminate\Http\Request;
use Illuminate\View\Component;

class FormFields extends Component
{
    public $formFields;
    public $id;
    public $route;
    public $extraData;
    public $requestData;

    public function __construct($formFields, $id, $route, $extraData = [], Request $request)
    {
        $this->formFields  = $formFields;
        $this->id          = $id;
        $this->route       = $route;
        $this->extraData   = $extraData;
        $this->requestData = $request->all();
    }

    public function render()
    {
        return view('components.form-fields');
    }
}
