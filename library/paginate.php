<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Hyper Pagination Class
 **/

/**
* @usage //Build dataset with Hyper database class
*
* $sql = 'select * from table';
*
* $this->loadLibary('paginate');
*
* //pass in the number of items per page and the instance identifier, this is used for the GET parameter such as ?page=2
* $paginate->set_display('10', 'page');
*
* //set the total records, calling a method to get the number of records from a model
* $paginate->set_total($db->num_rows($sql));
* 
* //calling a method to get the records with the limit set
* $this->assign('records', $db->get_results($sql . $pagination->get_limit()));
*
* //create the nav menu
* $this->assign('page_links', $pagination->page_links());
**/

class Paginate{

    /**
	* set the number of items per page.
	*
	* @var numeric
	**/

	private $_perPage;

	/**
	* set get parameter for fetching the page number
	*
	* @var string
	**/

	private $_instance;

    /**
	* set the number of items per page.
	*
	* @var string
	**/

	private $_class;

	/**
	* sets the page number.
	*
	* @var numeric
	**/

	private $_page;

	/**
	* set the limit for the data source
	*
	* @var string
	**/

	private $_limit;

	/**
	* set the total number of records/items.
	*
	* @param numeric
	**/

	private $_totalRows = 0;

	//variable to hold instance
    public $paginate;

    //note we used static variable,beacuse an instance cannot be used to refer this
    public static $instance;

	/**
	*  __construct
	*  
	**/

	public function __construct(){}

    //to prevent loop hole in PHP so that the class cannot be cloned
    private function __clone(){}

    //used static function so that, this can be called from other classes
    public static function getInstance()
    {

        if(!(self::$instance instanceof self)){
             self::$instance = new self();           
        }
         return self::$instance;
    }

	/**
	* @param numeric  $_perPage  sets the number of iteems per page
	* @param numeric  $_instance sets the instance for the GET parameter
	**/

	public function set_display($perPage, $instance, $custom_class = '')
	{
		$this->_instance = $instance;		
		$this->_perPage  = $perPage;
		$this->_class    = $custom_class;
		$this->set_instance();		
	}

	/**
	* get_start
	*
	* creates the starting point for limiting the dataset
	* @return numeric
	**/

	public function get_start()
	{
		return ($this->_page * $this->_perPage) - $this->_perPage;
	}

	/**
	* set_instance
	* 
	* sets the instance parameter, if numeric value is 0 then set to 1
	*
	* @param numeric
	**/

	private function set_instance()
	{
		global $url;
		
		$this->_page = 0;

		if (!isset($url) || $url == '/') {

			$this->_page = 1;

		} else {

			$arrUrl = explode('/',$url);
			foreach($arrUrl as $key => $value) {
				if(strtolower($value) == strtolower($this->_instance)) {
					$this->_page = $arrUrl[$key+1];
				}
			}			
		}

		//$this->_page = (int) (!isset($_GET[$this->_instance]) ? 1 : $_GET[$this->_instance]); 
		$this->_page = ($this->_page == 0 ? 1 : $this->_page);
	}

	/**
	* set_total
	*
	* collect a numberic value and assigns it to the totalRows
	*
	* @param numeric
	**/

	public function set_total($_totalRows)
	{
		$this->_totalRows = $_totalRows;
	}

	/**
	* get_limit
	*
	* returns the limit for the data source, calling the get_start method and passing in the number of items perp page
	* 
	* @return string
	**/

	public function get_limit()
	{
        return " LIMIT ".$this->get_start().",$this->_perPage";
    }

	/**
	* url_path
	*
	* return the path in proper Hyper format
	* 
	* @return string
	**/

    private function url_path()
    {
		global $url;

		if (!isset($url) || $url == '/'){

			$controllerName = DEFAULT_CONTROLLER;
			$action         = DEFAULT_ACTION;

			$path = '/' . $controllerName . '/' . $action;

		}else{

			$index = 0;

			$arrUrl = explode('/',$url);
			foreach($arrUrl as $key => $value) {
				if(strtolower($value) == strtolower($this->_instance)) {
					$index = $key;
				}
			}
			
			if($index != 0){
				unset($arrUrl[$index], $arrUrl[$index+1]);
			}
			$arrUrl = array_filter($arrUrl);
			$path   = implode('/', $arrUrl);
		}

		$path .= '/' . $this->_instance;

		if(defined('SEO_URL') && SEO_URL){
			$path = SITE_ROOT . '/' . $path . '/';
		}else{
			$path = SITE_ROOT . '/index.php?url=' . $path . '/';
		}

		return $path;
    }

