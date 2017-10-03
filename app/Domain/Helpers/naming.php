<?php

if (!function_exists('resource_name')) {
    function resource_name($input) {
        $input = (new ReflectionClass($input))->getShortName();
        return kebab_case(str_singular($input));
    }
}