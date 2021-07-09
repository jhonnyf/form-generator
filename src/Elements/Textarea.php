<?php

namespace SenventhCode\FormGenerator\Elements;

class Textarea extends Element
{

    public function returnAttributes(): array
    {
        $className = explode("\\", get_class($this));
        $className = mb_strtolower(array_pop($className));

        return array_merge(get_object_vars($this), ['elementType' => $className]);
    }
}
