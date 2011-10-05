<table border="0" cellspacing="5" cellpadding="5">
	<thead>
		<th width="700">Quote</th>
		<th width="150">Author</th>
	</thead>
	
	<?php  # add the alternating rows of quotes
	
		# first, get all the quote ids that are available
		$quote_ids = Quote::getAllQuotes();
	
		# now loop through each id and print out a table row for it
		$j = 0;
		foreach ($quote_ids as $qid) {
			# alternate row colors
			if ($j % 2 != 0) { $row_class = "alt"; }
			else { $row_class = "reg"; }
		
			# initialize a quote object
			$quote = new Quote();
			$quote->init($qid);
			
			// echo "$quote->getText() - $quote->getAuthor()";
	?>
		<!-- create the row -->
		<tr class="<?php echo $row_class; ?>">
			<td><?php echo $quote->getText(); ?></td>
			<td><?php echo $quote->getAuthor(); ?></td>
		</tr>
		
	<?php	
			# increment j so it will alternate
			$j++;	
		} # close the brackets
	?>
		
</table>

        