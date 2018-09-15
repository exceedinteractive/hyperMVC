<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* There comes a time when you want to replace certain words or characters in the string. 
* What if you have list of censored words or HTML tags you want to replace? 
* This function comes handy in those situations.
* @param $value of the haystack.
* @param $value array of characters or strings to replace.
* @usage $string = 'The {b}anchor text{/b} is the {b}actual word{/b} or words used {br}to describe the link {br}itself';
* $replace_array = array('{b}' => '<b>','{/b}' => '</b>','{br}' => '<br />');
* echo string_parser($string, $replace_array);
**/

function string_parser($string, $replacer)
{
    $result = str_replace(array_keys($replacer), array_values($replacer),$string);
    return $result;
}

/**
* Thanks to Ryan Stemkoski for this neat regular expression snippet to remove special characters from string.
* @param $value is a string containg ACII characters
* @usage $output = "Clean this copy of invalid non ASCII äócharacters.";
* echo clean_non_ascii($output);
**/

function clean_none_ascii($output) 
{
    $output = preg_replace('/[^(x20-x7F)]*/','', $output);
    return $output;
}

/**
* PHP function below comes handy when you want to generate random names, prefix, temp password in your project.
* @param $value is a string that set the length of rendom characters to return.
* @usage echo generateRandomString(20);
**/

function generateRandomString($length = 10) 
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

/**
*Shorten very long text to specified length, this function find the first space that is within the limit and truncate at that point.
* @param $value of the very long text.
* @param $value length to shorten.
* @usage$string = 'The behavior will not truncate an individual word, it will find the first space that is within the limit and truncate.';
* echo truncate($string,60);
**/

function truncate($text, $length = 0)
{
    if ($length > 0 && strlen($text) > $length)
    {
        $tmp = substr($text, 0, $length); 
        $tmp = substr($tmp, 0, strrpos($tmp, ' '));
        if (strlen($tmp) >= $length - 3) {
        	$tmp = substr($tmp, 0, strrpos($tmp, ' '));
        }
        $text = $tmp.'...';
    }
    return $text;
}

/**
* Shorten very long text over the specified character limit. For example, it transforms “Really long title” to “Really…title”.
* @param $value long text string to abridge
* @param $value number of characters to abridge to.
* @param $value size of intro.
* @usage$string = 'The behavior will not truncate an individual word, it will find the first space that is within the limit and truncate.';
* echo abridge($string,60);
**/

function abridge($text, $length = 50, $intro = 30)
{
    // Abridge the item text if it is too long.
    if (strlen($text) > $length)
    {
        // Determine the remaining text length.
        $remainder = $length - ($intro + 3);

        // Extract the beginning and ending text sections.
        $beg = substr($text, 0, $intro);
        $end = substr($text, strlen($text) - $remainder);

        // Build the resulting string.
        $text = $beg . '...' . $end;
    }
return $text;
}