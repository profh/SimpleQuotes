<?php

/*  This class is designed to make the generation of
	various types of radio button lists easier.  They 
	all have "memory" inasmuch as they draw from 
	(1) SESSION and (2) POST for previous inputs.
	
	All functions are static and can be called 
	directly when needed.                         */


class RadioButton {      // RADIO BUTTON CLASS


// -----------------------
// FUNCTION: GET CHOICE

public static function getChoice($menuname) {

   // Check to see if a choice has been made previously or is a session variable
   if (!isset($_SESSION["$menuname"]) || $_SESSION["$menuname"] == "") { // no session var, see if in $_POST
        if (isset($_POST["$menuname"]) && $_POST["$menuname"] != -9999) { $choice = $_POST["$menuname"]; }

        else { $choice= -1; }  // nothing in $_POST or $_SESSION
   }
   else { $choice = $_SESSION["$menuname"]; }  // use session data to get choice
    
    return $choice;

}  // end of getChoice() 


// -----------------------
// FUNCTION: TEST AUTOSUBMIT

public static function testAutoSubmit($arg) {

   // Check to see if a argument possibly indicating auto-submitting
   if ($arg == 1 || $arg == "auto"  || $arg == "submit") 
	{ $auto = 1; }  // auto-submit
	
	else { $auto = 0; }
    
    return $auto;

}  // end of testAutoSubmit() 



// -----------------------
// FUNCTION: CREATE FROM ARRAY

/* This function creates a set of radio buttons from a hash array.  
   This can be used to generate quick radio buttons, but there are 
   some specialty sets below that are commonly used -- using those 
   specialty sets should be even faster for common tasks.  
   
   Auto-submit doesn't work yet, so only feed two arguments for now.  */

public static function createFromArray()  {

   $args = func_get_args();
	
	switch (count($args)) {

		case 2:  
				$buttonset = $args[0];
				$array = $args[1];
				$auto = 0;         // don't auto-submit
				break;

		case 3:  
				$buttonset = $args[0];
				$array = $args[1];
				$atuo = DropDownList::testAutoSubmit($args[2]);
				break;
    }
 
 	// Check to see if a choice has been made previously or is a session variable
   
   $choice = RadioButton::getChoice($buttonset);
   
   // Create the radio button set... 
   if ($choice == -1) { // No choice made -- user prompted to select
   
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
   
} // End of createFromArray()




}  //  END OF RADIO BUTTON CLASS

?>