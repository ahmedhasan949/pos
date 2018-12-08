<?php   
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	require_once( APPPATH . 'controllers/__base.php');

/*
 * ---------------------------------------------------------------------------------------
 * File: doc.php
 * Type: Controller
 * Created On: Aug 13, 15 
 * Created by: Muhammad Rizwan
 * Description: For API Documentation
 * ---------------------------------------------------------------------------------------
 */

class Doc extends __Base
{
	function __construct()
   	{
		parent::__construct();
   	}
	
	function index()
	{ 
		$this->load->view('api/index');
	}
}