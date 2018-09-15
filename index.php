<?php
/**
 * Hyper version 1.0.0
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2015 - 2016, Hyper Framework
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	  Hyper
 * @author	  Exceed Interactive Development Team
 * @copyright Copyright (c) 2015 - 2016, Exceed Interactive (http://exceedinteractive.com/)
 * @license	  http://opensource.org/licenses/MIT	MIT License
 * @link	  http://hyperframework.net
 * @since	  Version 1.0.0
 * @credits   
 */

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));

/** Set Hyper framework path **/

$lib_path = 'library';

if (($_temp = realpath($lib_path)) !== FALSE){
	$lib_path = $_temp . DS;
}else{
	$lib_path = rtrim($lib_path, '/') . DS;
}

/** Is the library path correct? **/

if (!is_dir($lib_path)){
	header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
	echo 'Error: Library missing for frame work. Check to be sure your installation is correct.';
	exit(3);
}

/** Path to the library folder **/

define('BASEPATH', str_replace('\\', '/', $lib_path)); 

/** get your settings **/

require_once(ROOT . DS . 'config'  . DS . 'config.php');

/** Get the requested page **/

if(SEO_URL !== false && !isset($_GET['url'])){
	$url = $_SERVER['REQUEST_URI'];
}else{
	$url = isset($_GET['url']) ? $_GET['url'] : null;
}

/** off we go!! **/

require_once(ROOT . DS . 'library' . DS . 'router.php');