    /**
    * page_links
    *
    * create the html links for navigating through the dataset
    * 
    * @param sting $path optionally set the path for the link
    * @param sting $ext optionally pass in extra parameters to the GET
    * @return string returns the html menu
    **/

	public function page_links($path='',$ext=null)
	{
		$path 	   = $this->url_path();
	    $adjacents = "2";
	    $prev      = $this->_page - 1;
	    $next 	   = $this->_page + 1;
	    $lastpage  = ceil($this->_totalRows/$this->_perPage);
	    $lpm1      = $lastpage - 1;

	    $pagination = "";
		if($lastpage > 1)
		{   
			if($this->_class != ''){
		    	$pagination .= "<ul class='pagination " . $this->_class . "'>";
		    }else{
		    	$pagination .= "<ul class='pagination'>";
		    }
		if ($this->_page > 1)
		    $pagination.= "<li><a href='".$path."$prev"."$ext'>Previous</a></li>";
		else
		    $pagination.= "<span class='disabled'>Previous</span>";   

		if ($lastpage < 7 + ($adjacents * 2))
		{   
		for ($counter = 1; $counter <= $lastpage; $counter++)
		{
		if ($counter == $this->_page)
		    $pagination.= "<li><span class='current'>$counter</span></li>";
		else
		    $pagination.= "<li><a href='".$path."$counter"."$ext'>$counter</a></li>";                   
		}
		}
		elseif($lastpage > 5 + ($adjacents * 2))
		{
		if($this->_page < 1 + ($adjacents * 2))       
		{
		for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
		{
		if ($counter == $this->_page)
		    $pagination.= "<li><span class='current'>$counter</span></li>";
		else
		    $pagination.= "<li><a href='".$path."$counter"."$ext'>$counter</a></li>";                   
		}
		    $pagination.= "...";
		    $pagination.= "<li><a href='".$path."$lpm1"."$ext'>$lpm1</a></li>";
		    $pagination.= "<li><a href='".$path."$lastpage"."$ext'>$lastpage</a></li>";       
		}
		elseif($lastpage - ($adjacents * 2) > $this->_page && $this->_page > ($adjacents * 2))
		{
		    $pagination.= "<li><a href='".$path."1"."$ext'>1</a></li>";
		    $pagination.= "<li><a href='".$path."2"."$ext'>2</a></li>";
		    $pagination.= "...";
		for ($counter = $this->_page - $adjacents; $counter <= $this->_page + $adjacents; $counter++)
		{
		if ($counter == $this->_page)
		    $pagination.= "<span class='current'>$counter</span>";
		else
		    $pagination.= "<li><a href='".$path."$counter"."$ext'>$counter</a></li>";                   
		}
		    $pagination.= "..";
		    $pagination.= "<li><a href='".$path."$lpm1"."$ext'>$lpm1</a></li>";
		    $pagination.= "<li><a href='".$path."$lastpage"."$ext'>$lastpage</a></li>";       
		}
		else
		{
		    $pagination.= "<li><a href='".$path."1"."$ext'>1</a></li>";
		    $pagination.= "<li><a href='".$path."2"."$ext'>2</a></li>";
		    $pagination.= "..";
		for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
		{
		if ($counter == $this->_page)
		    $pagination.= "<span class='current'>$counter</span>";
		else
		    $pagination.= "<li><a href='".$path."$counter"."$ext'>$counter</a></li>";                   
		}
		}
		}

		if ($this->_page < $counter - 1)
		    $pagination.= "<li><a href='".$path."$next"."$ext'>Next</a></li>";
		else
		    $pagination.= "<li><span class='disabled'>Next</span></li>";
		    $pagination.= "</ul>\n";       
		}


	return $pagination;
	}
}

$this->paginate = Paginate::getInstance();