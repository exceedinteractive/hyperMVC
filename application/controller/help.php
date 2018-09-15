<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Help extends Controller
{

    public function __construct() {
		parent::__construct();
	}
	
	public function index() 
	{
	
		/** CSS**/
		$this->view->setCSS('include/css/hello_hyper.css');

		/** JS **/
		$this->view->setJS('include/js/script.js');

		/** PAGE TITLE **/
		$this->view->setSiteTitle( "Hyper | Welcome!" );

		/** META KEYWORDS **/
		$this->view->setMetaKeywords("Hyper framework, Hyper");

		/** META DESCRIPTION **/
		$this->view->setMetaDescription("Hello, welcome to Hyper PHP framework. Hyper is both lightweight and mighty.");
		
		/** HEADER **/
		$header = new View();
		$header->assign('logo', '<img src="' . base_url() . '/include/images/logo.png" alt="hyper framework logo" />');
		$header->assign('app_title', "Hyper - help!");
		$this->assign('header', $header->render('header', FALSE));
		
		/** FOOTER **/
		$footer = new View();
		$this->assign('footer', $footer->render('footer', FALSE)); 
		
		/** CONTENT **/
		$this->assign('content', ""); 

		/** LOAD TEMPLATE **/
		$this->loadView('readme');
		
	}

}