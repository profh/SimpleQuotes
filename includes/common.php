<?php

#	Include general helper classes here
include_once ("includes/lib/arrays.php");
include_once ("includes/lib/DB.php");
include_once ("includes/lib/db_connect.php");
include_once ("includes/lib/validate.php");
include_once ("includes/lib/forms.php");
include_once ("includes/lib/login.php");


#   Create instance of DB to work with
$db = new DB();


#	Include form controls here
include_once ("includes/lib/form_controls/dropdownlist.php");
include_once ("includes/lib/form_controls/textbox.php");
include_once ("includes/lib/form_controls/checkbox.php");
include_once ("includes/lib/form_controls/radiobutton.php");
include_once ("includes/lib/form_controls/button.php");


#	Include entities here
include_once ("includes/entities/customer.php");


#	Include page set-up library here
include_once ("includes/page.php");


#	Now start session (after class definitions loaded, but
#	before any html is generated -- otherwise error occurs)
session_start();


#	Create instance of the page controller to work with
$page = new Page();


#  See if this page is supposed to be print-friendly
if (isset($_GET["view"]) && $_GET["view"]=="pf") { $pf = TRUE; }
else { $pf = FALSE; }


?>