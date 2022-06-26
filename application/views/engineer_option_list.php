<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	$result = $this->engineer->read_engineer();
	
	foreach ($result as $row)
	{
		echo "<option value=\"".$row->no."\">".$row->engineer."</option>";
	}
			
?>
