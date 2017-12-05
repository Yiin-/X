<?php

if (!function_exists('resource_name')) {
    function resource_name($input) {
        try {
            $input = (new ReflectionClass($input))->getShortName();
        }
        catch (\ReflectionException $e) {
            //
        }
        return kebab_case(str_singular($input));
    }
}