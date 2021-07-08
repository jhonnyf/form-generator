<?php

namespace SenventhCode\FormGenerator;

use SenventhCode\FormGenerator\Elements\Button;
use SenventhCode\FormGenerator\Elements\Input;
use SenventhCode\FormGenerator\Elements\Select;
use SenventhCode\FormGenerator\Elements\Textarea;

class FormGenerator
{
    private $action;
    private $autocomplete = "off";
    private $enctype;
    private $method = 'POST';
    private $class;
    private $elements;

    public function __construct(string $action)
    {
        $this->action = $action;
    }

    public static function modelForm($Model, $values)
    {
        # code...
    }

    public static function customForm()
    {

    }

    public function input(string $name)
    {
        $Input = new Input;
        $Input->setName($name);

        $this->elements[] = $Input;

        return $Input;
    }

    public function select(string $name)
    {
        $Select = new Select;
        $Select->setName($name);

        $this->elements[] = $Select;

        return $Select;
    }

    public function textarea(string $name)
    {
        $Textarea = new Textarea;
        $Textarea->setName($name);

        $this->elements[] = $Textarea;

        return $Textarea;
    }

    public function button(string $name)
    {
        $Button = new Button;
        $Button->setName($name);

        $this->elements[] = $Button;

        return $Button;
    }

    /**
     * SETS
     */

    public function setAction(string $action)
    {
        $this->action = $action;
    }

    public function setAutocomplete(string $autocomplete)
    {
        $this->autocomplete = $autocomplete;
    }

    public function setEnctype(string $enctype)
    {
        $this->enctype = $enctype;
    }

    public function setMethod(string $method)
    {
        $this->method = mb_strtoupper($method);
    }

    public function setClass(array $class)
    {
        $this->class = $class;
    }

    /**
     * GET
     */

    public function getElements(): array
    {
        $response = [];
        foreach ($this->elements as $element) {
            $response[] = $element->returnAttributes();
        }

        return $response;
    }

    public function render(): array
    {
        $formElements = get_object_vars($this);
        unset($formElements['elements']);

        $response = [
            'form'     => $formElements,
            'elements' => $this->getElements(),
        ];

        return $response;
    }

}
