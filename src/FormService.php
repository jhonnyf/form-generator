<?php

namespace SenventhCode\FormGenerator;

use Illuminate\Support\Facades\DB;

class FormService
{
    private static $table;
    private static $fields;
    private static $values;

    public static function init($Model, array $values = [])
    {
        self::$table  = (new $Model)->getTable();
        self::$values = $values;
        self::getFields();

        return self::formRules();
    }

    private static function getFields(): void
    {
        $metadata     = static::tableMetadata(self::$table);
        self::$fields = static::transformFields($metadata);

        return;
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

    private static function formRules(): array
    {
        $fields = self::$fields;

        unset($fields['active']);
        unset($fields['created_at']);
        unset($fields['updated_at']);

        $fields = static::formatFields($fields, self::$values);
        $fields = static::baseRules($fields);
        $fields = static::customRules($fields);

        return $fields;
    }

    private static function formatFields(array $columns, array $formValues = []): array
    {
        $fields = [];

        $text     = ['varchar', 'char'];
        $number   = ['bigint', 'tinyint', 'int', 'decimal', 'bigint unsigned'];
        $dateTime = ['datetime', 'timestamp'];

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
            } elseif (in_array($column['type'], $dateTime)) {
                $type  = 'datetime-local';
                $value = str_replace(" ", "T", $value);
            } else {
                exit("Tipo não definido - <b>{$column['type']}</b>");
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

    private static function tableMetadata(string $table): array
    {
        return DB::select("DESCRIBE {$table};");
    }

    private static function baseRules(array $fields): array
    {
        $className = str_replace('_', ' ', self::$table);
        $className = ucwords($className);
        $className = str_replace(' ', '', $className);

        $path = "\SenventhCode\ConsoleService\App\Services\Metadata\Users";
        if (method_exists($path, 'baseRules')) {
            $fields = $path::baseRules($fields);
        }

        return $fields;
    }

    private static function customRules(array $fields): array
    {
        $className = str_replace('_', ' ', self::$table);
        $className = ucwords($className);
        $className = str_replace(' ', '', $className);

        if (file_exists(app_path("Services/FormService/{$className}.php"))) {
            $path = "\App\Services\FormService\\{$className}";
            if (method_exists(new $path, 'customRules')) {
                $fields = $path::customRules($fields);
            }
        }

        return $fields;
    }

}
