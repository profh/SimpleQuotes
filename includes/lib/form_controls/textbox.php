<?php

/*  This abbreviated class is designed to make the 
	generation of various types of text boxes easier.  
	Text boxes all have "memory" inasmuch as they draw 
	from (1) SESSION and (2) POST for previous inputs.
	
	All functions are static and can be called 
	directly when needed.                         */


class TextBox {      // TEXT BOX CLASS


// -----------------------
// FUNCTION: GET PREVIOUS VALUE

	public static function getPreviousValue($boxname, $pattern) {

	// Find if a previous, valid value exists in $_POST or in $_SESSION
	    if (!isset($_SESSION["$boxname"]) || $_SESSION["$boxname"] == "") { 
			// nothing is $_SESSION, check $_POST for valid data
	        if (!isset($_POST["$boxname"]) || $_POST["$boxname"] == "") { $previousvalue = "";} 
    
	        else {	
				// something is in $_POST, use it to set previous value
	        	$theresults = preg_match("/^$pattern$/", $_POST["$boxname"]);
	            if ($theresults) { $previousvalue = $_POST["$boxname"]; }
	            else { $previousvalue = ""; }
           
	        } // end of else (no session data, some post data)
	    } // end of if no session data

	    else { $previousvalue = $_SESSION["$boxname"]; }  // use session data to fill the box
	    return $previousvalue;

	}  // end of getPreviousValue() 




	// -----------------------
	// FUNCTION: CREATE TEXT BOX

	public static function createTextBox() {

		$args = func_get_args();
		switch (count($args)) {
			case 2:  
					$boxname = $args[0];
					$size = $args[1];
					$pattern = "^[[:alnum:][:space:][:punct:]]+$";  // just about anything ok
					break;
			case 3:  
					$boxname = $args[0];
					$size = $args[1];
					$pattern = $args[2];
					break;
	    }

		$previousvalue = TextBox::getPreviousValue($boxname, $pattern);
	    echo "<INPUT NAME=\"$boxname\" TYPE=\"text\" VALUE=\"$previousvalue\" SIZE=\"$size\">";
	    return;
	
	}  // end of createTextBox()



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
    
    echo "<TEXTAREA NAME=\"$areabox\" COLS=\"$cols\" ROWS=\"$rows\">$previousvalue</TEXTAREA>";
 
    return;

}  // End of createTextArea()



}  //  END OF TEXT BOX CLASS

?>