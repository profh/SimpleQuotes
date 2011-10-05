<?php

/*  This class is designed to make login tasks easier 
    (since they are often repeated tasks) and allow 
    logging of information for security purposes.  This 
    uses the Observer pattern and additional observers
    can be added to increase functionality of login
    (e.g., set session vars, add cookies, etc.)
    
    To use this class, the following assumptions are made:
    
    1.  You are validating against fields from a database.
        Furthermore, the database connection information has
        been specified in db_connect.php and you are using the 
        DB class (in the same directory as this class).
        
    2.	There are two observers already created -- one to log 
    		successful logins (general_logger) and one to log bad
    		logins (security_monitor).  They need to be attached if
    		you want them deployed (not done so by default).
    	
    		To attach/detach, can use code as follows:
    		$login = new Login();
				$login->attach( new SecurityMonitor() );
		
		3.	SessionSetter and CookieCutter are two other observers
				that are generally set up, but have to be customized 
				for each application right now.  (Perhaps in the future
				I will get around to rewriting this class...)
    	
    On to setting up the class...
    ======================================    */

// ------------------------------------
// INTERFACE: OBSERVABLE

interface Observable {
    function attach( Observer $observer );
    function detach( Observer $observer );
    function notify();
}

// ------------------------------------
// MAIN CLASS: LOGIN


class Login implements Observable {

    // -----------------------
    // CLASS PROPERTIES
    // -----------------------
    
    private $observers = array();
    private $login_status;
    private $user_table;
    private $user_id_field;
    private $pswd_field;
    private $user_id;
    private $attempted_login_id;
    private $attempted_pswd;
    
    const LOGIN_USER_UNKNOWN = 1;
    const LOGIN_WRONG_PASS   = 2;
    const LOGIN_ACCESS       = 3;

    // -----------------------
    // CONSTRUCTOR
    // -----------------------
    
    function __construct() {

	//include_once ("DB.php");

		$this->user_table = "customers";
    $this->user_id_field = "username";       
    $this->pswd_field = "password";
    $this->user_id = "";
    $this->attempted_login_id = "";
    $this->attempted_pswd = "";
    
    }   // End of Constructor
        
    // -----------------------
    // SET & GET METHODS
    // -----------------------

    public function setUserTable($string) { $this->user_table = $string; }
    public function setUserIDField($string) { $this->user_id_field = $string; }
    public function setPswdField($string) { $this->pswd_field = $string; }
    private function setLoginStatus($int) { $this->login_status = $int; }
    public function getLoginStatus() { return $this->login_status; }
    private function setUserID($string) { $this->user_id = $string; }
    public function getUserID() { return $this->user_id; }
    private function setAttemptedID($string) { $this->attempted_login_id = $string; }
    public function getAttemptedID() { return $this->attempted_login_id; }
    private function setAttemptedPswd($string) { $this->attempted_pswd = $string; }
    public function getAttemptedPswd() { return $this->attempted_pswd; }
    
    
    // -----------------------
    // OBSERVER METHODS
    // -----------------------
    
    public function attach( Observer $observer ) {
        $this->observers[] = $observer;
    }

    public function detach( Observer $observer ) {
        $this->observers = array_diff( $this->observers, array($observer) );
    }

    public function notify() {
        foreach ( $this->observers as $obs ) {
            $obs->update( $this );
        }
    }

    // ---------------------------
    // MAIN METHOD - CHECK LOGIN
    // ---------------------------
    
    public function checkLogin( $user_id, $pswd ) {
        
        $db = new DB();
        if (!$db->Open()) { return FALSE; }
        
        //  First, get the database table and fields to compare against
        $tbl = $this->user_table;
        $id = $this->user_id_field;
        $pw = $this->pswd_field;
        $u_id = trim($user_id);  // in case we forgot to trim it earlier...
        $pswd = trim($pswd);
        $safe_id = $db->makeSafe($u_id);  // make safe in case of injection
        $this->attempted_login_id = $u_id;
        $this->attempted_pswd = $pswd;
        
        $query = "SELECT $pw FROM $tbl WHERE $id = $safe_id";
        
        
        $db = new DB();
		$password = $db->getScalar($query);
	
		
		if (!$password) {  // user id not found in db
			$this->setLoginStatus(0);
			$this->notify();
			return FALSE;
		}
		
		else {
			if ($password != $pswd)  {  // pswd doesn't match
				$this->setLoginStatus(1);
				$this->notify();
				return FALSE;
			}
			else {  // all is well...
				$this->setLoginStatus(2);
				$this->user_id = $u_id;
				$this->notify();
				return TRUE;
			}
		}
    }  // end of checkLogin()

}  // End of Login class


// ------------------------------------
// INTERFACE: OBSERVER

interface Observer {
    public function update( Observable $observer );
}


// ------------------------------------
// EXTENSIONS ON OBSERVER  

class SessionSetter implements Observer {

//  NOTE: This observer is specific for JustBread Application
    public function update( Observable $observable ) {
    
        $login_status = $observable->getLoginStatus(); 
        
        if ($login_status == 2) {  // user successfully logs in
            
          $newdb = new DB();
        	if (!$newdb->Open()) { return FALSE; }
        	$u_id = $observable->getUserID();
          $safe_id = $newdb->makeSafe($u_id);  // make safe in case of injection
          
          $query = "SELECT customer_id FROM customers WHERE username = $safe_id";
          
          $customer_id = $newdb->getScalar($query);
          
          $customer = new CUstomer();
          $customer->init($customer_id);

          // Set session variables here
					$_SESSION["jb_person_id"] = $customer_id; 
					$_SESSION["jb_first_name"] = $customer->getFirstName();
					$_SESSION["jb_logged_in"] = "yes";    
        }
    } // end of update()
    
}  // end of SessionSetter

?>
