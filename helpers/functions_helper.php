<?php

function appName() {
    return 'Laboratorios Biomont';
}

/**
 * Formato de fecha
 */
function formatDate($str,$type) {
    $pre = '';
    if ($type == 1) {
        $sep = '/';
    } else if ($type == 2) {
        $sep = '-';
    } else if ($type == 3) {
        $sep = '/';
        $pre = '-';
    } else if ($type == 4) {
        $sep = '-';
        $pre = '/';
    }
    return implode($pre, array_reverse(explode($sep, $str)));
}

/**
 * Verificar data
 */
function var_log($data){
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

function utf8_encode_recursive($array) {
    foreach ($array as &$value) {
        if (is_array($value)) {
            $value = utf8_encode_recursive($value); // Llamada recursiva para los subarrays
        } elseif (is_string($value)) {
            $value = utf8_encode($value);
        }
    }
    return $array;
}
