<?php

class Validate {     
	
	/**
	 * 	This class has a bunch of static methods which have the 
	 *	regex I use for validating input.  One thing to note is 
	 *	that all (or just about all) methods take an optional 
	 *	argument at the end; if the argument == 1, then it will
	 *	print a generic error message for that type of pattern.
	 *	I almost never use this, but it is an option I built 
	 *	in just in case it might prove useful.
	 *	
	 */

	#  -----------------------
	#  FUNCTION: IS NOT NULL

	public static function isNotNull()  {

	/**
	 * 	Checks to make sure the input is not null.
	 *	
	 *	This method checks to make sure the variable being passed
	 *	is not null.  For strings, it simply tests to see if "" 
	 *	and for radio buttons or check boxes, uses isset.  The 
	 *	arguments it takes are (1) variable to test, (2) type of 
	 *	test -- either "tb" or "rb" for textbox and radio buttons, 
	 *	checkboxes, etc.
	 *	
	 */
   
	   $args = func_get_args();
		switch (count($args)) {

			case 2:  
					$var = $args[0];
					$type = $args[1];
					$print = 0;
					break;

			case 3:  
					$var = $args[0];
					$type = $args[1];
					if ($args[2] == 1 || $args[2] == "print") { $print = 1; }
					else { $print = 0; }
					break;
	    }

	   if ($type == "rb") {  #  radio button or check box or some other control
	        if (isset($var)) { return TRUE; } 
	        else { 
	        	if ($print == 1) { echo "Please set a value for this control. "; }
	        	return FALSE; 
	        }
	    }
    
	    else {  #  string value being passed, be sure it's not ""
	        if ($var != "") { return TRUE; } 
	        else { 
	        	if ($print == 1) { echo "Please set a value for this field. "; }
	        	return FALSE; 
	        }
	    }
    
	}  #  End of isNotNull()


	#  -----------------------
	#  FUNCTION: IS VALID

	public static function isValid()  {

	/**
	 * 	Generic method to test if input is valid.
	 *	
	 *	This method takes two required arguments and one 
	 *	optional one.  The first is the input to test and 
	 *	the second is regex used to test this input.  The 
	 *	third optional argument is a message you might want
	 *	echoed back if test fails.
	 *	
	 */

		$args = func_get_args();
		switch (count($args)) {

			case 2:  
					$value = $args[0];
					$pattern = $args[1];
					$msg = "";
					break;

			case 3:  
					$value = $args[0];
					$pattern = $args[1];
					$msg = $args[2];
					break;
	    }

		$theresults = preg_match($pattern, $value);
		if ($theresults) { return TRUE; } 
		else { 
			if ($msg != "") { echo $msg; }
			return FALSE; 
			}
	}  #   end of isValid()




}  #   END OF VALIDATE CLASS
?>
