<?php
	$quote_form = new Forms();  // new instance of Forms class    
	$quote_form->startForm();   // start the form
?>
	
	<p><strong>Add a new quote:</strong></p>
	
	<table border="0" cellspacing="5" cellpadding="5">
		<tr>
			<td width="75" align="right">Quote:</td>
			<td align="left"><input name="text" type="text" size="125"></td>
		</tr>
		<tr>
			<td width="75" align="right">Author:</td>
			<td align="left"><input name="author" type="text" size="50"></td>
		</tr>
	</table>

<?php
	$quote_form->endForm("Add Quote");
?>