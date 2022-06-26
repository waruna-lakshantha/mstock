<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	/*if (isset($item_list)) {
		$arrlength = count($item_list);
		
		for($x = 0; $x < $arrlength; $x++) {
			
			echo "<option value=\"".$item_list[$x]->no."~".$item_list[$x]->itemcode."\">".$item_list[$x]->description."</option>";			
			
		}
	}*/
	
	$html_out = "";
	$item = $this->item->read_item();	
	
	foreach ($item as $row)
	{
		
		$whouse = $this->wherehouse->read_wherehouse_by_no($row->whcode);
		
		$html_out .= "<option value=\"".$row->no."~".$row->itemcode."~".$row->uom."~".$whouse->whcode."~".$row->whcode."\">".$row->description." - ".$row->itemcode."</option>";
	}
	
	echo $html_out;
	
?>