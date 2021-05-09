<?php

namespace App\Services\Metadata;

use App\Services\Metadata\Interfaces\RulesInterface;

abstract class Master implements RulesInterface
{
    public static function tableRules(array $columns): array
    {
        return $columns;
    }

    public static function formRules(array $columns, array $formValues = []): array
    {
        return MetadataService::formRulesMain($columns, $formValues);
    }

}
