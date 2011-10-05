<?php

/*  This class is designed to make the generation of
	various types of check boxes lists easier.  They 
	all have "memory" inasmuch as they draw from 
	(1) SESSION and (2) POST for previous inputs.
	
	Note: if the checkboxes are all one name and values
	put into an array, this class can't be used.
	
	All functions are static and can be called 
	directly when needed.                         */


class CheckBox {      // CHECK BOX CLASS


// -----------------------
// FUNCTION: CREATE FROM ARRAY

/* This function creates a set of check boxes from a hash array.  
   This function automatically checks the session and post arrays
   to see if there is anything already set that needs to be checked.  */

public static function createFromArray($array)  {

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

   
} // End of createFromArray()



}  //  END OF CHECK BOX CLASS

?>