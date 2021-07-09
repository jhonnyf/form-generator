<?php

namespace SenventhCode\FormGenerator\Elements;

class Button extends Element
{
    private $type = 'submit';

    /**
     * SETS
     */

    public function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }

    public function returnAttributes(): array
    {
        $className = explode("\\", get_class($this));
        $className = mb_strtolower(array_pop($className));

        return array_merge(get_object_vars($this), ['elementType' => $className]);
    }
}
