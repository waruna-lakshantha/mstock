<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	$html_out = "";
	$result = $this->job_type->read_job_type();
	
	foreach ($result as $row)
	{
		$html_out .= "<option value=\"".$row->no."\">".$row->type."</option>";
	}
	
	echo $html_out;
?>