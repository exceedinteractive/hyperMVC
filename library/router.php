<?php
defined('BASEPATH') OR exit('No direct script access allowed');

 /** Check if environment is development and display errors **/
function setReporting() 
{
	if (DEVELOPMENT_ENVIRONMENT == true){
		error_reporting(E_ALL);
		ini_set('display_errors','On');
	} else {
		error_reporting(E_ALL);
		ini_set('display_errors','Off');
		ini_set('log_errors', 'On');
		ini_set('error_log', ROOT . DS . 'tmp' . DS . 'logs' . DS . 'error.log' );
	}
}

/** Check for Magic Quotes and remove them **/
function stripSlashesDeep($value) 
{
	$value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
	return $value;
}

function removeMagicQuotes() 
{
	if ( get_magic_quotes_gpc() ){
		$_GET    = stripSlashesDeep($_GET   );
		$_POST   = stripSlashesDeep($_POST  );
		$_COOKIE = stripSlashesDeep($_COOKIE);
	}
}

/** Check register globals and remove them **/
function unregisterGlobals() 
{
    if (ini_get('register_globals')) {
        $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
        foreach ($array as $value) {
            foreach ($GLOBALS[$value] as $key => $var) {
                if ($var === $GLOBALS[$key]) {
                    unset($GLOBALS[$key]);
                }
            }
        }
    }
}
 
//Automatically includes files containing classes that are called
function __autoload($className) 
{	
    //fetch file
    if (file_exists( ROOT  . DS . APP . DS . 'controller'   . DS . strtolower($className) . '.php') ) {
        require_once( ROOT . DS . APP . DS .  'controller'  . DS . strtolower($className) . '.php');        
    }else if (file_exists( ROOT . DS . APP . DS . 'model'   . DS . strtolower($className) . '.php') ) {
        require_once( ROOT . DS . APP . DS . 'model'        . DS . strtolower($className) . '.php');        
    }else if (file_exists( ROOT . DS . 'library' . DS . strtolower($className) . '.php') ) {
        require_once( ROOT . DS . 'library' . DS . strtolower($className) . '.php');       
    }else{
		// Error: Controller Class not found
		die("Error: Class not found.");
	}
	
}
 
/** Main Call Function **/
function callHook() 
{
	global $url;

	if (!isset($url) || $url == '/'){

		$controllerName = DEFAULT_CONTROLLER;
		$action         = DEFAULT_ACTION;
		$params         = array();

	}else{

		if(defined('SEO_URL') && SEO_URL){

		    $arrUrl = explode('/',$url);
		    $arrUrl = array_filter($arrUrl);

		    if(!empty($arrUrl))
		    {
		        $controllerName = array_shift($arrUrl);
		        $action         = (isset($arrUrl[0]) && $arrUrl[0] != '') ? array_shift($arrUrl) : DEFAULT_ACTION;
		        $params         = $arrUrl;
		    }

		}else{
		
			$arrUrl         = array();
			$arrUrl         = explode("/",$url);
			$arrUrl         = array_filter($arrUrl);
		    $controllerName = array_shift($arrUrl);
		    $action         = (isset($arrUrl[0]) && $arrUrl[0] != '') ? array_shift($arrUrl) : DEFAULT_ACTION;	
			$params         = $arrUrl;
		}
	}

	$class  = strtolower($controllerName);

	//instantiate the appropriate class
	if (class_exists($class) && (int)method_exists($class, $action)) {

		//ToDo figure out what to do with these params...??

		$controller = new $class;
		$controller->$action($params);

	}else{
	
		// Error: Controller Class not found
		die("1. File <b>'$controllerName.php'</b> containing class <b>'$class'</b> might be missing. <br>2. Method <b>'$action'</b> is missing in <b>'$controllerName.php'</b>");
	}

}

setReporting();
removeMagicQuotes();
unregisterGlobals();
callHook();
