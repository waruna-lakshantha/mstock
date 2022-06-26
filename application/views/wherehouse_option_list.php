<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	$html_out = "";
	$result = $this->wherehouse->read_wherehouse();
	
	foreach ($result as $row)
	{
		$html_out .= "<option value=\"".$row->no."\">".$row->whcode.'-'.$row->description."</option>";
	}
	
	echo $html_out;
?>