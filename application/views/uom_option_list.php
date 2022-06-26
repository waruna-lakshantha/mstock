<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	$result = $this->uom->read_uom();
	
	foreach ($result as $row)
	{			
		echo "<option value=\"".$row->uom."\">".$row->uom."</option>";
	}		
?>