<?php

class Button {   //  START OF BUTTON CLASS

// ------------------------------------
// FUNCTION: CREATE BUTTON

public static function createButton() {

/*  This function creates the HTML submit button.
    The submit button title can be modified by
    passing a string to the function -- if left 
    blank, the default will be 'Submit'.    */
    
	$args = func_get_args();
	
	switch (count($args)) {

		case 0:  
				$value = "Submit";
				break;

		case 1:  
				$value = $args[0];
				break;
    }
    
	echo "<INPUT name=\"Submit\" type=\"Submit\" value=\"$value\">"; 

}  //  end of createButton()

} //  END OF BUTTON CLASS

?>