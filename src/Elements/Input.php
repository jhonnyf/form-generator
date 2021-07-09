<?php

namespace SenventhCode\FormGenerator\Elements;

class Input extends Element
{
    private $type     = 'text';
    private $readonly = false;
    private $min;
    private $max;
    private $maxlength;
    private $checked;

    /**
     * SETS
     */

    public function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }

    public function setReadonly(bool $readonly)
    {
        $this->readonly = $readonly;

        return $this;
    }

    public function setMin(int $min)
    {
        $this->min = $min;

        return $this;
    }

    public function setMax(int $max)
    {
        $this->max = $max;

        return $this;
    }

    public function setMaxLength(int $maxlength)
    {
        $this->maxlength = $maxlength;

        return $this;
    }

    public function setChecked(bool $checked)
    {
        $this->checked = $checked;

        return $this;
    }

    public function returnAttributes(): array
    {
        $className = explode("\\", get_class($this));
        $className = mb_strtolower(array_pop($className));

        return array_merge(get_object_vars($this), ['elementType' => $className]);
    }
}
