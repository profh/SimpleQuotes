<?php
	class Quote {
		# properties
		private $quote_id;
		private $text;
		private $author;
		private $active;
	
		# constructor
		function __construct() 	{
			$this->quote_id = -1;
			$this->text = "";
			$this->author = "";
			$this->active = 1;
		}
	
		# getters and setters
		public function getQuoteID() { return $this->quote_id; }
		public function getText() { return $this->text; }
		public function getAuthor() { return $this->author; }
		public function getActive() { return $this->active; }

		public function setQuoteID($int) { $this->user_id = $int; }
		public function setText($str) { $this->text = $str; }
		public function setAuthor($str) { $this->author = $str; }
		public function setActive($int) { $this->active = $int; }
	
		# other methods
		# -------------------
		# TEST QUOTE ID
		private static function checkQuoteID($quote_id) {
			if (is_numeric($quote_id) && $quote_id > 0 && preg_match("/^[0-9]+$/", $quote_id))  { return TRUE; }
			else { return FALSE; }
		}
		
		# -------------------
		# INITIALIZE FROM DB

		public function init($quote_id)	{
			# make sure the id is set properly (i.e., integer greater than zero)
			if (Quote::checkQuoteID($quote_id)) {
				$condition = "WHERE quote_id = $quote_id";

				$db = new DB();
				$data = $db->getOneRecord("quotes", $condition);

				if (!$data) {  #  No results were found 
					return FALSE;
				}

				else {
					$this->setText($data["text"]);
					$this->setAuthor($data["author"]);
					$this->setActive($data["active"]);
				}	
				return TRUE;
			}
			else { return FALSE; }
			
		} #  End of init()
		
		# -------------------
		#  UPDATE DB

		public function updateDB()  { 
			#  assuming a valid quote id
			if (Quote::checkQuoteID($this->quote_id)) {
				
				# Create and open database connection
				$db = new DB();
				if (!$db->Open()) { return FALSE; }

				# prepare data for update
				$quote_id = $db->makeSafe($this->quote_id);
				$text = $db->makeSafe($this->text);
				$author = $db->makeSafe($this->author);
				$active = $db->makeSafe($this->active);

				#  Update database query
				$query = "UPDATE quotes 
									SET text = $klingon_phrase,
										author = $author,
										active = $active
									WHERE quote_id = $quote_id";

				#  Execute the query and return the result
				$result = $db->updateRecord($query);

				if ($result)  { 
					return TRUE;
				}
				else {
					#  echo "error: could not update DB<br>";
					return FALSE;
				}
			}
			else { return FALSE; }
		} #  End of updateDB()


		# -------------------
		#  INSERT NEW RECORD

		public function insertNewRecord()  {

			# Create and open database connection
			$db = new DB();
			if (!$db->Open()) { return FALSE; }

			$text = $db->makeSafe($this->text);
			$author = $db->makeSafe($this->author);
			$active = $db->makeSafe($this->active);

			#  Insert into database

			$query = "INSERT INTO quotes (quote_id, text, author, active)  
						VALUES (NULL, $text, $author, $active)";

			$result = $db->insertRecord($query);

			if(is_numeric($result))  {
				$this->quote_id = $result;
				return TRUE;
			}
			else {
				return FALSE;
			}
		} #  End of insertNewRecord()
		
		# -------------------
		#  GET ALL ACTIVE QUOTES
		public static function getAllQuotes() {
			# the query...
		  $query = "SELECT quote_id FROM quotes WHERE active = 1 ORDER BY author";
			# get db instance
		  $db = new DB();
			# make sure it's open and working
			if (!$db->Open()) { return FALSE; }
			# get the data and return
		  $quote_ids = $db->getArray($query);
			return $quote_ids;
			
		} # End of getAllQuotes
		
	} # End of Quote class
?>
