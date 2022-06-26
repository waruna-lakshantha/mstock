<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
	
	<div class="w3-row-padding">
		
		<h3>Stock Balance</h3>
	
		<div class="w3-col m12 w3-responsive">
			<?php 	
				
				$template = array(
						'table_open'            => '<table id="tblStockBal" class="w3-table-all">',
						
						'thead_open'            => '<thead>',
						'thead_close'           => '</thead>',			
						
						'heading_row_start'     => '<tr class="w3-cyan">',
						'heading_row_end'       => '</tr>',
						'heading_cell_start'    => '<th>',
						'heading_cell_end'      => '</th>',
						
						'tbody_open'            => '<tbody>',
						'tbody_close'           => '</tbody>',
						
						'row_start'             => '<tr>',
						'row_end'               => '</tr>',
						'cell_start'            => '<td>',
						'cell_end'              => '</td>',
						
						'row_alt_start'         => '<tr>',
						'row_alt_end'           => '</tr>',
						'cell_alt_start'        => '<td>',
						'cell_alt_end'          => '</td>',
						
						'table_close'           => '</table>'
				);
				
				$this->table->set_template($template);
				echo $this->table->generate($this->store->read_com_loc_wise_stock_balance_all());
			?>			 	 
			 
		</div>
	</div>    
	
	<br>
	
	<script type="text/javascript">
	    var table = document.getElementById('tblStockBal');
	    
	    for (var i = 0, row; row = table.rows[i]; i++)
	    {
	    	table.rows[i].cells[0].style.display = "none";
	    	table.rows[i].cells[3].style.display = "none";
	        table.rows[i].cells[5].style.display = "none";
	        table.rows[i].cells[8].style.textAlign = 'right';
	    }  
	</script>

<div id="idmessage" class="w3-panel w3-red w3-display-container w3-round w3-card-4 w3-animate-bottom w3-hide">
  <span onclick="this.parentElement.style.display='none'"
  class="w3-button w3-red w3-large w3-display-topright">&times;</span>
  <h3 id="msgHed"></h3>
  <p id="msgDes"></p>
</div>
