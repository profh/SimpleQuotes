<?php

/*  This class is designed to make the generation of
	various types of drop down lists easier.  They 
	all have "memory" inasmuch as they draw from 
	(1) SESSION and (2) POST for previous inputs.
	
	All functions are static and can be called 
	directly when needed.                         */


class DropDownList {      // DROP DOWN LIST CLASS


// -----------------------
// FUNCTION: GET CHOICE

public static function getChoice($menuname) {

   // Check to see if a choice has been made previously or is a session variable
   if (!isset($_SESSION["$menuname"]) || $_SESSION["$menuname"] == "") { // no session var, see if in $_POST
        if (isset($_POST["$menuname"]) && $_POST["$menuname"] != -1) { $choice = $_POST["$menuname"]; }

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
// FUNCTION: TEST CURRENT

public static function getCurrent($arg) {

   // Check to see if a choice has been made previously or is a session variable
   if ($arg == "cur_date" || $arg == "current" || $arg == "today" || $arg == "yes" || $arg == "now")
	{ $settocurrent = 1; }  // set to current
	
	else { $settocurrent = 0; }
    
    return $settocurrent;

}  // end of testCurrent() 


// -----------------------
// FUNCTION: CREATE FROM ARRAY

/* This function creates a pull-down menu a hash array.  
   This can be used to generate quick pull-down menus,
   but there are some specialty menus below that are 
   commonly used -- using those specialty menus should 
   be even faster for common tasks.  
   
   If $auto == 1, then the menu will auto-submit on change.   */

public static function createFromArray()  {

   $args = func_get_args();
	
	switch (count($args)) {

		case 2:  
				$menuname = $args[0];
				$array = $args[1];
				$auto = 0;         // don't auto-submit
				break;

		case 3:  
				$menuname = $args[0];
				$array = $args[1];
				$auto = DropDownList::testAutoSubmit($args[2]);
				break;
    }
 
 	// Check to see if a choice has been made previously or is a session variable
   
   $choice = DropDownList::getChoice($menuname);
   
   // Start the menu 
   if ($auto == 1) {echo "<SELECT name=\"$menuname\" size=1 onchange=\"this.form.submit()\">";}
   else { echo "<SELECT name=\"$menuname\" size=1>"; }
   
   if ($choice == -1) { // No choice made -- user prompted to select
           echo "<OPTION value=\"-1\" selected>Select...</OPTION>";
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
   
} // End of createFromArray()




// -----------------------
// FUNCTION: CREATE STATE MENU

/* This function creates a pull-down menu of the 50 states 
   plus DC.  If the                                                                                                                             user already selected a state and that 
   information is in either $_POST or $_SESSION, it will be 
   pre-selected if the menu is ever reloaded.    */

public static function createStateMenu()  {
   
  $args = func_get_args();
	
	switch (count($args)) {

		case 1:  
				$menuname = $args[0];
				$type = "long";
				break;

		case 2:  
				$menuname = $args[0];
				$type = $args[1];
				break;
    }
  
  
  $choice = DropDownList::getChoice($menuname);    // get any prior choice
 
  if ($type == "short") {   // use an array of state abbreviations
  
  	 $states = array ("AL" => "AL", "AK" => "AK", "AZ" => "AZ", 
 		    		"AR" => "AR", "CA" => "CA", "CO" => "CO", 
 					"CT" => "CT", "DE" => "DE", 
 					"DC" => "DC", "FL" => "FL", 
 					"GA" => "GA", "HI" => "HI", "ID" => "ID", 
	 				"IL" => "IL", "IN" => "IN", "IA" => "IA", 
 					"KS" => "KS", "KY" => "KY", "LA" => "LA", 
					"ME" => "ME", "MD" => "MD", "MA" => "MA", 
					"MI" => "MI", "MN" => "MN", "MS" => "MS",
					"MO" => "MO", "MT" => "MT", "NE" => "NE", 
	                "NV" => "NV", "NH" => "NH", "NJ" => "NJ",
    	            "NM" => "NM", "NY" => "NY", 
        	        "NC" => "NC", "ND" => "ND", 
            	    "OH" => "OH", "OK" => "OK", "OR" => "OR", 
                	"PA" => "PA", "RI" => "RI", 
                	"SC" => "SC", "SD" => "SD", 
    	            "TN" => "TN", "TX" => "TX", "UT" => "UT", 
        		    "VT" => "VT", "VA" => "VA", "WA" => "WA", 
                	"WV" => "WV", "WI" => "WI", 
               		"WY" => "WY");
       asort($states);
  }
  
  else {   // use the state's full name
  	
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
          asort($states);
  	
  }
   
   echo "<SELECT name=\"$menuname\" size=1>";
   
   if ($choice == -1) { // No choice made -- user prompted to select
           echo "<OPTION value=\"-1\" selected>Select...</OPTION>";
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

public static function createMonthMenu() {

// Step 1: Get arguments -- second arg is settocurrent and third is autosubmit, but might be 
//		   accidently reversed, so will test to see if 2nd arg is either auto or submit

	$args = func_get_args();
	
	switch (count($args)) {

		case 1:  
				$menuname = $args[0];
				$settocurrent = "no";
				$auto = 0;         // don't auto-submit
				break;
		
		case 2:  
				$menuname = $args[0];
				if ($args[1] == "auto"  || $args[1] == "submit") { // accidently set 2nd value to autosubmit
					$auto = DropDownList::testAutoSubmit($args[1]);
					$settocurrent = "no";
				}
				else { 
					$settocurrent = DropDownList::getCurrent($args[1]);
					$auto = 0;         // don't auto-submit
				}
				break;

		case 3:  
				$menuname = $args[0];
				if ($args[1] == "auto"  || $args[1] == "submit") { // accidently set 2nd value to autosubmit
					$auto = DropDownList::testAutoSubmit($args[1]);
					$settocurrent = DropDownList::getCurrent($args[2]);
				}
				else { 
					$settocurrent = DropDownList::getCurrent($args[1]);
					$auto = DropDownList::testAutoSubmit($args[2]);
				}
				break;
    }


// Step 2: Set Value and Month Arrays
		$Month = array("January", "Feburary", "March", "April", "May", 
						"June", "July", "August", "September", "October", 
						"November", "December");
		$Value = array("1", "2", "3", "4", "5", "6", "7", "8", "9", 
						"10", "11", "12");
		
		
// Step 3: Check if no choice has been made and if set to current option is selected
	
    $choice = DropDownList::getChoice($menuname);
	
	//  if no choice, then combo box with current month selected or 'please select...'
		if ($choice == -1 || !$choice) {
			
			if ($auto == 1) {echo "<SELECT name=\"$menuname\" size=1 onchange=\"this.form.submit()\">";}
			else { echo "<SELECT name=\"$menuname\" size=1>"; }
			
			if ($settocurrent != 1) {  // Don't default to current month
			     echo "<OPTION value=\"-1\" selected>Month...</OPTION>";
			}
			
			for ($i = 0; $i < 12 ; $i++)  {
			     $curmonth = date("m");
			
                if ($Value[$i]==$curmonth && $settocurrent == 1) {
                   echo "<option value=$Value[$i] selected>$Month[$i]</option>";
                }
                
                else { echo "<option value=$Value[$i]>$Month[$i]</option>"; }
			}   // end of for-i loop
			
			echo "</SELECT>";
		}  // end of if no choice
	

// Step 4: If choice made, then create combo with memory
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
// FUNCTION: CREATE YEAR MENU

/* This function creates a typical year pull-down menu.  If there is no previous
   choice made by the user and it is not set to the current year (sometimes we 
   want it to default to current to make life easier) then it will start with the 
   typical 'please select...' msg.  **If settocurrent (last argument) is set to 'curr'
   then it will default to the current year.**  Also, if no start value is set, it
   will default to the current year.  If the user had previously chosen a 
   value, then that will be the selected year.  (To do this, use in conjunction
   with the getChoice fn.)  This menu moves backwards -- in some cases, the user is
   recording the past, but can't pick a future date (or year) so this is useful. */

public static function createYearMenu() {


// Step 1: Get arguments -- second arg is settocurrent and third is length, but might be 
//		   accidently reversed, so will test to see if 2nd arg is either current or length

	$args = func_get_args();
	
	switch (count($args)) {

		case 1:  
				$menuname = $args[0];
				$start = "current";	// start with current year
				$length = 5;
				$direction = "forward";
				$auto = 0;       	// don't auto-submit
				break;
		
		case 2:  
				$menuname = $args[0];
				if (is_numeric($args[1]) && $args[1] < 100) {
					$start = "current";
					$length = $args[1];
				}
				
				else { 
					$start = $args[1];
					$length = 5;
				}
				
				$direction = "forward";
				$auto = 0;       	// don't auto-submit
				break;

		case 3:  
				$menuname = $args[0];
				if (is_numeric($args[1]) && $args[1] < 100) {
					$start = $args[2];
					$length = $args[1];
				}
				
				else { 
					$start = $args[1];
					$length = $args[2];
				}
				
				$direction = "forward";
				$auto = 0;       	// don't auto-submit
				break;

		case 4:  
				$menuname = $args[0];
				if (is_numeric($args[1]) && $args[1] < 100) {
					$start = $args[2];
					$length = $args[1];
				}
				
				else { 
					$start = $args[1];
					$length = $args[2];
				}
				
				$direction = $args[3];
				$auto = 0;       	// don't auto-submit
				break;

		case 5:  
				$menuname = $args[0];
				if (is_numeric($args[1]) && $args[1] < 100) {
					$start = $args[2];
					$length = $args[1];
				}
				
				else { 
					$start = $args[1];
					$length = $args[2];
				}
				
				$direction = $args[3];
				$auto = DropDownList::testAutoSubmit($args[4]);
				break;

    } // end of switch case


// Step 1: Set up first and last values for year options
    if ($start == "current" || $start == "" ) { 
    	if ($direction == "forward") { $valueyear = date("Y") - 1; } // use current year
    	else { $valueyear = date("Y") + 1; }
    }
    else { 
    	if ($direction == "forward") { $valueyear = $start - 1; } 
    	else { $valueyear = $start + 1; }
    }
    
    if (!isset($length) || $length == 0) { $length = 5; } // use length of 5 if none given
		
		
// Step 2: Check if a choice has been made and build menu if no choice made
	
    // Check for choice data in $_SESSION and $_POST
    
    $choice = DropDownList::getChoice($menuname);
    
    
	//  if no choice, then combo box with current year at top or 'please select...'
		if ($choice == -1 || !$choice) {
			
			if ($auto == 1) {echo "<SELECT name=\"$menuname\" size=1 onchange=\"this.form.submit()\">";}
			else { echo "<SELECT name=\"$menuname\" size=1>"; }
			
			echo "<OPTION value=\"-1\" selected>Year...</OPTION>";
			
			for ($i = 0; $i < $length ; $i++)  {
			
			     if ($direction == "forward") { $valueyear++; }
			     else { $valueyear-- ; }
			     
			     echo "<option value=$valueyear>$valueyear</option>"; 

			}   // end of for-i loop
			
			echo "</SELECT>";
		}  // end of if no choice
	

// Step 3: If choice made, then create combo with memory
	else {  
		// start combo box
					
			if ($auto == 1) {echo "<SELECT name=\"$menuname\" size=1 onchange=\"this.form.submit()\">";}
			else { echo "<SELECT name=\"$menuname\" size=1>"; }
			
			for ($i = 0; $i < $length ; $i++)  {
			
			    if ($direction == "forward") { $valueyear++; }
			     else { $valueyear-- ; }
			
                if ($valueyear==$choice) {echo "<option value=$valueyear selected>$valueyear</option>";}
                
                else {echo "<option value=$valueyear>$valueyear</option>";}
			}  // end of for-i loop
			
			
			echo "</SELECT>";
			
		} //  end of else
		
	return;

} // End of createYearMenu()




}  //  END OF DROP DOWN LIST CLASS


?>