<?php

if (!function_exists("str_putcsv"))
{
    function str_putcsv(array $input, string $delimiter = ",", string $closure = '"')
    {
        return $closure.join( $closure . $delimiter . $closure, $input) . $closure;
    }
}

?>