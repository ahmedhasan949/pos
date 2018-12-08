<?php
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	require_once( APPPATH . 'controllers/__base.php');
/*
 * ---------------------------------------------------------------------------------------
 * File: client_doc.php
 * Type: Controller
 * Created On: Oct 14, 2013 
 * Created by: Muhammad Kashif
 * Description: For Swagger interface
 * ---------------------------------------------------------------------------------------
 */

class Swagger extends __Base
{
	function __construct()
   	{
		parent::__construct();
   	}
	function index()
	{
	   
		$this->load->view('/swagger/dist/index.html');
	}
}