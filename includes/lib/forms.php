<?php

// FORMS CLASS
class Forms {      

    private $form_name; 
    private $method; 
    private $action; 
    private $ext_array;
    private $enctypeOn;
    private $enctypeValue;
    private $enctypeArray;

	function __construct() {
	    $this->form_name = "";
	    $this->method = "POST";       
	    $this->action = $_SERVER['PHP_SELF'];
	    $this->ext_array = array(".php");      // i.e., only php file ok by default 
	    $this->enctypeOn = "off";
	    $this->enctypeValue = "multipart/form-data";
	    $this->enctypeArray = array("multipart/form-data");   // allowable enctypes 

	}   // End of Constructor

	public function setFormName($string) { $this->form_name = $string; }
	public function setMethod($string) { $this->method = $string; }
	public function setAction($string) { $this->action = $string; }
	public function setExtensionsArray($array) { $this->ext_array = $array; }
	public function setEnctypeOn($string) { $this->enctypeOn = $string; }
	public function setEnctypeValue($string) { $this->enctypeValue = $string; }
	public function setEnctypeArray($array) { $this->enctypeArray = $array; }

	// -----------------------------------
	// FUNCTION: IS VALID METHOD

	private function isValidMethod() { 

	    $method = $this->method; 
	    if (!$method || $method == "") { return FALSE; } 
		else {  
	        $meth = strtolower($method); 
	        if ($meth=="post" || $meth=="get") { return TRUE; } 
			else { return FALSE; }  
	    } 
	} // End of isValidMethod()


// -----------------------------------
// FUNCTION: IS VALID EXTENSION

private function isValidExtension() { 

    $action = $this->action;
    $file_ext = strrchr($file_name,"."); 
    $ext_array = $this->ext_array; 
    $ext_count = count($ext_array); 

    if (!$action || $action == "") { return FALSE; } 
	else { 
        if (!$ext_array) { return TRUE; } 
		else { 
            foreach ($ext_array as $value) {
                $first_char = substr($value,0,1); 
                    if ($first_char <> ".") { $extensions[] = ".".strtolower($value); } 
					else { $extensions[] = strtolower($value); } 
            } 

		$valid_extension = false; // initially assume it is false...
            foreach ($extensions as $value) { 
                if ($value == $file_ext) { $valid_extension = true; }                 
            } 
            if ($valid_extension) { return TRUE; } 
			else { return FALSE; } 
        } 
    } 
    
} // End of isValidExtension()


// -----------------------------------
// FUNCTION: IS VALID ENCTYPE

private function isValidEnctype() { 

/* We begin by getting the action file's extension by finding 
   the last instance of the . and getting the characters that 
   follow (can't take last 3 characters because some 
   extensions have four chars, e.g., .html)  */


    $enctypeValue = $this->enctypeValue; 
    $enctypeArray = $this->enctypeArray; 
    $ext_count = count($ext_array); 


    // Now a few checks before we try to match the enctype

    if (!$enctypeValue || $enctypeValue == "") { // be sure there is a value...

	  $errno = 4;

	  //$this->print_errors($errno);

        return FALSE; 

    } else { 

        if (!$enctypeArray) { // if none specified, any type is acceptable

            return TRUE; 

        } else { 

		/* just in case enctypes in array are not formatted right, we'll 
		   correct any potential problem here by making all lower case */

            foreach ($enctypeArray as $value) { $types[] = strtolower($value); } 
            
            } 

    // Now onto the business of matching the enctypes with values in the array

		$valid_type = false; // initially assume it is false...

            foreach ($types as $value) { 

                if ($value == $enctypeValue) { 

                    $valid_type = true; // set to true if a match...
                }                 
            } 

            if ($valid_type) { 

                return TRUE;   // return true if any matches...

            } else { 

		    $errno = 4;
		    //$this->print_errors($errno);
		    return FALSE; 
            } 
        } 

} // End of isValidEnctype()


	// ------------------------------------
	// FUNCTION: START FORM

	public function startForm() {
    
		$name = $this->form_name;
		$method = $this->method;
		$action = $this->action;
		$enctype = $this->enctypeValue;

		// Test the method, action and enctype to be sure it is valid

		    $validMeth = $this->isValidMethod();  // make sure method is POST or GET
    
		    // if $PHP_SELF, no extension test on action file
		    if ($action == $_SERVER['PHP_SELF']) { $validExt = TRUE; }  
		    // make sure action file is valid type (e.g., '.php')
		 	else { $validExt = $this->isValidExtension(); }    
 	
		 	if ($this->enctypeOn == "off") { $validEnc = TRUE; }  // no check needed
		 	else {$validEnc = $this->isValidEnctype(); }   // if on, check if valid
 	
		 	if (!$validMeth || !$validExt || !$validEnc) { return FALSE; }

		//  Create the HTML form tag
		if($this->enctypeOn == "off") { 
			echo "<FORM name=\"$name\" method=\"$method\" action=\"$action\">"; 
		}
		elseif($this->enctypeOn == "on") { 
			echo "<FORM name=\"$name\" method=\"$method\" action=\"$action\" enctype=\"$enctype\">"; 
		}
		else { return FALSE; }

	}  //  end of startForm()


// ------------------------------------
// FUNCTION: END FORM

