<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	$result = $this->company->read_company();
	
	echo "<table id=\"id_para_comp\" class=\"w3-table-all w3-small\">";
	
	echo "<tr class=\"w3-cyan\">";
		echo "<th>Select</th>";
		echo "<th style=\"display:none;\">Code</th>";
		echo "<th>Name</th>";		
	echo "</tr>";
	
	foreach ($result as $row)
	{
		echo "<tr>";
		echo "<td><input class=\"w3-check\" type=\"checkbox\"></td>";
		echo "<td style=\"display:none;\">".$row->no."</td>";
		echo "<td>".$row->description."</td>";		
		echo "</tr>";
	}
	
	echo "</table>";
			
?>