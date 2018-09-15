<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Hyper URL Helper
**/

// Base URL is set in Hyper config file

function base_url()
{
	return SITE_ROOT;
}

/**
 * Make links that will obey Hyper SEO_URL setting
 * 
 * @param $value for the link text
 * @param $value of the href attribute. Either an array or single controller name.
 * @usage echo create_link('click me', 'controller');
 */

function create_link($text, $page = '')
{

	if(!isset($page) || $page == ''){
		$page = base_url();
	}else{
		if(defined('SEO_URL') && SEO_URL){
			if(is_array($page)){
				$page = array_filter(stripslashes_deep($page));
				$page = implode('/', $page);
				$page = base_url() . '/' . $page;
			}else{
				$page = stripslashes($page);
				$page = base_url() . '/' . $page;
			}
		}else{
			if(is_array($page)){
				$page = array_filter(stripslashes_deep($page));
				$page = implode('/', $page);
				$page = base_url() . '/index.php?url=' . $page;
			}else{
				$page = stripslashes($page);
				$page = base_url() . '/index.php?url=' . $page;
			}
		}
	}

	$link = '<a href="' . $page . '">' . $text . '</a>';
	return $link;
}

/**
 * Simple email link
 * 
 * @param $value for the link text
 * @param $valid email address
 */

function create_mailto($text, $email)
{
	$link = '<a href="mailto:' . $email . '">' . $text . '</a>';
	return $link;
}

/**
*Make all those plain URL and email texts clickable by automatically converting hem to hyperlink.
* @param $value of a string that contains plain URL's
* @usage $my_string = strip_tags('this http://www.cdcv.com/php_tutorial/strip_tags.php make clickable text and this email bobby23432@fakserver.com');
* echo autolink($my_string);
**/

function autolink($message) 
{
    //Convert all urls to links
    $message = preg_replace('#([s|^])(www)#i', '$1http://$2', $message);
    $pattern = '#((http|https|ftp|telnet|news|gopher|file|wais)://[^s]+)#i';
    $replacement = '<a href="$1" target="_blank">$1</a>';
    $message = preg_replace($pattern, $replacement, $message);

    /* Convert all E-mail matches to appropriate HTML links */
    $pattern = '#([0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*.';
    $pattern .= '[a-wyz][a-z](fo|g|l|m|mes|o|op|pa|ro|seum|t|u|v|z)?)#i';
    $replacement = '<a href="mailto:1">1</a>';
    $message = preg_replace($pattern, $replacement, $message);
    return $message;
}

/**
* Another piece of useful PHP function to output the URL of current Page.
* @uages echo curPageURL();
**/

function curPageURL() 
{
	$pageURL = 'http';
	if (!empty($_SERVER['HTTPS'])) {$pageURL .= "s";}
		$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}

	return $pageURL;
}

/**
* Keep the URL's from getting unwated slashes 
**/

function stripslashes_deep($value)
{
    $value = is_array($value) ?
             array_map('stripslashes_deep', $value) :
             stripslashes($value);

    return $value;
}

/**
* Get url segment
* @param numerical value only. 
* @usage 1 - returns class
*        2 - returns action
*        3 - returns params
*        4 - returns params
*        5 - returns "    " etc ...
**/

function getSegment($segment = false)
{
	if($segment && is_numeric($segment)){
		if(defined('SEO_URL') && SEO_URL){
			$parsed     = parse_url(curPageURL());
			$path       = $parsed['path'];
			$path_parts = explode('/', $path);
		} else {
			if(isset($_GET['url']) && $_GET['url'] != '/'){
				$path       = $_GET['url'];
				$path_parts = explode('/', $path);
			} else {
				return false;
			}
		}

		$path_parts = array_filter($path_parts);

		if(!empty($path_parts) && array_key_exists($segment, $path_parts)){
			return $path_parts[$segment];
		}else{
			return false;
		}
	} else {
		return false;
	}
}