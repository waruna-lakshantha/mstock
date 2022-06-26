<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	$result = $this->item_category->read_item_category();
	
	foreach ($result as $row)
	{
		echo "<option value=\"".$row->no."\">".$row->description."</option>";
	}
			
?>