<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	/*if (isset($machine_list)) {
		$arrlength = count($machine_list);
		
		for($x = 0; $x < $arrlength; $x++) {
			
			echo "<option value=\"".$machine_list[$x]->no."~".$machine_list[$x]->machineno."\">".$machine_list[$x]->machineno."</option>";		
			
		}
	}*/

    $html_out = "";
    $location = $this->location->read_location();

    $i = 0;

    foreach ($location as $row)
    {
        if ($i == 0){
            $html_out .= "<option class=\"w3-red\" value=\"\" disabled selected>".$row->location."</option>";
        }else{
            $html_out .= "<option class=\"w3-red\" value=\"\" disabled>".$row->location."</option>";
        }    

        $machine_list = $this->machine->read_machine_by_location($row->no);

        foreach ($machine_list as $row_m)
        {
            $html_out .= "<option value=\"".$row_m->no."~".$row_m->no."\">".$row_m->machineno." (".$row->location.")</option>";	
        }

        $i++;
    }

    echo $html_out;
?>