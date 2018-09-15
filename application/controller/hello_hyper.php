<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hello_Hyper extends Controller
{

    public function __construct() 
    {
		parent::__construct();
	}
	
	public function index() 
	{
		/** LOAD TEMPLATE **/
		$this->loadView('index');
	
		/** CSS**/
		$this->view->setCSS("include/css/hello_hyper.css");

		/** JS **/
		$this->view->setJS("include/js/script.js");

		/** PAGE TITLE **/
		$this->view->setSiteTitle("Hello Hyper!");

		/** META KEYWORDS **/
		$this->view->setMetaKeywords("Hyper framework, Hyper");

		/** META DESCRIPTION **/
		$this->view->setMetaDescription("Hello, welcome to Hyper PHP framework. Hyper is both lightweight and mighty.");
		
		/** HEADER **/
		$header = new View();
		$header->assign('app_title', "Welcome to Hyper! Your new PHP framework.");
		$header->assign('logo', '<img src="' . base_url() . '/include/images/logo.png" alt="hyper framework logo" />');
		$this->assign('header', $header->render('header', FALSE));
		
		/** FOOTER **/
		$footer = new View();
		$this->assign('footer', $footer->render('footer', FALSE)); 
			
		/** CONTENT **/
		$this->assign('content', "<p>Hello,<br/><br/> If you are seeing this page for the first time it means your intallation was successful. Hope you enjoy hyper framework. Need help getting started, " . create_link('click here', 'help') . "."); 

		/** THANKS! **/
		$this->assign('thank_you', 'Thank You,<br/>Hyper Development Team');
		
		/** DATABASE AND PAGINATION **/
		// $sql = 'Select * FROM test';

		// $this->loadLibrary('paginate');

		// $this->paginate->set_display('2', 'page');

		// $this->paginate->set_total($this->db->num_rows($sql));

		// $this->assign('records', $this->db->get_results($sql . $this->paginate->get_limit()));

		// $this->assign('page_links', $this->paginate->page_links());
		
	}

}