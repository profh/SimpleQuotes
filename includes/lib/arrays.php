<?php

class Arrays {      // ARRAYS CLASS

/*	A more detailed set of notes exists for each method within the method itself,
	but this list gives a good overview of what is in this set of methods.  All 
	methods here are static (for obvious reason).
		
	LIST OF FUNCTIONS INCLUDED
	--------------------------
	printArray($array,$border)				-- commonly used for diagnostics on basic arrays;
											   if border is NULL, default border set to 1
											   											   
	getPostVars($pattern)					-- puts selected $_POST vars into array; 
										       if pattern is NULL, gets all $_POST vars
										       
	getGetVars($pattern)					-- puts selected $_GET vars into array; 
										       if pattern is NULL, gets all $_GET vars
										       
	makeSessionVars($array)					-- put array items into session vars
	
	
	getSessionVars($pattern) 				-- puts selected $_SESSION vars into array; 
										   	   if pattern is NULL, gets all $_SESSION vars 
	
	clearEmptyValues($array)				-- removes any empty values from assoc array
	
	
	addedToArray($old_array, $new_array)	-- determines which values were added to new array
	
	
	removedFromArray($old_array, $new_array) -- determines which values were removed from new array
*/


// -----------------------
// FUNCTION: PRINT ARRAY

public static function printArray() {

	$args = func_get_args();	
	switch (count($args)) {

		case 1:  
				$array = $args[0];
				$border = 1;
				break;
		case 2:  
				$array = $args[0];
				$border = $args[1];
				break;
    }
   
   // Check that the array exists
   if (!$array) {
        echo "No array specified.<BR><BR>";
        return;
   }
   
   else {
        // Start table
        echo "<TABLE BORDER=$border>";
   
        // Print the array in table cells
        while (list($key, $value) = each ($array))  {
            echo "<TR><TD>$key</TD><TD>$value</TD></TR>"; 
        }  // end of while loop
   
        // End table and return
        echo "</TABLE>";
        return;
   }  // end of else

} // End of printArray()


// -----------------------
// FUNCTION: GET POST VARS

public static function getPostVars() {
   
	$args = func_get_args();
	switch (count($args)) {
		case 0:  
			$pattern = "^[[:alnum:][:space:][:punct:]]+$";  // just about anything ok
			break;
		case 1:  
			$pattern = $args[0];
			break;
    }
   
   if (!$_POST) { return FALSE; }     // The POST array is empty so return false
      
   foreach ($_POST as $key => $value) {
   
		 if (preg_match("/^$pattern$/", "$key") && $value != -1 && $key != "Submit") {
   
        // Trim data just to be safe
        $key = trim($key);
        $value = trim($value);
        
        // Strip slashes if magic quotes in effect
        if (get_magic_quotes_gpc()) {
            $key = stripslashes($key);
            $value = stripslashes($value);
        }
        // enter into the post vars to be returned
        $postvars["$key"] = $value;
   
      } // End of if regex
   } // End of foreach loop
   
   if (!$postvars) { return FALSE; }
   else { return $postvars; }

} // End of getPostVars()


// -----------------------
// FUNCTION: GET GET VARS

public static function getGetVars() {

/* This function loops through the $_GET
   array and extracts all variables that 
   match the pattern passed.  All values 
   are cleaned using trim and strip slashes
   if magic_quotes are on.   */
   
   $args = func_get_args();
	
	switch (count($args)) {

		case 0:  
				$pattern = "^[[:alnum:][:space:][:punct:]]+$";  // just about anything ok
				break;

		case 1:  
				$pattern = $args[0];
				break;
    }
   
   if (!$_GET) {
        // echo "The GET Array is empty<BR>";
        return FALSE;
   }
      
   foreach ($_GET as $key => $value) {
   
     if (preg_match("/$pattern/", "$key") && $value != "" && $key != "Submit") {
   
        // Trim data
        $key = trim($key);
        $value = trim($value);
        
        // Strip slashes if magic quotes in effect
        if (get_magic_quotes_gpc()) {
            $key = stripslashes($key);
            $value = stripslashes($value);
        }

        // enter into the post vars to be returned
        
        $getvars["$key"] = $value;
   
      } // End of if loop
   } // End of foreach loop
   
   if (!$getvars) { return FALSE; }
   else { return $getvars; }

} // End of getGetVars()


// -------------------------
// FUNCTION: MAKE SESSION VARS

public static function makeSessionVars($array) {

/* This function takes an associative array 
   and generates a series of session variables  */
   
   if (!is_array($array)) { return FALSE; }   // valid array not passed
   
   foreach ($array as $key => $value) {
   
      $_SESSION["$key"] = $value;

   } // End of foreach loop
   
   return;

} // End of makeSessionVars()


// ----------------------------------
// FUNCTION: GET SESSION VARS

public static function getSessionVars() {

/* This function loops through the $_SESSION
   array and extracts all variables that 
   match the pattern passed.  After cleaning
   the data (should be clean -- just a 
   precaution) the values are all placed
   into an associative array.  */
   
   $args = func_get_args();
	
	switch (count($args)) {

		case 0:  
				$pattern = "^[[:alnum:][:space:][:punct:]]+$";  // just about anything ok
				break;

		case 1:  
				$pattern = $args[0];
				break;
    }
   
   if (!$_SESSION) {
        // echo "The SESSION Array is empty<BR>";
        return FALSE;
   }
      
   foreach ($_SESSION as $key => $value) {
   
     //  if it fits the pattern and is relevant
     if (preg_match("/$pattern/", "$key") && $value != -1 && $key != "Submit") {
     	
     	//  if the value is not an array or object, try to trim and clear slashes...
     	if (!is_array($value) && !is_object($value)) {
     		
     		// Trim data
        	$key = trim($key);
        	$value = trim($value);
        
        	// Strip slashes if magic quotes in effect
        	if (get_magic_quotes_gpc()) {
            	$key = stripslashes($key);
        	    $value = stripslashes($value);
        	}
        	
     	}  // end if not array or object
     
     	//  enter the key and value into the sessionvars array to be returned
     	$sessionvars["$key"] = $value;
     
     }  // end if fit pattern
   
   } // End of foreach loop
   
   if (!$sessionvars) { return FALSE; }  // no array was generated
   else { return $sessionvars; }

} // End of getSessionVars()


// ----------------------------------
// FUNCTION: CLEAR EMPTY VALUES

public static function clearEmptyValues($array) {

/*	This function unsets any empty values in an associate array
	and returns the array back.  This is useful, perhaps, if we 
	expect some $_POST values are unset and we need to clear 
	those empty values out of the array.  */

   foreach ($array as $key => $value) {
    	if ($value == "") { unset($array[$key]); }
    }
    
    return $array;
   
}	// End of clearEmptyValues()


/*	The following two functions are little more than the array_diff 
	function applied, but I often get array_diff messed up, so these
	functions help me keep it straight.  There is no function for 
	array_intersect, b/c I rarely make mistakes with that one.   */

// ----------------------------------
// FUNCTION: ADDED TO ARRAY

public static function addedToArray($old_array, $new_array) {

/*	This function returns those values that exist in the 
	new array, but not in the old one.  Those values have 
	essentially been "added" to the old array.   */

   $added = array_diff($new_array, $old_array);
   return $added;

}  // End of addedToArray()



// ----------------------------------
// FUNCTION: REMOVED FROM ARRAY

public static function removedFromArray($old_array, $new_array) {

/*	This function returns those values that exist in the old 
	array, but are no longer found in the new one.  Those 
	values have essentially been "removed" to the old array.   */

   $removed = array_diff($old_array, $new_array);
   return $removed;
   
}	// End of removedFromArray()



// ----------------------------------
// FUNCTION: CLEAR POST ARRAY

public static function clearPostArray() {

   foreach($_POST as $key => $value) { unset($_POST[$key]); }
   return TRUE;
   
}	// End of clearPostArray()


// ----------------------------------
// FUNCTION: CLEAR GET ARRAY

public static function clearGetArray() {

   foreach($_GET as $key => $value) { unset($_GET[$key]); }
   return TRUE;
   
}	// End of clearGetArray()


// ----------------------------------
// FUNCTION: CLEAR SESSION ARRAY

public static function clearSessionArray() {

   foreach($_SESSION as $key => $value) { unset($_SESSION[$key]); }
   return TRUE;
   
}	// End of clearSessionArray()

}  //  END OF ARRAYS CLASS

?>