<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Check for valid email
 * 
 * @param $value of the email address to check.
 */

function validate_email($email)
{
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		return true;
	}else{
		return false;
	}
}