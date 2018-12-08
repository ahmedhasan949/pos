<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');	

/*
 * ---------------------------------------------------------------------------------------
 * File: __base.php
 * Created On: Apr 8, 13 
 * Created by: Muhammad Rizwan
 * Description: This base controller will be extended by all other controllers.
 * ---------------------------------------------------------------------------------------
 */
 
class __Base extends CI_Controller {

	public function __construct()
   	{
		parent::__construct();
		$this->load->library('Includes');
	    
		// TODOD - Load it if request is not from API
		// Since webapp is not part of this application, we can ignore it for now
		/*
			$this->load->library('auth');
			//Check if the cookies are created, then auto login using info in cookies
			auth::autologin_from_cookie();
		*/
   	}
   	
   	public function index() {
   	     
   	}
	
	public function base()
	{
		echo("Base Controller");
	}
	 
}
