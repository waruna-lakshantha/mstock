<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	$result = $this->department->read_store_department();
	
	foreach ($result as $row)
	{
		echo "<option value=\"".$row->no."\">".$row->description."</option>";
	}
?>