<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Quickly get extension from a filename string.
* @param $value any file name
* @usage $filename = 'this_myfile.cd.doc';
* echo get_extension($filename);
**/

function get_extension($filename)
{
  $myext = substr($filename, strrpos($filename, '.'));
  return str_replace('.','',$myext);
}

/**
* There are many snippets to remove file extension from file name, but this PHP code does exactly what it should, remove extension from any file name, two or four characters extensions.
* @param $value any file name
* @usage echo RemoveExtension('myfile.ho');
**/

function RemoveExtension($strName)
{
     $ext = strrchr($strName, '.');
     if($ext !== false)
     {
         $strName = substr($strName, 0, -strlen($ext));
     }
     return $strName;
}

/**
* The short function to determines human readable filesize with bytes ending.
* @param $value location of the file to check
* @usage $thefile = filesize('test_file.mp3')
* echo format_size($thefile);
**/

function format_size($size) 
{
      $sizes = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
      if ($size == 0) { return('n/a'); } else {
      return (round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $sizes[$i]); }
}