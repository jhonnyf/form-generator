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
        return get_object_vars($this);
    }
}
