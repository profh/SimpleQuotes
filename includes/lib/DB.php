<?php

/**
 * 
 *  This class handles basic database issues such as connecting,
 *	getting query results, inserting and updating records, and 
 *	ensuring the format is appropriate.
 *	
 *	This is for a MySQL database only.  Most code has been updated to 
 * 	PHP5, but with all these classes there might be remains of very 
 *	old code lurking here...  (version 0.1 was for PHP3!)
 *	
 *	Key methods include:
 *	
 * 	1.  Open -- this method opens a link to the database.  It is assumed that 
 * 			key connection data is stored in a file called "db_connect.php"
 * 			and is in the same directory as this class.  Is public method.
 * 		
 * 	2.	Close -- this method closes the connection.  Not really necessary 
 *			PHP will close the connection automatically when the script is 
 *			finished running.  Is public method.
 * 	
 * 	3.	getRecords -- this method takes a SELECT query and (after verifying 
 * 			it is a SELECT query) returns a results pointer to be used in getting 
 * 			the data.  Better access can be had using the following methods in 
 * 			many instances:
 *		
 * 			a. getScalar($user_query) -- returns a single value from db
 * 			b. getArray($user_query) -- returns a simple array from db
 *			c. getHash($user_query) -- returns an associative array from db
 *			d. getOneRecord($table, $conditions) -- get all the fields for a 
 * 			single record in a table.  If multiple rows would be returned, 
 *			only the last row is displayed.
 * 		
 *	4.	insertRecord -- this method takes an INSERT query, verifies the query 
 * 			and then inserts the record from the query into the database.  If the
 *			method executes, it returns the new ID of the record from the database.  
 *			In the case where there was no new ID created (such as an association 
 *			table), then it simply returns true.
 *	
 *	5.  updateRecord -- this method takes an UPDATE query, verifies the query 
 *			and then updates the record from the query in the database.
 */


class DB {     

