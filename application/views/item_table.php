<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	$item = $this->item->read_item();
	
	echo "<table id=\"id_para_item\" class=\"w3-table-all w3-small\">";
	
	echo "<tr class=\"w3-cyan\">";
	echo "<th>Select</th>";
	echo "<th style=\"display:none;\">No</th>";
	echo "<th>Code</th>";
	echo "<th>Name</th>";
	echo "</tr>";
	
	foreach ($item as $row)
	{
		echo "<tr>";
		echo "<td><input class=\"w3-check\" type=\"checkbox\"></td>";
		echo "<td style=\"display:none;\">".$row->no."</td>";
		echo "<td>".$row->itemcode."</td>";
		echo "<td>".$row->description."</td>";
		echo "</tr>";
	}
	
	echo "</table>";
	
	echo "<script type=\"text/javascript\">";
		echo "function filterItemCode() {";
			echo "var input, filter, table, tr, td, i;";
			echo "input = document.getElementById(\"id_search_itm_code\");";
			echo "filter = input.value.toUpperCase();";
			echo "table = document.getElementById(\"id_para_item\");";
			echo "tr = table.getElementsByTagName(\"tr\");";
			echo "for (i = 0; i < tr.length; i++) {";
				echo "td = tr[i].getElementsByTagName(\"td\")[2];";
				echo "if (td) {";
					echo "if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {";
						echo "tr[i].style.display = \"\";";
					echo "} else {";
						echo "tr[i].style.display = \"none\";";
					echo "}";
				echo "}";
			echo "}";
		echo "}";
				
		echo "function filterItemDes() {";
			echo "var input, filter, table, tr, td, i;";
			echo "input = document.getElementById(\"id_search_itm_des\");";
			echo "filter = input.value.toUpperCase();";
			echo "table = document.getElementById(\"id_para_item\");";
			echo "tr = table.getElementsByTagName(\"tr\");";
			echo "for (i = 0; i < tr.length; i++) {";
				echo "td = tr[i].getElementsByTagName(\"td\")[3];";
				echo "if (td) {";
					echo "if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {";
						echo "tr[i].style.display = \"\";";
					echo "} else {";
						echo "tr[i].style.display = \"none\";";
					echo "}";
				echo "}";
			echo "}";
		echo "}";
	echo "</script>";
	
?>