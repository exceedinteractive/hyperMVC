<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Controller
{

	protected $view;
	protected $model;
	protected $view_name;

    public function __construct() 
    {

    	$this->view_name   = '';
		$this->view        = new View();

		/** Auto load helpers **/
		
		$this->autoloadHelper();

		/** Auto load librarys **/

		$this->autoloadLibrary();

    }

	public function index()
	{
		$this->assign('content', 'This is index class index method, Method is not set yet.');
	}
	
	public function assign($variable, $value)
	{
		$this->view->assign($variable, $value);
	}
	
	public function loadModel($name)
	{
		$modelName = $name;
		$this->model = new $modelName();
	}
	
	public function loadView($name)
	{
		if(file_exists( ROOT . DS . APP . DS . 'view' . DS . strtolower($name) . '.php')){
			$this->view_name = $name;
		}
	}

	public function loadLibrary($name)
	{
		$class = strtolower($name);

		if(file_exists(ROOT . DS . 'library' . DS . strtolower($class) . '.php')){
			require(ROOT . DS . 'library' . DS . strtolower($class) . '.php');
		}

	}

	public function autoloadLibrary()
	{
		if(defined('LOAD_LIBRARY') && LOAD_LIBRARY != ''){
			$haystack = LOAD_LIBRARY;
			$needle   = ",";

			$pos = strpos($haystack, $needle);

			if($pos === FALSE){
				$class = LOAD_LIBRARY;
				$this->loadLibrary($class);
			}else{
				$arrLibrary = explode(",", $haystack);
				$arrLibrary = array_filter($arrLibrary);
				foreach ($arrLibrary as $class) 
				{
					$this->loadLibrary($class);
				}
			}
		}
	}

	public function loadHelper($name)
	{
		$helper = strtolower($name);

		if(file_exists(ROOT . DS . 'helper' . DS . strtolower($helper) . '.php')){
			require(ROOT . DS . 'helper' . DS . strtolower($helper) . '.php');
		}

	}

	public function autoloadHelper()
	{
		if(defined('LOAD_HELPER') && LOAD_HELPER != ''){
			$haystack = LOAD_HELPER;
			$needle   = ",";

			$pos = strpos($haystack, $needle);

			if($pos === FALSE){
				$name = LOAD_HELPER;
				$this->loadHelper($name);
			}else{
				$arrHelper = explode(",", $haystack);
				$arrHelper = array_filter($arrHelper);
				foreach ($arrHelper as $name) 
				{
					$this->loadHelper($name);
				}
			}
		}
	}
	
	public function __destruct() 
	{
		if(!empty($this->view_name)){
			$this->view->render($this->view_name);
		}
	}
	
}

