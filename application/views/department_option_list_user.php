<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	$result = $this->department->read_user_department();
	
	foreach ($result as $row)
	{
		echo "<option value=\"".$row->no."\">".$row->description."</option>";
	}
?>