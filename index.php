<?php
		# include some basic lib files
    include ("includes/lib/arrays.php");
		include ("includes/lib/forms.php");
    include ("includes/lib/form_controls/textbox.php");
		include ("includes/lib/db_connect.php");
    include ("includes/lib/DB.php");
		include ("includes/entities/quote.php");

		# Start with a basic header
    include ("partials/header.php");
    
    #  If trying to submit a quote, add it to the database
    if (isset($_POST["Submit"]) && $_POST["Submit"] == "Add Quote") {
			# get the data from the $_POST array
    	$text = $_POST["text"];
			$author = $_POST["author"];
			
			# create a new instance of quotes to work with and set values
			$quote = new Quote();
			$quote->setText($text);
			$quote->setAuthor($author);
			
			# try to save quote to the database and inform user
			# use notice class if good and error class if bad
			if ($quote->insertNewRecord()) {
				echo "<p class=\"notice\">New quote added to the system.</p>";
			}
			else {
				echo "<p class=\"error\">Quote could not be added to the system.</p>";
			}
    }  #  end of if submit

		# In any case, display the list of quotes
		include ("partials/quote_list.php");
		
		# Provide the form needed to add a new quote
		echo "<p class=\"supertiny\">&nbsp;</p>";
		include ("partials/new_quote_form.php");
		
		# Add a footer
		echo "<p class=\"supertiny\">&nbsp;</p>";
    include ("partials/footer.php");
?>