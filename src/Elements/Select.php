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

    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    public function returnAttributes(): array
    {
        $className = explode("\\", get_class($this));
        $className = mb_strtolower(array_pop($className));

        return array_merge(get_object_vars($this), ['elementType' => $className]);
    }
}
