<?php

/**
 *	The page controller class has some generic functions that 
 *	I usually want available on any given page just to handle
 *	processing.
 *	
 *	An instance of this class ($page) is created in common.php 
 *	so if I want to access a function, I can simply say:
 *	   $page->function();
 *	
 */


class Page {      

    protected $data;
    protected $auth_level;
    protected $table;
    protected $user_id_field;
    protected $pswd_field;
    protected $default_title;
    protected $default_pagecode;
    

/*  ======================================
    METHOD SET 0:  CONSTRUCTOR 
    ======================================   */

	function __construct() {

		$this->data = array();
		$auth_level = 0;
		$table = "";
		$user_id_field = "";
		$pswd_field = "";
		$default_title = "";
		$default_pagecode = "home";
	
	}   #  End of Constructor


	/*  ============================================
	    METHOD SET 1:  SET & GET FUNCTIONS
	    ============================================   */

	public function setUserTable($string) { $this->table = $string; }
    
	public function setUserIDField($string) { $this->user_id_field = $string; }
    
	public function setPswdField($string) { $this->pswd_field = $string; }

	public function setDefaultTitle($string) { $this->default_title = $string; }

	public function setDefaultPagecode($string) { $this->default_pagecode = $string; }

	public function getDataArray() { return $this->data; }


	/*  =====================================
	    METHOD SET 2:  GENERAL FUNCTIONS	
	    =====================================    */

	#  ------------------------------------
	#  FUNCTION: GET DATA

	public function getData() { 

		if (isset($_POST) && count($_POST) > 0) {  #  data passed via $_POST, add to data array

			$temp = Arrays::getPostVars();
			$arr = Arrays::clearEmptyValues($temp);
			foreach ($arr as $key => $value) {
				$d_key = trim($key);
				$d_val = trim($value);
				$data[$d_key] = $d_val;
			}
			$this->data = $data;
			return $data;
		}
	
		elseif (isset($_GET) && count($_GET) > 0) {  #  data passed via $_GET, add to data array
		
			$temp = Arrays::getGetVars();
			$arr = Arrays::clearEmptyValues($temp);
			foreach ($arr as $key => $value) {
				$d_key = trim($key);
				$d_val = trim($value);
				$data[$d_key] = $d_val;
			}
			$this->data = $data;
			return $data;
		}
	
		else {  #  nothing to get, so return false
		
			$this->data = array();
			return FALSE;
		}

	}  #  end of getData()



	#  ------------------------------------
	#  FUNCTION: PROCESS LOGIN

	public function processLogin($uid, $pswd) { 

		$login = new Login(); 
	
		# 	set observers
		$login->attach( new SessionSetter() );
		#  $login->attach( new GeneralLogger() );
		#  $login->attach( new SecurityMonitor() );
		#  $login->attach( new CookieCutter() );
	
		@$testing = $login->checkLogin($uid, $pswd);
		
			if ($testing) { return TRUE; }
		
			else {  return FALSE; }
	
	}  #  end of processLogin()



	#  ------------------------------------
	#  FUNCTION: START PAGE

	public function startPage() { 

		$args = func_get_args();
	
		switch (count($args)) {

			case 0:  
					$pageTitle = $this->default_title;
					$pageCode = $this->default_pagecode;
					break;

			case 1:  
					$pageTitle = $args[0];
					$pageCode = $this->default_pagecode;
					break;

			case 2:  
					$pageTitle = $args[0];
					$pageCode = $args[1];
					break;
	    }
    
	    if (isset($_GET["view"]) && $_GET["view"] == "pf") { include ("includes/header_pf.php"); }
    
	    else { include ("includes/header.php"); }
	
	}  #  end of startPage()


	#  ------------------------------------
	#  FUNCTION: END PAGE

	public function endPage() { 

		# echo "<P>&nbsp;</P>";
	
		if (isset($_GET["view"]) && $_GET["view"] == "pf") { include ("includes/footer_pf.php"); }
    
	    else { include ("includes/footer.php"); }
	
	}  #  end of endPage()


	#  ------------------------------------
	#  FUNCTION: START CONTENT

	public function startContent(){
	
		$args = func_get_args();
	
		switch (count($args)) {

			case 0:  
					$id = 0;
					break;

			case 1:  
					$id = $args[0];
					break;
	    }

		echo "\t<div class=\"content$id\">\n";
	}


	#  ------------------------------------
	#  FUNCTION: END SECTION

	public function endSection() {
		echo "\t</div>\n";
	}


	#  ------------------------------------
	#  FUNCTION: THROW ERROR

	public function throwError($msg) { 

		$this->startPage("System Error");
		echo "<span class=\"error\">$msg</span>";
		$this->endPage();
		exit();	
	}  #  end of throwError()

} #   END OF PAGE CLASS


?>
