<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	$html_out = "";
	$result = $this->store->read_store();
	
	foreach ($result as $row)
	{
		$html_out .= "<option value=\"".$row->no."\">".$row->description."</option>";
	}
	
	echo $html_out;
?>