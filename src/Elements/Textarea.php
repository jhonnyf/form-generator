<?php

namespace SenventhCode\FormGenerator\Elements;

class Textarea extends Element
{

    public function returnAttributes(): array
    {
        return get_object_vars($this);
    }
}