	public function endForm() {

		$args = func_get_args();
		switch (count($args)) {

			case 0:  
				$var = "none"; 
				break;
			case 1:  
				$var = $args[0]; 
				break;
		    }
    
		if ($var == "none") {
			echo "</FORM>"; 
		}
		else {
			echo "<INPUT name=\"Submit\" type=\"Submit\" value=\"$var\">"; 
			echo "</FORM>";
		}
		return;
		
	}  //  end of endForm()


/*  =====================================
    METHOD SET 3:  FORM ELEMENT FUNCTIONS	
    =====================================  

    A collection of PHP functions to generate various types of form elements, including:
    
    General Control Options...
    ------------------------------
    createTextBox($boxname, $size, $type)       -- see below for types (type=="", no validation)
    createTextArea($areabox, $cols, $rows)
    
    createMenuFromArray($menuname, $array, $atuo)  -- creates a pull-down menu from any 2D hash
    createRadioFromArray($buttonset, $array)    -- creates a set of radio buttons from any 2D hash
    createCheckBoxesFromArray($array)           -- creates a group of check boxes from any 2D hash

    *createMenuFromDB($menuname, $query, $auto)  -- creates a pull-down menu from db data
    *createRadioFromDB($buttonset, $query)       -- creates a set of radio buttons from db data
    *createCheckBoxesFromDB($query)              -- creates a group of check boxes from db data
    

    Specialty Control Options...
    ------------------------------
    createStateMenu($menuname)
    
    createMonthMenu($menuname, $settocurrent)   -- "curr" sets to current
    createDayMenu($menuname, $month, $year)
    createYearMenuPast($menuname, $start, $length, $settocurrent)
    createYearMenuFuture($menuname, $start, $length, $settocurrent)
    
    createSexChoice($buttonset)                 -- radio button set for gender
    createYesNo($buttonset)                     -- generic yes|no radio button set
        
    For the most part, these controls have memory; that is, if the user has previously 
    used the control to make a valid choice, the choice will be remembered.  As far as
    memory goes, textboxes are validated for any POST data (assume that SESSION data has 
    been validated prior to added to the SESSION array) based on type/pattern passed.  
    You will find more details on each function can be found in the notes below.
    =====================================================================================  */


// FUNCTION: CREATE TEXT BOX

/* This function is called to create a text box form element
   and to fill this value with any previous input, if that 
   input exists.  It will also check for valid data input if 
   either a type is past (see below) or a regex pattern.  
   
   Preset types include...
   --------------------------------------------------
   name             ssn     (social security number)
   address          ccn     (credit card number)
   city             number  (any number, w/ or w/o ,)
   zip              money   (dollars w or w/o ,)
   phone            age     (positive three digit number)
   
   Additionally, if $type is just a regex pattern, the
   function will use that pattern for validation.  If 
   $type == "" then no validation test is run           */

public static function createTextBox($boxname, $size, $type) {
    
    // Find if a previous, valid value exists in $_POST or in $_SESSION
    if (!isset($_SESSION["$boxname"]) || $_SESSION["$boxname"] == "") { // nothing is $_SESSION, check $_POST for valid data
        if (!isset($_POST["$boxname"]) || $_POST["$boxname"] == "") { $previousvalue = "";}
    
        else {  // If something is in $_POST, run validation check
        
           // use type to find the right pattern
           switch ($type)  {

		      case $type == "name":   

				    $pattern = "^[A-Za-z][A-Za-z.,' -]*$";
				    break;
                
              case $type == "address":  

				    $pattern = "^[0-9A-Za-z#][0-9A-Za-z.,#&' -]+$";
				    break;
                
              case $type == "city":     

				    $pattern = "^[A-Za-z][A-Za-z.&' -]+$";
				    break;
                
              case $type == "zip":   

				    $pattern = "^[0-9]{5}(-[0-9]{4})?$";
				    break;
              
              case $type == "e-mail":    

				    $pattern = "^[A-Za-z0-9_%][A-Za-z0-9._%+-]*@[A-Za-z0-9_%][A-Za-z0-9._%-]+\.[A-Za-z]{2,4}$";
				    break;
              
              case $type == "age":    

				    $pattern = "^[0-9]{1,3}$";
                    break;
              
              case $type == "ssn":    

				    $pattern = "^([0-9]{9}|[0-9]{3}[-][0-9]{2}[-][0-9]{4})$";
				    break;
              
              case $type == "ccn":   

				    $pattern ="^([0-9]{16}|[0-9]{4}[ -][0-9]{4}[ -][0-9]{4}[ -][0-9]{4})$";
				    break;
                    
              case $type == "number":   

				    $pattern = "^(\-?[0-9]+(.[0-9]+)?|\-?[0-9]{1,3}(,[0-9]{3})*)(.[0-9]+)?$";
				    break;
                    
              case $type == "posinteger":   

				    $pattern = "^[0-9]+$";
				    break;
			  
			  case $type == "money":  

				    $pattern = "^([0-9]+|[0-9]{1,3}(,[0-9]{3})*)(.[0-9]{1,2})?$";
				    break;
                    
              case $type == "phone":  

				    $pattern = "^([0-9]{10}|([0-9]{3}\-[0-9]{3}\-[0-9]{4})|(\([0-9]{3}\))[ ]?[0-9]{3}\-[0-9]{4}))$";
				    break;
              
              default:   

				    $pattern = $type;  // type is actually a pattern (regex)
                
           } // end of switch case
           
           if ($pattern != "") {  // run a validation test
                $theresults = preg_match("/$pattern/", $_POST["$boxname"]);
                if ($theresults) { $previousvalue = $_POST["$boxname"]; }
                else { $previousvalue = ""; }
            }
            else { $previousvalue = $_POST["$boxname"]; } // no validation test run
           
        } // end of else (no session data, some post data)
        
    } // end of if no session data
    
    else { $previousvalue = $_SESSION["$boxname"]; }  // use session data to fill the box
    
    // Create text box now...
    
    echo "<INPUT NAME=\"$boxname\" TYPE=\"text\" VALUE=\"$previousvalue\" SIZE=\"$size\">";
 
    return;
    
} // End of createTextBox()


// -----------------------
// FUNCTION: CREATE TEXT AREA

/* This function is called to create a text area box form 
   element and to fill this value with any previous input, if 
   that input exists.  Typically used for comments or 
   notes that go unvalidated. */

public static function createTextArea($areabox, $cols, $rows) {

    // Find if a previous, valid value exists in $_POST or in $_SESSION
    
    if (!isset($_SESSION["$areabox"]) || $_SESSION["$areabox"]=="")  {  // no session data, check post array
        if (!isset($_POST["$areabox"]) || $_POST["$areabox"]=="") { $previousvalue = "";} // if nothing, print nothing
        else { $previousvalue = $_POST["$areabox"]; } // if something, then print it
    }
    else { $previousvalue = $_SESSION["$areabox"]; }
    
    // Create text area box now...
    
    echo "<TEXTAREA NAME=\"$areabox\" COLS=\"$cols\" ROWS=\"$rows\">$previousvalue</TEXTAREA>";
 
    return;

}  // End of createTextArea()


// -----------------------
// FUNCTION: CREATE MENU FROM ARRAY

/* This function creates a pull-down menu a hash array.  
   This can be used to generate quick pull-down menus,
   but there are some specialty menus below that are 
   commonly used -- using those specialty menus should 
   be even faster for common tasks.  
   
   If $atuo == 1, then the menu will auto-submit on change.   */

public static function createMenuFromArray($menuname, $array, $auto)  {

   // Check to see if a choice has been made previously or is a session variable
   if (!isset($_SESSION["$menuname"]) || $_SESSION["$menuname"] == "") { // no session var, see if in $_POST
        if (isset($_POST["$menuname"]) && $_POST["$menuname"] != -9999) { $choice = $_POST["$menuname"]; }

        else { $choice=0; }
   }
   else { $choice = $_SESSION["$menuname"]; }
 
   
   // Start the menu 
   if ($auto == 1) {echo "<SELECT name=\"$menuname\" size=1 onchange=\"this.form.submit()\">";}
   else { echo "<SELECT name=\"$menuname\" size=1>"; }
   
   if ($choice == "0") { // No choice made -- user prompted to select
           echo "<OPTION value=\"-9999\" selected>Please select...</OPTION>";
           while (list($key, $value) = each ($array))  {
                echo "<OPTION value=\"$key\">$value</OPTION>"; 
           }  // end of while loop
    }
    else {  // prior choice made -- select that value
            while (list($key, $value) = each ($array))  {
                if ($key == $choice) { echo "<OPTION value=\"$key\" selected>$value</OPTION>"; }
                else { echo "<OPTION value=\"$key\">$value</OPTION>"; }
           } // end of while loop    
    }
    
    echo "</SELECT>";
             
    return;
   
} // End of createMenuFromArray()


// -----------------------
// FUNCTION: CREATE RADIO FROM ARRAY

/* This function creates a set of radio buttons from a hash array.  
   This can be used to generate quick radio buttons, but there are 
   some specialty sets below that are commonly used -- using those 
   specialty sets should be even faster for common tasks.  */

public static function createRadioFromArray($buttonset, $array)  {
 
   // Check to see if a choice has been made previously or is a session variable
   if (!isset($_SESSION["$buttonset"]) || $_SESSION["$buttonset"] == "") { // no session var, see if in $_POST
        if (isset($_POST["$buttonset"]) && $_POST["$buttonset"] != -9999) { $choice = $_POST["$buttonset"]; }

        else { $choice=0; }
   }
   else { $choice = $_SESSION["$buttonset"]; }
 
   
   // Create the radio button set... 
   if ($choice == "0") { // No choice made -- user prompted to select
   
           while (list($key, $value) = each ($array))  {
                echo "<INPUT type=\"radio\" name=\"$buttonset\" value=\"$key\"> $value<BR>"; 
           }  // end of while loop
    }
    else {  // prior choice made -- select that value
            while (list($key, $value) = each ($array))  {
                if ($key == $choice) { echo "<INPUT type=\"radio\" name=\"$buttonset\" value=\"$key\" checked> $value<BR>"; }
                else { echo "<INPUT type=\"radio\" name=\"$buttonset\" value=\"$key\"> $value<BR>"; }
           } // end of while loop
    
    }
             
    return;
   
} // End of createRadioFromArray()


// -----------------------
// FUNCTION: CREATE CHECK BOXES FROM ARRAY

/* This function creates a set of radio buttons from a hash array.  
   This can be used to generate quick radio buttons, but there are 
   some specialty sets below that are commonly used -- using those 
   specialty sets should be even faster for common tasks.  */

public static function createCheckBoxesFromArray($array)  {

    while (list($key, $value) = each ($array))  {
   
     if (!isset($_SESSION["$key"]) || $_SESSION["$key"] == "")  { // no session data, check post and create boxes
        if (isset($_POST["$key"])) { echo "<INPUT type=\"checkbox\" name=\"$key\" value=\"$key\" checked> $value<BR>"; }
        else { echo "<INPUT type=\"checkbox\" name=\"$key\" value=\"$key\"> $value<BR>"; }
     }
     else {
        if (isset($_SESSION["$key"])) { echo "<INPUT type=\"checkbox\" name=\"$key\" value=\"$key\" checked> $value<BR>"; }
        else { echo "<INPUT type=\"checkbox\" name=\"$key\" value=\"$key\"> $value<BR>"; }
     }
        
        
   }  // end of while loop
             
    return;
   
} // End of createCheckBoxesFromArray()


// -----------------------
// FUNCTION: CREATE MENU FROM DB

/* This function creates a pull-down menu from a basic 
   query to a db.  Query should only return two fields:
   the key (likely the ID field) and the value.
   
   If $auto == 1, the form will auto-submit on change.     */

public static function createMenuFromDB($menuname, $query, $auto)  {
 
   // Check to see if a choice has been made previously or is a session variable
   if (!isset($_SESSION["$menuname"]) || $_SESSION["$menuname"] == "") { // no session var, see if in $_POST
        if (isset($_POST["$menuname"]) && $_POST["$menuname"] != -9999) { $choice = $_POST["$menuname"]; }

        else { $choice=0; }
   }
   else { $choice = $_SESSION["$menuname"]; }  // choice is whatever value is in $_SESSION
 
   // Set up a link to the database and execute the query

		
		$db_frm = new DB();
		$db_frm->Open();
		$Result = $db_frm->getRecords($query);
   
   // Start the menu 
   if ($auto == 1) {echo "<SELECT name=\"$menuname\" size=1 onchange=\"this.form.submit()\">";}
   else { echo "<SELECT name=\"$menuname\" size=1>"; }
   
   if ($choice == "0") { // No choice made -- user prompted to select
           echo "<OPTION value=\"-9999\" selected>Please select...</OPTION>";
           while ($Query_results = mysql_fetch_row($Result))  {
               $key = $Query_results[0];
               $value = $Query_results[1];
    		   echo "<OPTION value=\"$key\">$value</OPTION>";
		   }  // end of while loop
    }
    else {  // prior choice made -- select that value
            while ($Query_results = mysql_fetch_row($Result))  {
                $key = $Query_results[0];
                $value = $Query_results[1];
            
                if ($key == $choice) { echo "<OPTION value=\"$key\" selected>$value</OPTION>"; }
                else { echo "<OPTION value=\"$key\">$value</OPTION>"; }
           } // end of while loop
    }
    
    echo "</SELECT>";
    $db_frm->Close();         
    return TRUE;
   
} // End of createMenuFromDB()


// -----------------------
// FUNCTION: CREATE RADIO FROM DB

/* This function creates a set of radio buttons from
   a basic query.  Similar to the DB pull-down menu
   function above.  */

public static function createRadioFromDB($buttonset, $query)  {
 
    // Check to see if a choice has been made previously or is a session variable
        if (!isset($_SESSION["$buttonset"]) || $_SESSION["$buttonset"] == "") { // no session var, see if in $_POST
            if (isset($_POST["$buttonset"]) && $_POST["$buttonset"] != -9999) { $choice = $_POST["$buttonset"]; }

            else { $choice=0; }
        }
        else { $choice = $_SESSION["$buttonset"]; }    

   // Set up a link to the database and execute the query

		$db_frm = new DB();
		$db_frm->Open();
		$Result = $db_frm->getRecords($query);
 
   
   // Create the radio button set... 
   if ($choice == "0") { // No choice made -- user prompted to select
   
           while ($Query_results = mysql_fetch_row($Result))  {
                $key = $Query_results[0];
                $value = $Query_results[1];
                echo "<INPUT type=\"radio\" name=\"$buttonset\" value=\"$key\"> $value<BR>"; 
           }  // end of while loop
    }
    else {  // prior choice made -- select that value
            while ($Query_results = mysql_fetch_row($Result))  {
                $key = $Query_results[0];
                $value = $Query_results[1];
                if ($key == $choice) { echo "<INPUT type=\"radio\" name=\"$buttonset\" value=\"$key\" checked> $value<BR>"; }
                else { echo "<INPUT type=\"radio\" name=\"$buttonset\" value=\"$key\"> $value<BR>"; }
           } // end of while loop
    
    }
    
    $db_frm->Close();         
    return TRUE;
   
} // End of createRadioFromDB()


// -----------------------
// FUNCTION: CREATE CHECK BOXES FROM DB

/* This function creates a set of check boxes from a hash array.  */

public static function createCheckBoxesFromDB($query)  {

   // Set up a link to the database and execute the query

		$db_frm = new DB();
		$db_frm->Open();
		$Result = $db_frm->getRecords($query);

   // Create the checkboxes...
   
   while ($Query_results = mysql_fetch_row($Result))  {
        $key = $Query_results[0];
        $value = $Query_results[1];
                        
     if (!isset($_SESSION["$key"]) || $_SESSION["$key"] == "")  { // no session data, check post and create boxes
        if (isset($_POST["$key"])) { echo "<INPUT type=\"checkbox\" name=\"$key\" value=\"$key\" checked> $value<BR>"; }
        else { echo "<INPUT type=\"checkbox\" name=\"$key\" value=\"$key\"> $value<BR>"; }
     }
     else {
        if (isset($_SESSION["$key"])) { echo "<INPUT type=\"checkbox\" name=\"$key\" value=\"$key\" checked> $value<BR>"; }
        else { echo "<INPUT type=\"checkbox\" name=\"$key\" value=\"$key\"> $value<BR>"; }
     }
        
   }  // end of while loop
   
    $db_frm->Close();         
    return TRUE;
   
} // End of createCheckBoxesFromDB()


// -----------------------
// FUNCTION: CREATE STATE MENU

/* This function creates a pull-down menu of the 50 states 
   plus DC.  If the user already selected a state and that 
   information is in either $_POST or $_SESSION, it will be 
   pre-selected if the menu is ever reloaded.    */

public static function createStateMenu($menuname)  {
   
  // Check for choice data in $_SESSION and $_POST
  if (!isset($_SESSION["$menuname"]) || $_SESSION["$menuname"] == "") { // no session var, see if in $_POST
        if (isset($_POST["$menuname"]) && $_POST["$menuname"] != -9999) { $choice = $_POST["$menuname"]; }

        else { $choice=0; }
   }
   else { $choice = $_SESSION["$menuname"]; }  // use session data
 
 $states = array ("AL" => "Alabama", "AK" => "Alaska", "AZ" => "Arizona", 
 				"AR" => "Arkansas", "CA" => "California", "CO" => "Colorado", 
 				"CT" => "Connecticut", "DE" => "Delaware", 
 				"DC" => "District of Columbia", "FL" => "Florida", 
 				"GA" => "Georgia", "HI" => "Hawaii", "ID" => "Idaho", 
 				"IL" => "Illinois", "IN" => "Indiana", "IA" => "Iowa", 
 				"KS" => "Kansas", "KY" => "Kentucky", "LA" => "Louisiana", 
				"ME" => "Maine", "MD" => "Maryland", "MA" => "Massachusetts", 
				"MI" => "Michigan", "MN" => "Minnesota", "MS" => "Mississippi",
				"MO" => "Missouri", "MT" => "Montana", "NE" => "Nebraska", 
                "NV" => "Nevada", "NH" => "New Hampshire", "NJ" => "New Jersey",
                "NM" => "New Mexico", "NY" => "New York", 
                "NC" => "North Carolina", "ND" => "North Dakota", 
                "OH" => "Ohio", "OK" => "Oklahoma", "OR" => "Oregon", 
                "PA" => "Pennsylvania", "RI" => "Rhode Island", 
                "SC" => "South Carolina", "SD" => "South Dakota", 
                "TN" => "Tennessee", "TX" => "Texas", "UT" => "Utah", 
                "VT" => "Vermont", "VA" => "Virginia", "WA" => "Washington", 
                "WV" => "West Virginia", "WI" => "Wisconsin", 
                "WY" => "Wyoming");
   
   echo "<SELECT name=\"$menuname\" size=1>";
   
   if ($choice == "0") { // No choice made -- user prompted to select
           echo "<OPTION value=\"-9999\" selected>Please select...</OPTION>";
           while (list($value, $statename) = each ($states))  {
                echo "<OPTION value=\"$value\">$statename</OPTION>"; 
           }  // end of while loop
    }
    else {  // prior choice made -- select that value
            while (list($value, $statename) = each ($states))  {
                if ($value == $choice) { echo "<OPTION value=\"$value\" selected>$statename</OPTION>"; }
                else { echo "<OPTION value=\"$value\">$statename</OPTION>"; }
           } // end of while loop
    
    }
    
    echo "</SELECT>";
             
    return;
   
} // End of createStateMenu()


// -----------------------
// FUNCTION: CREATE MONTH MENU

/* This function creates a typical month pull-down menu.  If there is no previous
   choice made by the user and it is not set to the current month (sometimes we 
   want it to default to current to make life easier) then it will start with the 
   typical 'please select...' msg.  **If settocurrent (last argument) is set to 'curr'
   then it will default to the current month.**  If the user had previously chosen 
   a value, then that will be the selected month.  (To do this, use in conjunction
   with the getChoice fn.)   If $auto == 1, the form will auto-submit.  This can be 
   used to repopulate the day menu after a month is selected. */

public static function createMonthMenu($menuname, $settocurrent, $auto) {

// Step 1: Set Value and Month Arrays
		$Month = array("January", "Feburary", "March", "April", "May", 
						"June", "July", "August", "September", "October", 
						"November", "December");
		$Value = array("1", "2", "3", "4", "5", "6", "7", "8", "9", 
						"10", "11", "12");
		
		
// Step 2: Check if a choice has been made and if set to current option is selected
	
    // Check for choice data in $_SESSION and $_POST
    if (!isset($_SESSION["$menuname"]) || $_SESSION["$menuname"] == "") { // no session var, see if in $_POST
        if (isset($_POST["$menuname"]) && $_POST["$menuname"] != -9999) { $choice = $_POST["$menuname"]; }

        else { $choice=0; }
    }
    else { $choice = $_SESSION["$menuname"]; }  // use session data
	
	//  if no choice, then combo box with current month selected or 'please select...'
		if ($choice == 0 || !$choice) {
			
			if ($auto == 1) {echo "<SELECT name=\"$menuname\" size=1 onchange=\"this.form.submit()\">";}
			else { echo "<SELECT name=\"$menuname\" size=1>"; }
			
			if (!$settocurrent || $settocurrent != "curr") {  // Don't default to current month
			     echo "<OPTION value=\"-9999\" selected>Please select...</OPTION>";
			}
			
			for ($i = 0; $i < 12 ; $i++)  {
			     $curmonth = date("m");
			
                if ($Value[$i]==$curmonth && $settocurrent == "curr") {
                   echo "<option value=$Value[$i] selected>$Month[$i]</option>";
                }
                
                else { echo "<option value=$Value[$i]>$Month[$i]</option>"; }
			}   // end of for-i loop
			
			echo "</SELECT>";
		}  // end of if no choice
	

// Step 3: If choice made, then create combo with memory
	else {  
		// start combo box
					
			if ($auto == 1) {echo "<SELECT name=\"$menuname\" size=1 onchange=\"this.form.submit()\">";}
			else { echo "<SELECT name=\"$menuname\" size=1>"; }
			
			for ($i = 0; $i < 12 ; $i++)  {
			
                if ($Value[$i]==$choice) {
                   echo "<option value=$Value[$i] selected>$Month[$i]</option>";
                }
                
                else { echo "<option value=$Value[$i]>$Month[$i]</option>"; }
			}  // end of for-i loop
			
			echo "</SELECT>";
			
		} //  end of else
		
	return;

} // End of createMonthMenu()


// -----------------------
// FUNCTION: CREATE YEAR MENU PAST

/* This function creates a typical year pull-down menu.  If there is no previous
   choice made by the user and it is not set to the current year (sometimes we 
   want it to default to current to make life easier) then it will start with the 
   typical 'please select...' msg.  **If settocurrent (last argument) is set to 'curr'
   then it will default to the current year.**  Also, if no start value is set, it
   will default to the current year.  If the user had previously chosen a 
   value, then that will be the selected year.  (To do this, use in conjunction
   with the getChoice fn.)  This menu moves backwards -- in some cases, the user is
   recording the past, but can't pick a future date (or year) so this is useful. */

public static function createYearMenuPast($menuname, $start, $length, $settocurrent, $auto) {

// Step 1: Set up first and last values for year options
    if (!$start || $start == 0) { $valueyear = date("Y") + 1; } // use current year if none given
    else { $valueyear = $start + 1;}
    
    if (!$length || $length == 0) { $length = 5; } // use length of 5 if none given
		
		
// Step 2: Check if a choice has been made
	
    // Check for choice data in $_SESSION and $_POST
    if (!isset($_SESSION["$menuname"]) || $_SESSION["$menuname"] == "") { // no session var, see if in $_POST
        if (isset($_POST["$menuname"]) && $_POST["$menuname"] != -9999) { $choice = $_POST["$menuname"]; }

        else { $choice=0; }
    }
    else { $choice = $_SESSION["$menuname"]; }  // use session data
	
	//  if no choice, then combo box with current year at top or 'please select...'
		if ($choice == 0 || !$choice) {
			
			if ($auto == 1) {echo "<SELECT name=\"$menuname\" size=1 onchange=\"this.form.submit()\">";}
			else { echo "<SELECT name=\"$menuname\" size=1>"; }
			
			if (!$settocurrent || $settocurrent != "curr") {  // Don't set to current year by default
			     echo "<OPTION value=\"-9999\" selected>Please select...</OPTION>";
			}
			
			for ($i = 0; $i < $length ; $i++)  {
			
			     $valueyear = $valueyear - 1;
			     $curyear = date("Y");
			
			if ($valueyear==$curyear && $settocurrent == "curr") {echo "<option value=$valueyear selected>$valueyear</option>";}
                
                else { echo "<option value=$valueyear>$valueyear</option>"; }

			}   // end of for-i loop
			
			echo "</SELECT>";
		}  // end of if no choice
	

// Step 3: If choice made, then create combo with memory
	else {  
		// start combo box
					
			if ($auto == 1) {echo "<SELECT name=\"$menuname\" size=1 onchange=\"this.form.submit()\">";}
			else { echo "<SELECT name=\"$menuname\" size=1>"; }
			
			for ($i = 0; $i < $length ; $i++)  {
			    $valueyear = $valueyear - 1;
			
                if ($valueyear==$choice) {echo "<option value=$valueyear selected>$valueyear</option>";}
                
                else {echo "<option value=$valueyear>$valueyear</option>";}
			}  // end of for-i loop
			
			
			echo "</SELECT>";
			
		} //  end of else
		
	return;

} // End of createYearMenuPast()


// -----------------------
// FUNCTION: CREATE YEAR MENU FUTURE

/* This function creates a typical year pull-down menu.  If there is no previous
   choice made by the user and it is not set to the current year (sometimes we 
   want it to default to current to make life easier) then it will start with the 
   typical 'please select...' msg.  **If settocurrent (last argument) is set to 'curr'
   then it will default to the current year.**  Also, if no start value is set, it
   will default to the current year.  If the user had previously chosen a 
   value, then that will be the selected year.  (To do this, use in conjunction
   with the getChoice fn.)  This menu moves forwards -- in some cases, the user is
   entering expiration dates, but can't pick a past date (or year) so this is useful. */

public static function createYearMenuFuture($menuname, $start, $length, $settocurrent, $auto) {

// Step 1: Set up first and last values for year options
    if (!$start || $start == 0) { $valueyear = date("Y") - 1; } // use current year if none given
    else { $valueyear = $start - 1;}
    
    if (!$length || $length == 0) { $length = 5; } // use length of 5 if none given
		
		
// Step 2: Check if a choice has been made
	
    // Check for choice data in $_SESSION and $_POST
    if (!isset($_SESSION["$menuname"]) || $_SESSION["$menuname"] == "") { // no session var, see if in $_POST
        if (isset($_POST["$menuname"]) && $_POST["$menuname"] != -9999) { $choice = $_POST["$menuname"]; }

        else { $choice=0; }
    }
    else { $choice = $_SESSION["$menuname"]; }  // use session data
	
	//  if no choice, then combo box with current year at top or 'please select...'
		if ($choice == 0 || !$choice) {
			
			if ($auto == 1) {echo "<SELECT name=\"$menuname\" size=1 onchange=\"this.form.submit()\">";}
			else { echo "<SELECT name=\"$menuname\" size=1>"; }
			
			if (!$settocurrent || $settocurrent != "curr") {  // Don't set to current year by default
			     echo "<OPTION value=\"-9999\" selected>Please select...</OPTION>";
			}
			
			for ($i = 0; $i < $length ; $i++)  {
			
			     $valueyear = $valueyear + 1;
			     $curyear = date("Y");
			
			if ($valueyear==$curyear && $settocurrent == "curr") {echo "<option value=$valueyear selected>$valueyear</option>";}
                
                else { echo "<option value=$valueyear>$valueyear</option>"; }

			}   // end of for-i loop
			
			echo "</SELECT>";
		}  // end of if no choice
	

// Step 3: If choice made, then create combo with memory
	else {  
		// start combo box
					
			if ($auto == 1) {echo "<SELECT name=\"$menuname\" size=1 onchange=\"this.form.submit()\">";}
			else { echo "<SELECT name=\"$menuname\" size=1>"; }
			
			for ($i = 0; $i < $length ; $i++)  {
			    $valueyear = $valueyear + 1;
			
                if ($valueyear==$choice) {echo "<option value=$valueyear selected>$valueyear</option>";}
                
                else {echo "<option value=$valueyear>$valueyear</option>";}
			}  // end of for-i loop
			
			echo "</SELECT>";
		} //  end of else
		
	return;

} // End of createYearMenuFuture()


// -----------------------
// FUNCTION: CREATE DAY MENU

/* This function creates a pull-down menu of days for a month.  If none are specified
   (achieved by setting $month to -1), it creates a menu of 31 days. 
   
   If $month set to 0, then it will use days in current month.   */

public static function createDayMenu($menuname, $month, $year, $settocurrent, $auto)  {

   // Step 1: Finding the last day
   if (!$year || $year == 0) { $year = date("Y"); } // set year to current year if none specified
   if (!$month || $month == -1) { $last = 31; } // use 31 days if no month given
   elseif($month == 0) { $last = date("t"); } // use days in current month if set to -1
   elseif($month == 4 || $month == 6 || $month == 9 || $month == 11) { $last = 30; }
   elseif($month == 2) { // dealing with Feb
            if (date("L", mktime(0, 0, 0, 2, 1, $year)) == 1) {$last = 29; } // if year is a leap year
            else { $last = 28; }
        }
   else { $last = 31; }  // It's Jan, March, May, July, Aug, Oct, Dec
   
   $curday = date("j");  // setting today's day
   
   
   // Step 2: Check if a choice has been made
	
    // Check for choice data in $_SESSION and $_POST
    if (!isset($_SESSION["$menuname"]) || $_SESSION["$menuname"] == "") { // no session var, see if in $_POST
        if (isset($_POST["$menuname"]) && $_POST["$menuname"] != -9999) { $choice = $_POST["$menuname"]; }

        else { $choice=0; }
    }
    else { $choice = $_SESSION["$menuname"]; }  // use session data

	
	//  if no choice, then combo box with current year at top or 'please select...'
		if ($choice == 0 || !$choice) {
			
			if ($auto == 1) {echo "<SELECT name=\"$menuname\" size=1 onchange=\"this.form.submit()\">";}
			else { echo "<SELECT name=\"$menuname\" size=1>"; }
			
			if (!$settocurrent || $settocurrent != "curr") {  // Don't set to current day by default
			     echo "<OPTION value=\"-9999\" selected>Please select...</OPTION>";
			}
			
			for ($d = 1 ; $d <= $last ; $d++) {
   	
			    if ($d == $curday && $settocurrent == "curr") {echo "<option value=$d selected>$d</option>";}
                
                else { echo "<option value=$d>$d</option>"; }

			}   // end of for-d loop
			
			echo "</SELECT>";
		}  // end of if no choice
	

    // Step 3: If choice made, then create combo with memory
	   else {  
		  // start combo box
					
			if ($auto == 1) {echo "<SELECT name=\"$menuname\" size=1 onchange=\"this.form.submit()\">";}
			else { echo "<SELECT name=\"$menuname\" size=1>"; }
			for ($d = 1 ; $d <= $last ; $d++)  {
			    
			    if ($d==$choice) {echo "<option value=$d selected>$d</option>";}
                
                else {echo "<option value=$d>$d</option>";}
			}  // end of for-d loop
			
			echo "</SELECT>";
			
		} //  end of else

         
   return;
   
} // End of createDayMenu()


// -----------------------
// FUNCTION: CREATE SEX CHOICE

/* This function creates a set of radio buttons for choice of male or female.  
   Can retain memory of previous choices in either $_POST or $_SESSION     */

public static function createSexChoice($buttonset)  {
   
    // Check to see if a choice has been made previously or is a session variable
        if (!isset($_SESSION["$buttonset"]) || $_SESSION["$buttonset"] == "") { // no session var, see if in $_POST
            if (isset($_POST["$buttonset"]) && $_POST["$buttonset"] != -9999) { $choice = $_POST["$buttonset"]; }

            else { $choice=0; }
        }
        else { $choice = $_SESSION["$buttonset"]; } 
   
   
    // Now create the gender buttonset   
        if ($choice == "0") {  // no prior choice
            echo "<INPUT type=\"radio\" name=\"$buttonset\" value=\"M\"> Male<BR>
                  <INPUT type=\"radio\" name=\"$buttonset\" value=\"F\"> Female";
        }
        else {
            if ($choice == "M") {      // prior choice was male
                echo "<INPUT type=\"radio\" name=\"$buttonset\" value=\"M\" checked> Male<BR>
                      <INPUT type=\"radio\" name=\"$buttonset\" value=\"F\"> Female";
            }
            else {                     // prior choice was female
                echo "<INPUT type=\"radio\" name=\"$buttonset\" value=\"M\"> Male<BR>
                      <INPUT type=\"radio\" name=\"$buttonset\" value=\"F\" checked> Female";
            }
        }
                  
   return;
   
} // End of createSexChoice()


// -----------------------
// FUNCTION: CREATE YES||NO CHOICE

/* This function creates a set of radio buttons for choice of yes or no.  
   Can retain memory of previous choices in either $_POST or $_SESSION     */

public static function createYesNo($buttonset)  {
   
    // Check to see if a choice has been made previously or is a session variable
        if (!isset($_SESSION["$buttonset"]) || $_SESSION["$buttonset"] == "") { // no session var, see if in $_POST
            if (isset($_POST["$buttonset"]) && $_POST["$buttonset"] != -9999) { $choice = $_POST["$buttonset"]; }

            else { $choice=0; }
        }
        else { $choice = $_SESSION["$buttonset"]; } 
   
   
    // Now create the yes|no buttonset   
        if ($choice == "0") {  // no prior choice
            echo "<INPUT type=\"radio\" name=\"$buttonset\" value=\"Y\"> Yes<BR>
                  <INPUT type=\"radio\" name=\"$buttonset\" value=\"N\"> No";
        }
        else {
            if ($choice == "Y") {      // prior choice was male
                echo "<INPUT type=\"radio\" name=\"$buttonset\" value=\"Y\" checked> Yes<BR>
                      <INPUT type=\"radio\" name=\"$buttonset\" value=\"N\"> No";
            }
            else {                     // prior choice was female
                echo "<INPUT type=\"radio\" name=\"$buttonset\" value=\"Y\"> Yes<BR>
                      <INPUT type=\"radio\" name=\"$buttonset\" value=\"N\" checked> No";
            }
        }
                  
   return;
   
} // End of createYesNo()


}  // END OF FORMS CLASS

?>