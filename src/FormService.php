<?php

namespace SenventhCode\FormGenerator;

use SenventhCode\FormGenerator\MetadataService;

class FormService
{
    private static $table;
    private static $fields;

    public static function init($Model)
    {
        self::$table = (new $Model)->getTable();
        self::getFields();

        // $className = static::createNameClass($tableName);
        // $pathClass = static::checkClass($className);
        // $fields    = $pathClass::formRules($columns, $formValues);

        return self::formRules();
    }

    private static function getFields()
    {
        $metadata     = MetadataService::tableMetadata(self::$table);
        self::$fields = static::transformFields($metadata);
    }

    private static function transformFields(array $fields): array
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

        return $metadata;
    }

    private static function formRules()
    {
        return static::formatFields(self::$fields);
    }

    private static function formatFields(array $columns, array $formValues = []): array
    {
        $fields = [];

        $text   = ['varchar', 'char'];
        $number = ['bigint', 'tinyint', 'int', 'decimal'];

        foreach ($columns as $column) {

            $value = isset($formValues[$column['name']]) ? $formValues[$column['name']] : '';
            if (strlen($column['default']) > 0 && empty($value)) {
                $value = $column['default'];
            }

            $element = 'input';

            if ($column['key'] === 'pri') {
                $type = 'hidden';
            } elseif (in_array($column['type'], $text)) {
                $type = 'text';
            } elseif (in_array($column['type'], $number)) {
                $type = 'number';
            } elseif ($column['type'] === 'date') {
                $type = 'date';
            } elseif ($column['type'] === 'datetime') {
                $type  = 'datetime-local';
                $value = str_replace(" ", "T", $value);
            } else {
                exit("Tipo não definido - {$column['type']}");
            }

            $fields[$column['name']] = [
                'element'    => $element,
                'name'       => $column['name'],
                'type'       => $type,
                'max_length' => $column['max_length'],
                'value'      => $value,
            ];
        }

        return $fields;
    }
}
