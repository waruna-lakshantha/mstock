<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	$html_out = "";
	$location = $this->location->read_location();
	
	$i = 0;
	
	$html_out .= "<div class=\"w3-bar w3-black\">";
	
	foreach ($location as $row)
	{
		if($i === 0){
			$html_out .= "<button class=\"w3-bar-item w3-button tablink w3-red m\" onclick=\"openLoc(event,'loc_".$row->no."')\">".$row->location."</button>";
		}else{
			$html_out .= "<button class=\"w3-bar-item w3-button tablink m\" onclick=\"openLoc(event,'loc_".$row->no."')\">".$row->location."</button>";
		}
		
		$i++;
	}
	
	$html_out .= "</div>";
	
	$i = 0;
	
	foreach ($location as $row_l)
	{
		if($i === 0){
			$html_out .= "<div id=\"loc_".$row_l->no."\" class=\"w3-container w3-border location\">";
		}else{
			$html_out .= "<div id=\"loc_".$row_l->no."\" class=\"w3-container w3-border location\" style=\"display:none\">";
		}
		
		$html_out .= "<table id=\"id_loc_tbl_".$row_l->no."\" class=\"w3-table-all w3-small\">";
		
		$html_out .= "<tr class=\"w3-cyan\">";
		$html_out .= "<th>Select</th>";
		$html_out .= "<th style=\"display:none;\">Code</th>";
		$html_out .= "<th>Name</th>";
		$html_out .= "<th>Location</th>";
		$html_out .= "</tr>";
		
		$machine_list = $this->machine->read_machine_by_location($row_l->no);
		
		foreach ($machine_list as $row_m)
		{
			$html_out .= "<tr>";
			$html_out .= "<td><input class=\"w3-check\" type=\"checkbox\"></td>";
			$html_out .= "<td style=\"display:none;\">".$row_m->no."</td>";
			$html_out .= "<td>".$row_m->machineno."</td>";
			$html_out .= "<td>".$row_l->location."</td>";
			$html_out .= "</tr>";
		}

		$html_out .= "</table>";

		$html_out .= "<br>";
		
		$html_out .= "</div>";	

		$i++;

	}

	$html_out .= "<script>";
	
	$html_out .= "var machine_tbl_list = [";
	
	$loc_count = count($location);
	
	$cur_cnt = 1;
	
	foreach ($location as $row_t)
	{
		if ($cur_cnt < $loc_count){
			$html_out .= "\"id_loc_tbl_".$row_t->no."\",";
		}else{
			$html_out .= "\"id_loc_tbl_".$row_t->no."\"";
		}		
		
		$cur_cnt++;
	}
	
	$html_out .= "];";
	
	$html_out .= "function storeMachinePara() {";
		$html_out .= "var _TableData = new Array();";
		
		$html_out .= "var ai = 0;";
		$html_out .= "var rowi = 0;";
		
		$html_out .= "var Len = machine_tbl_list.length;";
		
		$html_out .= "for (i = 0; i < Len; i++) {";
						
			$html_out .= "$('#' + machine_tbl_list[i] + ' tr').each(function(row, tr){";
				
				$html_out .= "if(rowi > 0){";
					$html_out .= "var issel = $(tr).find('td:eq(0)').find('input').is(':checked');";
					$html_out .= "var code = $(tr).find('td:eq(1)').text();";
					
					$html_out .= "if(issel){";
						$html_out .= "if(code != undefined && code != null && code.length > 0)";
							$html_out .= "{";
							$html_out .= "_TableData[ai]={";
								$html_out .= "\"machineno\" : code";
							$html_out .= "};";
							$html_out .= "ai++;";
						$html_out .= "}";
						$html_out .= "}";
					$html_out .= "}";
				
				$html_out .= "rowi++;";
				
			$html_out .= "});";
		$html_out .= "}";
			
		$html_out .= "return _TableData;";
			
	$html_out .= "}";
	
	$html_out .= "function openLoc(evt, locName) {";
		$html_out .= "var i, x, tablinks;";
		$html_out .= "x = document.getElementsByClassName(\"location\");";
		$html_out .= "for (i = 0; i < x.length; i++) {";
			$html_out .= "x[i].style.display = \"none\";";
		$html_out .= "}";
		$html_out .= "tablinks = document.getElementsByClassName(\"m\");";
		$html_out .= "for (i = 0; i < x.length; i++) {";
			$html_out .= "tablinks[i].className = tablinks[i].className.replace(\" w3-red\", \" \");";
		$html_out .= "}";
		$html_out .= "document.getElementById(locName).style.display = \"block\";";
		$html_out .= "evt.currentTarget.className += \" w3-red\";";
	$html_out .= "}";
	$html_out .= "</script>";

	echo $html_out;
		
?>