    private $host;
    private $userid;
    private $pswd;
    private $db_name;
    private $link;
    private $result;


function __construct() {

	@include ("db_connect.php");
	
  if (isset($dbHost)) { $this->host = $dbHost; } else { $this->host = ""; }
	if (isset($dbUser)) { $this->userid = $dbUser; } else { $this->userid = ""; }
	if (isset($dbPswd)) { $this->pswd = $dbPswd; } else { $this->pswd = ""; }
	if (isset($Database)) { $this->db_name = $Database; } else { $this->db_name = ""; }
	
	$open = $this->Open();
	if (!$open) { return FALSE; }
    
}   #  End of Constructor


#  ===============
#   SET FUNCTIONS 
#  ===============

public function setDBHost($string) { $this->host = $string; }

public function setUserID($string) { $this->userid = $string; }

public function setPswd($string) { $this->pswd = $string; }

public function setDBName($string) { $this->db_name = $string; }

public function getLink() { return $this->link; }   #  debugging purposes only


# =================
#  PREP FUNCTIONS	
# =================

#  -----------------------------
#    FUNCTION: MAKE SAFE

public function makeSafe($var) {
/**
 *  This fn preps a string for insertion into the db
 */
	if($var === null) { return "NULL"; }
	else { 
		#  trim, escape bad chars and add single quotes
		$revised = "'" . mysql_real_escape_string(trim($var),$this->link) . "'";
		return $revised; 
	}
	
}  #  end of makeSafe()


#  -----------------------------
#    FUNCTION: FROM DB

public function cleanData($var) {
/**
 * 	This fn cleans a string retrieved from the db
 */
	if($var === null) { return "NULL"; }
	else { 
		#  trim and strip slashes
		$revised = stripslashes(trim($var));
		return $revised; 
	}
	
}  #  end of fromDB()
	

#  -----------------------------
#    FUNCTION: PRINT ERROR REPORTS

private function print_errors($errno) {  #  use primarily for debugging, can be used outside

    switch ($errno) {

      case 1:  echo "Database host needs to be specified. ";  break;
      case 2:  echo "No user ID has been specified. ";  break;
      case 3:  echo "No password have been given. ";  break;
      case 4:  echo "Database was not opened. ";  break;
      case 5:  echo "The name of the database needs to be specified. ";  break;
	  	case 6:  echo "The database could not be selected. ";  break;
      case 7:  echo "The query is not a SELECT query. ";  break;
      case 8:  echo "No records were returned for this query. ";  break;
      case 9:  echo "The query is not an INSERT query. ";  break;
      case 10:  echo "The records were not inserted. ";  break;
      case 11:  echo "The query is not an UPDATE query. ";  break;
      case 12:  echo "The records were not updated. ";  break;
      case 13:  echo "The records were not deleted. ";  break;
    }

}  #  End of print_errors()


#  -----------------------------
#    FUNCTION: OPEN

public function Open() {
	
/**
 * 	Opens a connection to the database
 */

	# 	Make sure key data is not blank
		
		if ($this->host == "") { 
			$errno = 1;
			# $this->print_errors($errno);
			$this->link = FALSE;
			return FALSE; 
		}
		
		if ($this->userid == "") {
			$errno = 2;
			# $this->print_errors($errno);
			$this->link = FALSE;
			return FALSE;
		}
		
		if ($this->pswd == "") {
			$errno = 3;
			# $this->print_errors($errno);
			$this->link = FALSE;
			return FALSE;
		}
		
	
	#   Set up a link to the database  	
		@$Link = mysql_connect($this->host, $this->userid, $this->pswd);
		
		if (!$Link) {   #  Link couldn't be established
			$errno = 4;
			# $this->print_errors($errno);
			$this->link = FALSE;
			return FALSE;
		}
		
		else { 
			$this->link = $Link;
		}
		
		$DBName = $this->db_name;

		if ($DBName == "") {   #  No database has been specified
			$errno = 5;
			# $this->print_errors($errno);
			$this->Close();
			return FALSE;
		}
		else { 
			@$selected = mysql_select_db($DBName, $Link); 
			if (!$selected) { 
				$errno = 6;
				# $this->print_errors($errno);
				$this->Close();
				$this->db_name = "";  # didn't work, so set name to blank
				return FALSE;
			}

			return TRUE;
		}

}  #  end of Open()


#  -----------------------------
#    FUNCTION: CLOSE

public function Close() {

/**
 * 	Closes the connection to the database
 */

	$Link = $this->link;
	
	if (!isset($Link) || !$Link || $Link == "") {   #  Link couldn't be established
			$errno = 4;
			# $this->print_errors($errno);
			return FALSE;
		}

	else { 
		mysql_close($Link); 
		return TRUE;
	}
		
} #  end of Close()


#  -----------------------------
#    FUNCTION: GET RECORDS

public function getRecords($user_query) {

#	USED ONLY FOR OLDER CODE -- DO NOT USE NOW

	$Link = $this->link;
	$DB_Name = $this->db_name;
	
	if (!$Link || $DB_Name == "") {  # in case the link not set...
		$open = $this->Open();
		if (!$open) { return FALSE; }
		$Link = $this->link;
	}

	#   Check the query to make sure it is a SELECT query
	
	if (eregi("^SELECT[^;]+$", $user_query)) {
	
		$tested_query = $user_query;
		# echo "<BR><BR>SELECT query is: $tested_query<BR><BR>";
	}
	
	else {   #  was not an appropriate query
		$errno = 7;
		# $this->print_errors($errno);
		return FALSE;
	}
	
	#   Execute the query
		@$Result = mysql_query($tested_query, $Link);

	#   If some results returned, return the results info
		if ($Result) {
			$this->result = $Result;
			return $Result;
		}
		
	#   Else inform the user that no data was returned by the query
		else {
			$errno = 8;
			# $this->print_errors($errno);
			return FALSE;
		}
		
} #  end of getRecords()


#  -----------------------------
#    FUNCTION: GET SCALAR

public function getScalar($user_query) {

/**
 * 	This function returns a single value from the database for a given query.
 */  

	$Link = $this->link;
	$DB_Name = $this->db_name;
	
	if (!$Link || $DB_Name == "") {  # in case the link not set...
		$open = $this->Open();
		if (!$open) { return FALSE; }
		$Link = $this->link;
	}
	
	#   Check the query to make sure it is a SELECT query
	
	if (preg_match('/^SELECT[^;]+$/', $user_query)) {
	
		$tested_query = $user_query;
		# echo "<BR><BR>SELECT query is: $tested_query<BR><BR>";
	}
	
	else {   #  was not an appropriate query
		$errno = 7;
		# $this->print_errors($errno);
		return FALSE;
	}
	
	#   Execute the query
		@$Result = mysql_query($tested_query, $Link);

	#   If some results returned, return the results info
		if ($Result) {
			@$scalar = $this->cleanData(mysql_result($Result, 0));
			return $scalar;
		}
		
	#   Else inform the user that no data was returned by the query
		else {
			$errno = 8;
			# $this->print_errors($errno);
			return FALSE;
		}
		
} #  end of getScalar()


#  -----------------------------
#    FUNCTION: GET ARRAY

public function getArray($user_query) {

/**
 * 	This function returns a simple array from the database for a given query. 
 */ 

	$Link = $this->link;
	$DB_Name = $this->db_name;
	
	if (!$Link || $DB_Name == "") {  # in case the link not set...
		$open = $this->Open();
		if (!$open) { return FALSE; }
		$Link = $this->link;
	}
	
	#   Check the query to make sure it is a SELECT query
	
	if (preg_match('/^SELECT[^;]+$/', $user_query)) {
	
		$tested_query = $user_query;
		# echo "<BR><BR>SELECT query is: $tested_query<BR><BR>";
	}
	
	else {   #  was not an appropriate query
		$errno = 7;
		# $this->print_errors($errno);
		return FALSE;
	}
	
	#   Execute the query
		@$Result = mysql_query($tested_query, $Link);

	#   If some results returned, place results in an array
		if ($Result) {
			
			while ($Query_results = mysql_fetch_row($Result)) {
				
				$value = $this->cleanData($Query_results[0]);
				$tempArray[] = $value;
			} #  end while loop
			
			return $tempArray;
		}
		
	#   Else inform the user that no data was returned by the query
		else {
			$errno = 8;
			# $this->print_errors($errno);
			return FALSE;
		}
} #  end of getArray()


#  -----------------------------
#  FUNCTION: GET HASH

public function getHash($user_query) {

/**
 *  This function returns an associative array from the database for a given query.
 */  

	$Link = $this->link;
	$DB_Name = $this->db_name;
	
	if (!$Link || $DB_Name == "") {  # in case the link not set...
		$open = $this->Open();
		if (!$open) { return FALSE; }
		$Link = $this->link;
	}

	#   Check the query to make sure it is a SELECT query
	
	if (preg_match('/^SELECT[^;]+$/', $user_query)) {
	
		$tested_query = $user_query;
		# echo "<BR><BR>SELECT query is: $tested_query<BR><BR>";
	}
	
	else {   #  was not an appropriate query
		$errno = 7;
		# $this->print_errors($errno);
		return FALSE;
	}
	
	#   Execute the query
		@$Result = mysql_query($tested_query, $Link);

	#   If some results returned, place results in an array
		if ($Result) {
			
			while ($Query_results = mysql_fetch_row($Result)) {
				
				$key = $this->cleanData($Query_results[0]);
				$value = $this->cleanData($Query_results[1]);
				$tempArray[$key] = $value;
			} #  end while loop
			
			return $tempArray;
		}
		
	#   Else inform the user that no data was returned by the query
		else {
			$errno = 8;
			# $this->print_errors($errno);
			return FALSE;
		}
} #  end of getHash()


#  -----------------------------
#    FUNCTION: GET ONE RECORD

public function getOneRecord($table, $conditions) {

/**
 * 	Gets all the data for one record in a table and returns as a hash (field=>value).  
 *	If no condition set, the last row data is returned.  (can be useful...)
 */

	$Link = $this->link;
	$DB_Name = $this->db_name;
	
	if (!$Link || $DB_Name == "") {  # in case the link not set...
		$open = $this->Open();
		if (!$open) { return FALSE; }
		$Link = $this->link;
	}
	
	# 	Query to get the field names
		$query = "SHOW COLUMNS FROM $table";
	
	#   Execute the query
		@$Result = mysql_query($query, $Link);
		
	#   put the fields into an array
		if ($Result) {   
			while ($Query_results = mysql_fetch_row($Result)) {
				
				$field_name = $this->cleanData($Query_results[0]);
				$fields_array[] = $field_name;
			} #  end while loop
		}  #  end of if fields found
		
	#   Else inform the user that no fields were found in table
		else {    
			$errno = 8;
			# $this->print_errors($errno);
			return FALSE;
		}
	
	# 	Prepare to get the record in question	
		$numColumns = count($fields_array);	
		$query2 = "SELECT * FROM $table $conditions";
		
		@$Result2 = mysql_query($query2, $Link);
		
		if ($Result2) {
			while ($Query_results2 = mysql_fetch_row($Result2)) {
				for ($i=0; $i<$numColumns; $i++) {
					$one_record[$fields_array[$i]] = $this->cleanData($Query_results2[$i]);
				}  #  end of for loop
			} #  end of while loop
		
			return $one_record;
		}
		
		#   Else inform the user that the record in question was not found
		else {
			$errno = 8;
			# $this->print_errors($errno);
			return FALSE;
		}
		
} #  end of getOneRecord()


#  -----------------------------
#    FUNCTION: INSERT RECORD

public function insertRecord($user_query) {
	
/**
 * 	Inserts a record into the database given a proper insert query.
 */

	$Link = $this->link;
	$DB_Name = $this->db_name;
	
	if (!$Link || $DB_Name == "") {  # in case the link not set...
		$open = $this->Open();
		if (!$open) { return FALSE; }
		$Link = $this->link;
	}

	#   Check the query to make sure it is an INSERT query
	
	if (preg_match('/^INSERT INTO[^;]+\)$/', $user_query)) {
   		$tested_query = $user_query;
   		# echo "<BR><BR>Insert query is: $tested_query<BR><BR>";
	}
	
	else {   #  was not an appropriate query
		$errno = 9;
		# $this->print_errors($errno);
		return FALSE;
	}
	
	#   Execute the query
		@$Result = mysql_query($tested_query, $Link);

	#   if the record was inserted, return the new ID associated with the record
		if ($Result) {   
		
			$new_id = mysql_insert_id($Link);    #  the ID of the record just inserted
			if ($new_id) { return $new_id; }
			else { return TRUE; }    #  if there was no new ID created (e.g., assoc tbl)
		}
		
	#   Else inform the user that no data was inserted into the database
		else {
			$errno = 10;
			# $this->print_errors($errno);
			return FALSE;
		}
		
} #  end of insertRecord()


#  -----------------------------
#    FUNCTION: UPDATE RECORD

public function updateRecord($user_query) {
	
/**
 * 	Updates the record in the database given a proper update query.
 */
	
	$Link = $this->link;
	$DB_Name = $this->db_name;
	
	if (!$Link || $DB_Name == "") {  # in case the link not set...
		$open = $this->Open();
		if (!$open) { return FALSE; }
		$Link = $this->link;
	}
	
	#   Check the query to make sure it is an UPDATE query
	
	if (preg_match('/^UPDATE[^;]+$/', $user_query)) {
   		$tested_query = $user_query;
   		# echo "<BR><BR>Update query is: $tested_query<BR><BR>";
	}
	
	else {   #  was not an appropriate query
		$errno = 11;
		# $this->print_errors($errno);
		return FALSE;
	}
	
	#   Execute the query
		@$Result = mysql_query($tested_query, $Link);
		
	#   if the record was updated, return true
		if ($Result) {   
			return TRUE;
		}
		
	#   Else inform the user that no data was updated in the database
		else {
			$errno = 12;
			# $this->print_errors($errno);
			return FALSE;
		}
		
} #  end of updateRecord()


#  -----------------------------
#    FUNCTION: DELETE RECORD

public function deleteRecord($table, $field, $value) {

/**
 * 	Deletes a record from the database.
 *	
 *	Deletion can be dangerous and done improperly.  To try to minimize damage,
 *	this method works by taking three arguments.  The first is the name of the 
 *	table we want to delete the record from.  Next we have to specify the 
 *	condition for deletion (e.g., where user_id = 9).  The second argument is
 *	the name of the field in the condition and the third argument is the 
 *	value of that field.  If more than one condition holds, this last argument 
 *	can be amended to add in additional conditions (e.g., "9 AND active = 0").
 * 
 */

	$Link = $this->link;
	$DB_Name = $this->db_name;
	
	if (!$Link || $DB_Name == "") {  # in case the link not set...
		$open = $this->Open();
		if (!$open) { return FALSE; }
		$Link = $this->link;
	}
	
	$query = "DELETE FROM $table WHERE $field = $value";
	
	#   Execute the query
		@$Result = mysql_query($query, $Link);
		
	#   if the record was deleted, return true
		if ($Result) {   
			return TRUE;
		}
		
	#   Else inform the user that no data was deleted in the database
		else {
			$errno = 13;
			# $this->print_errors($errno);
			return FALSE;
		}
				
} #  end of deleteRecord()


#  -----------------------------
#    FUNCTION: GET NUM ROWS

public function getNumRows($user_query) {

/**
 *  This function returns the number of rows returned by a query.
 */ 

	$Link = $this->link;
	$DB_Name = $this->db_name;
	
	if (!$Link || $DB_Name == "") {  # in case the link not set...
		$open = $this->Open();
		if (!$open) { return FALSE; }
		$Link = $this->link;
	}
	
	#   Check the query to make sure it is a SELECT query
	
	if (preg_match('/^SELECT[^;]+$/', $user_query)) {
	
		$tested_query = $user_query;
		# echo "<BR><BR>SELECT query is: $tested_query<BR><BR>";
	}
	
	else {   #  was not an appropriate query
		$errno = 7;
		# $this->print_errors($errno);
		return FALSE;
	}
	
	#   Execute the query
		@$Result = mysql_query($tested_query, $Link);

	#   If some results returned, get the number of rows returned
		if ($Result) {
			@$numrows = mysql_num_rows($Result);
			return $numrows;
		}
		
	#   Else inform the user that no data was returned by the query
		else {
			$errno = 8;
			# $this->print_errors($errno);
			return FALSE;
		}
		
} #  end of getNumRows()


#  -----------------------------
#    FUNCTION: DROP TABLE

public function dropTable($table) {

/**
 * 	Drops a given table from the database.  BE CAREFUL!!
 */

	$Link = $this->link;
	$DB_Name = $this->db_name;
	
	if (!$Link || $DB_Name == "") {  # in case the link not set...
		$open = $this->Open();
		if (!$open) { return FALSE; }
		$Link = $this->link;
	}
	
	$query = "DROP TABLE $table";
	
	#   Execute the query
		@$Result = mysql_query($query, $Link);
		
	#   if the record was deleted, return true
		if ($Result) {   
			return TRUE;
		}
		
	#   Else inform the user that table was not dropped from the database
		else {
			$errno = 14;
			# $this->print_errors($errno);
			return FALSE;
		}
				
} #  end of dropTable()


#  -----------------------------
#    FUNCTION: CREATE TABLE

public function createTable($user_query) {
	
/**
 * 	Creates a table from a proper SQL query.
 */	

	$Link = $this->link;
	$DB_Name = $this->db_name;
	
	if (!$Link || $DB_Name == "") {  # in case the link not set...
		$open = $this->Open();
		if (!$open) { return FALSE; }
		$Link = $this->link;
	}
	
	#   Check the query to make sure it is a CREATE TABLE query
	
	if (eregi("^CREATE TABLE[[:alnum:][:space:][:punct:]]+$", $user_query)) {
   		$tested_query = $user_query;
	}
	
	else {   #  was not an appropriate query
		$errno = 11;
		# $this->print_errors($errno);
		return FALSE;
	}
	
	#   Execute the query
		@$Result = mysql_query($tested_query, $Link);
		
	#   if the table was created, return true
		if ($Result) {   
			return TRUE;
		}
		
	#   Else inform the user that the table was not created
		else {
			$errno = 15;
			# $this->print_errors($errno);
			return FALSE;
		}
				
} #  end of createTable()



} #  END OF DB CLASS


?>