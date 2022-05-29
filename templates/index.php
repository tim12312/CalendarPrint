<?php
script('calendarprint', 'script');
style('calendarprint', 'style');
?>

<div id="app">
	<table>
		<tr>
<td>Month:</td><td>  <input type="number" id="month" name="quantity" min="1" max="12" value=6></td>
</tr><tr>
	<td>Year:</td><td> <input type='text' id='year' value=2022></td>
</tr>
<tr>
<td colspan="2">
<button id='run'>print</button> 
</td>
</tr>
</table>
</div>

