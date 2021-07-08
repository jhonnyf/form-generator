<?php

namespace SenventhCode\FormGenerator\Elements;

class Select extends Element
{

    private $multiple = false;
    private $options;

    /**
     * SETS
     */

    public function setMultiple(bool $multiple)
    {
        $this->multiple = $multiple;

        return $this;
    }

    public function setOptions(bool $options)
    {
        $this->options = $options;

        return $this;
    }

    public function returnAttributes(): array
    {
        return get_object_vars($this);
    }
}
