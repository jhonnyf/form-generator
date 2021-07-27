<?php

namespace SenventhCode\FormGenerator\Elements;

abstract class Element
{

    protected $name;
    protected $label;
    protected $value;
    protected $required = false;
    protected $disabled = false;
    protected $classes = [];

    /**
     * SETS
     */

    public function setName(string $name)
    {
        $this->name = $name;
        $this->setLabel($this->name);

        return $this;
    }

    public function setLabel(string $label)
    {
        $this->label = $label;

        return $this;
    }

    public function setValue(string $value)
    {
        $this->value = $value;

        return $this;
    }

    public function setDisabled(bool $disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }

    public function setRequired(bool $required)
    {
        $this->required = $required;

        return $this;
    }

    public function setClass(array $classes)
    {
        $this->classes = $classes;

        return $this;
    }
}
