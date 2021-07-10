<?php

namespace SenventhCode\FormGenerator;

use Illuminate\Support\Facades\DB;
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
    private $elements = [];

    public function __construct(string $action)
    {
        $this->action = $action;
    }

    public function modelForm($Model, array $values)
    {
        $tabel = (new $Model)->getTable();

        $fields = $this->tableMetadata($tabel);
        $fields = $this->transformFields($fields);
        $fields = $this->baseRules($tabel, $fields);
        $fields = $this->formatFields($fields, $values);

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
        if (count($this->elements) == 0) {
            return [];
        }

        $response = [];
        foreach ($this->elements as $element) {
            $response[] = $element->returnAttributes();
        }

        return $response;
    }

    /**
     * METHODS
     */

    public function render()
    {
        $formElements = get_object_vars($this);
        unset($formElements['elements']);

        $data = [
            'form'     => $formElements,
            'elements' => $this->getElements(),
        ];

        return view('form-generator::form-generator', $data);
    }

    private function tableMetadata(string $table): array
    {
        return DB::select("DESCRIBE {$table};");
    }

    private function transformFields(array $fields): array
    {
        foreach ($fields as $field) {

            $max_length   = null;
            $position_int = strpos($field->Type, "(");
            if ($position_int !== false) {

                preg_match('/[^a-z]+/', $field->Type, $matches);

                $max_length = str_replace('(', '', $matches[0]);
                $max_length = str_replace(')', '', $max_length);
            }

            $metadata[$field->Field]['name']       = $field->Field;
            $metadata[$field->Field]['type']       = $position_int === false ? $field->Type : substr($field->Type, 0, $position_int);
            $metadata[$field->Field]['max_length'] = trim($max_length);
            $metadata[$field->Field]['key']        = strtolower($field->Key);
            $metadata[$field->Field]['default']    = $field->Default;
        }

        unset($metadata['active']);
        unset($metadata['created_at']);
        unset($metadata['updated_at']);

        return $metadata;
    }

    private function formatFields(array $columns, array $formValues = []): array
    {
        $text     = ['varchar', 'char'];
        $number   = ['bigint', 'tinyint', 'int', 'decimal', 'bigint unsigned'];
        $dateTime = ['datetime', 'timestamp'];

        $fields = [];
        foreach ($columns as $column) {

            $value = isset($formValues[$column['name']]) ? $formValues[$column['name']] : '';
            if (strlen($column['default']) > 0 && empty($value)) {
                $value = $column['default'];
            }

            $elementType = isset($column['elementType']) ? $column['elementType'] : 'input';
            if ($elementType == 'input') {

                if ($column['key'] === 'pri') {
                    $type = 'hidden';
                } elseif (in_array($column['type'], $text)) {
                    $type = 'text';
                } elseif (in_array($column['type'], $number)) {
                    $type = 'number';
                } elseif ($column['type'] === 'date') {
                    $type = 'date';
                } elseif (in_array($column['type'], $dateTime)) {
                    $type  = 'datetime-local';
                    $value = str_replace(" ", "T", $value);
                } else {
                    $type = $column['type'];
                }
            }

            $elementType = $this->$elementType($column['name'])
                ->setType($type)
                ->setValue($value);

            if ($column['max_length'] > 0) {
                $elementType->setMaxLength($column['max_length']);
            }

            if (isset($column['required'])) {
                $elementType->setRequired($column['required']);
            }

            if (isset($column['label'])) {
                $elementType->setLabel($column['label']);
            }
        }

        $this->button('Salvar');

        return $fields;
    }

    private function baseRules(string $table, array $fields): array
    {
        $className = str_replace('_', ' ', $table);
        $className = ucwords($className);
        $className = str_replace(' ', '', $className);

        $path = "\SenventhCode\ConsoleService\App\Services\Metadata\Users";
        if (method_exists($path, 'baseRules')) {
            $fields = $path::baseRules($fields);
        }

        return $fields;
    }
}
