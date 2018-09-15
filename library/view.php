<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Handles the view functionality of our MVC framework
 */
class View 
{
    /**
     * Holds variables assigned to template
     */

    private $data = array();

    /**
     * Holds render status of view.
     */

    private $render = FALSE;

    /**
    * Holds cache object
    **/

    private $cache;

    public function __construct() 
    {
	
		$this->data['site_title']       = '';
		$this->data['site_icon']        = '';
		$this->data['meta_description'] = '';
		$this->data['meta_keywords']    = '';
	
		$this->data['css'] = '';
		$this->data['js']  = '';
		
		$this->data['header']  = '';
		$this->data['content'] = '';
		$this->data['footer']  = '';

		if(defined('CACHE_FILE') && CACHE_FILE === TRUE){

			require_once(ROOT . DS . 'library' . DS . 'cache.php');

			$this->cache = new Cache();
		}
		
    }

    /**
	* Receives assignments from controller and stores in local data array
	* 
	* @param $variable
	* @param $value
	**/
    
    /**
	* Receives assignments from controller and stores in local data array
	* 
	* @param $variable
	* @param $value
	**/

    public function assign($variable = '', $value) 
    {
		if ($variable == '')
			$this->data = $value;
		else
			$this->data[$variable] = $value;
    }

    /**
	* Render the output directly to the page, or optionally, return the
	* generated output to caller.
	* 
	* @param $variable Set which particular data to render to page
	* @param $direct_output Set to any non-TRUE value to have the 
	* output returned rather than displayed directly.
	**/
    
    public function render($view, $direct_output = TRUE)
    {
		
		// check if we are going to return a cache file
		if(defined('CACHE_FILE') && CACHE_FILE === TRUE && $direct_output == TRUE){

			$this->cache->setCache(strtolower($view));

			if($this->cache->isCached(strtolower($view))){

				$cache_file = $this->cache->retrieve(strtolower($view));
				
				echo $cache_file . '<!-- read from cache -->';
				return;
			}

		}
		
		// check if $view is an absolute path
		if(substr($view, -4) == ".php"){
		
			$file = $view;
		
		} else {

			//compose file name
			$file = ROOT . DS . APP . DS . 'view' . DS . strtolower($view) . '.php';
		
		}

        if (file_exists($file)){
		
            /**
             * trigger render to include file when this model is destroyed
             * if we render it now, we wouldn't be able to assign variables
             * to the view!
             */
            $this->render = $file;
			
        }else{
			
			return "view file doesn't exist.";
		
		}

        // Turn output buffering on, capturing all output
        if ($direct_output !== TRUE){
            ob_start();
        }

        // Parse data variables into local variables
		$data = $this->data;

        // Get template
        include($this->render);
		
        // Get the contents of the buffer and return it
        if ($direct_output !== TRUE){

            return ob_get_clean();

        } else {

        	if(defined('CACHE_FILE') && CACHE_FILE === TRUE){
        		
        		ob_start();

		        // Parse data variables into local variables
				$data = $this->data;

		        // Get template
		        include($this->render);

	        	// cache the buffer for later next time this page is called
	        	$buffer = ob_get_clean();
	        	
	        	$this->cache->setCache(strtolower($view));
	        	$this->cache->store(strtolower($view), $buffer, CACHE_EXPIRATION);

            } 
        }
    }

	public function setSiteTitle($name)
	{
		$this->data['site_title'] = '<title>' . $name . '</title>' . PHP_EOL;
	}
	
	public function setSiteIcon($filename)
	{
		if (file_exists(SITE_ROOT . DS . $filename)){
		
		$this->data['site_icon'] = '<link rel="shortcut icon" type="image/vnd.microsoft.icon" href="' . SITE_ROOT . DS . $filename . '">' . PHP_EOL;
		
		}
	}
	
	public function setMetaKeywords($words)
	{
		$this->data['meta_keywords'] = '<meta name="keywords" content="' . $words . '">' . PHP_EOL;
	}
	
	public function setMetaDescription($descr)
	{
		$this->data['meta_description'] = '<meta name="description" content="' . $descr . '">' . PHP_EOL;
	}	
	
	public function setCSS($filename)
	{
		if (file_exists(ROOT . DS . $filename)){
			
		$this->data['css'] = $this->data['css'] . '<link rel="stylesheet" type="text/css" href="' . SITE_ROOT . '/' . str_replace('\\', '/', $filename) . '">' . PHP_EOL;
		
		}
	}
	
	public function setJS($filename)
	{
		$this->data['js'] = $this->data['js'] . '<SCRIPT LANGUAGE="JavaScript" SRC="' . $filename . '"></SCRIPT>' . PHP_EOL;
	}

    public function __destruct() {
	
    }
}