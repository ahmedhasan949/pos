<?php

/*
 * ---------------------------------------------------------------------------------------
 * File: includes.php
 * Created On: Aug 5, 13
 * Created by: Muhammad Rizwan
 * Description: This library is loaded in almost every controller and is used to include
 * css / js files to views
 * ---------------------------------------------------------------------------------------
 */

class Includes{

	private $javascript = array();
	private $stylesheet = array();

	public function __construct()
	{

	}

	/*
	 * Function: use_javascript
	 * Params: javascript => js file name OR path to js file OR url of js file
	 *                       you can also pass array haing multiple js file names at once
	 * Written by: Muhammad Rizwan
	 * Date: Apr 3, 12
	 * Description: Stores JS file paths to a local variable which is later included in view by calling another method
	 */
	public function use_javascript($js)
	{

		$js_param = (!is_array($js)) ? array($js) : $js;
		foreach($js_param as $javascript)
		{

			if( stripos($javascript, "assets/jscript/") === false && stripos($javascript, "http://") === false )
			{
				$javascript = JS_PATH."/".$javascript;
			}

			if($javascript != '')
			{
				array_push($this->javascript,$javascript);
			}
		}
	}

	/*
	 * Function: use_stylesheet
	 * Params: stylesheet => css file name OR path to css file OR url of css file
	 * Written by: Muhammad Rizwan
	 * Date: Apr 3, 12
	 * Description: Stores CSS file paths to a local variable which is later included in view by calling another method
	 */
	public function use_stylesheet($css)
	{
		$css_param = (!is_array($css)) ? array($css) : $css;
		foreach($css_param as $stylesheet)
		{
			if( stripos($stylesheet, "assets/template/") === false && stripos($stylesheet, "http://") === false )
			{
				$stylesheet = TEMPLATE_PATH."/css/".$stylesheet;
			}

			$stylesheet.="?v".CSS_VERSION;

			if($stylesheet != '')
			{
				array_push($this->stylesheet,$stylesheet);
			}
		}
	}

	/*
	 * Function: include_javascripts
	 * Params: <none>
	 * Written by: Muhammad Rizwan
	 * Date: Apr 3, 12
	 * Description: Includes all the JS files stored in local variable
	 */
	public function include_javascripts()
	{
		foreach($this->javascript as $js)
		{
			echo "<script language=\"javascript\" src=\"$js\" type='text/javascript'></script>\n";
		}
	}

	/*
	 * Function: include_stylesheets
	 * Params: <none>
	 * Written by: Muhammad Rizwan
	 * Date: Apr 3, 12
	 * Description: Includes all the CSS files stored in local variable
	 */
	public function include_stylesheets()
	{
		foreach($this->stylesheet as $stylesheet)
		{
			echo "<link rel=\"stylesheet\" href=\"$stylesheet\" type=\"text/css\" media=\"screen\"/>\n";
		}
	}
}
