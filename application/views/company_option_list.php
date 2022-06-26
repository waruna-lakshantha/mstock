<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	$result = $this->company->read_company();
	
	foreach ($result as $row)
	{
		echo "<option value=\"".$row->no."~".$row->comcode."\">".$row->description."</option>";
	}
			
?>