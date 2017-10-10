<?php

if (!function_exists('parse_bill_number_pattern')) {
    function parse_bill_number_pattern($pattern, $counter = 0) {
        $map = [
            '{year}' => date('Y'),
            '{month}' => date('m'),
            '{day}' => date('d'),
            '{hour}' => date('H'),
            '{minute}' => date('m'),
            '{counter}' => $counter
        ];
        return preg_replace(array_keys($map), array_values($map), $pattern);
    }
}