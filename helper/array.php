<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Hyper Array Helper
**/

/**
* // One Dimentional Array to Table Function
*
* class              – If set, this will be the CSS class of the table itself.
* 
* custom_headers     – If this array is present, it will replace the headers found in the $data array.
* 
* capitalize_headers – If TRUE, ucwords will be applied to all headers not affected by custom_headers.
* 
* format_functions   – An optional array. With this, you can specify the name of a function to clean up the data in a particular column.
* 
* column_widths      – Optional array that allows you to specific widths (in pixels) for each of the columns.
* 
* nowrap_head        – If TRUE, nowrap will be added to the table header row cells.
* 
* nowrap_body        – If TRUE, nowrap will be added to the table body cells.
**/

function array_to_table($data, $args = false) 
{
    if (!is_array($args)) { $args = array(); }
    foreach (array('class','column_widths','custom_headers','format_functions','nowrap_head','nowrap_body','capitalize_headers') as $key) {
        if (array_key_exists($key,$args)) { $$key = $args[$key]; } else { $$key = false; }
    }
    if ($class) { $class = ' class="'.$class.'"'; } else { $class = ''; }
    if (!is_array($column_widths)) { $column_widths = array(); }

    //get rid of headers row, if it exists (headers should exist as keys)
    if (array_key_exists('headers',$data)) { unset($data['headers']); }

    $t = '<table'.$class.'>';
    $i = 0;
    foreach ($data as $row) {
        $i++;
        //display headers
        if ($i == 1) { 
            foreach ($row as $key => $value) {
                if (array_key_exists($key,$column_widths)) { $style = ' style="width:'.$column_widths[$key].'px;"'; } else { $style = ''; }
                $t .= '<col'.$style.' />';
            }
            $t .= '<thead><tr>';
            foreach ($row as $key => $value) {
                if (is_array($custom_headers) && array_key_exists($key,$custom_headers) && ($custom_headers[$key])) { $header = $custom_headers[$key]; }
                elseif ($capitalize_headers) { $header = ucwords($key); }
                else { $header = $key; }
                if ($nowrap_head) { $nowrap = ' nowrap'; } else { $nowrap = ''; }
                $t .= '<td'.$nowrap.'>'.$header.'</td>';
            }
            $t .= '</tr></thead>';
        }

        //display values
        if ($i == 1) { $t .= '<tbody>'; }
        $t .= '<tr>';
        foreach ($row as $key => $value) {
            if (is_array($format_functions) && array_key_exists($key,$format_functions) && ($format_functions[$key])) {
                $function = $format_functions[$key];
                if (!function_exists($function)) { custom_die('Data format function does not exist: '.htmlspecialchars($function)); }
                $value = $function($value);
            }
            if ($nowrap_body) { $nowrap = ' nowrap'; } else { $nowrap = ''; }
            $t .= '<td'.$nowrap.'>'.$value.'</td>';
        }
        $t .= '</tr>';
    }
    $t .= '</tbody>';
    $t .= '</table>';

    return $t;
}
