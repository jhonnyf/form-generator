<?php

namespace SenventhCode\FormGenerator;

use Illuminate\Support\Facades\DB;

class MetadataService
{
    public static function tableFields(string $tableName): array
    {
        $columns   = static::tableMetadata($tableName);
        $className = static::createNameClass($tableName);

        $pathClass = static::checkClass($className);
        $fields    = $pathClass::tableRules($columns);

        return $fields;
    }

    public static function tableMetadata(string $table): array
    {
        return DB::select("DESCRIBE {$table};");
    }

    private static function checkClass(string $className): string
    {
        $path = "\App\Services\Metadata\Master";
        if (file_exists(app_path("Services/Metadata/Modules/{$className}.php"))) {
            $path = "\App\Services\Metadata\Modules\\{$className}";
        }

        return $path;
    }

    private static function createNameClass(string $tableName): string
    {
        $className = str_replace('_', ' ', $tableName);
        $className = ucwords($className);
        $className = str_replace(' ', '', $className);

        return $className;
    }
}
