<?php

namespace SenventhCode\FormGenerator;

interface FormServiceInterface
{
    public static function customRules(array $fields): array;
}
