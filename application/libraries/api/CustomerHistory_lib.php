<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Subject_lib
 *
 * @author: Muhammad Umar Hayat
 * @Description: users library to perform user functions.
 */
class CustomerHistory_lib
{
    /*
     * Property: 		CI
     * Description:             This will hold CI_Controller instance to perform all CI functionality
     * Type:     		Private
     */
    private $CI;
    const api_message_key = "AIzaSyDLwcHk93UVYSGtp-eDNi2RvnGDPoCDnNU";
   
    public function __construct()
    {
        $this->CI = &get_instance();
         $this->api_message_key = "AIzaSyDLwcHk93UVYSGtp-eDNi2RvnGDPoCDnNU";
        
    }

    /*
     * Mehtod:   		add_subject
     * Params: 			.....
     * Description:             Add Subject
     * Returns: 		.....
     */
     
    
    
     
    public function Get_Customer_history($request = array())  {
         
        $this->CI->load->model('Categories_model');
        $get_cat = $this->CI->Categories_model->get_categories(); 
        
        if (empty($get_cat)) {
           
            $response['result']['status'] = 'error';
            $response['result']['response'] = "Categories Are not Available";
            return array($response, 406);
        } 

        $response['result']['status'] = 'success';
        $response['result']['response'] = "Categories Successfully Recieved";
        $response['result']['data'] = $get_cat;
        return array($response, 200);
    } 
    
    
    
}
