<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	$template = array(
			'table_open'            => '<table id="tblItem" class="w3-table-all w3-tiny">',
			
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
?>

<div class="w3-panel w3-card-4 w3-light-grey">

	<br>
	
	<?php 
		if (isset($error)) {
			echo "<ul>";
			foreach ($error as $item => $value):
				echo "<li>".$item." : ". $value."</li>";
			endforeach;
			echo "</ul>";
		}
	?>	
	
	<?php 
		if (isset($upload_data)) {
			echo "<h3>Your file was successfully uploaded!</h3>";
			echo "<ul>";
			foreach ($upload_data as $item => $value):
			if ($item !== 'file_path' && $item !== 'full_path'){
				echo "<li>".$item." : ". $value."</li>";
			}				
			endforeach;
			echo "</ul>";
		}
	?>		

	<div class="w3-row-padding">		
		
		<div class="w3-row-padding">
			<div class="w3-col m5">
  				<input class="w3-input w3-border w3-padding" type="text" placeholder="Search for codes.." id="searchCode" onkeyup="searchByCode()">			
			</div>
			<div class="w3-col m5">
				<input class="w3-input w3-border w3-padding" type="text" placeholder="Search for Description.." id="searchDes" onkeyup="searchByDes()">	
			</div>
			<div class="w3-col m2">
				<button id="btn_add_item" class="w3-button w3-block w3-green" onclick="New_Item()">New</button>
			</div>			
		</div>	
		
		<br>	
	
		<div class="w3-col m12 w3-responsive">

			<?php 
			
				/*no				0
				itemcode			1
				Description			2
				Uom					3
				catno				4
				isstockmaintain		5
				isactive			6
				Upload Image		7
				Edit				8
				isasset				9
				isservice			10
				itemcode			11
				whcode				12
				minlevel			13
				maxlevel			14
				rol					15
				roq					16
				lt					17
				shp					18*/
			
				$this->table->set_template($template);
				
				$query_dtl = $this->item->read_item_all();
				
				echo $this->table->generate($query_dtl);	
			?>

        </div>
	</div>
	
	<br>	
	
</div>	

<?php 
	/*itemcode
	description
	uom
	catno
	isstockmaintain
	isactive
	isasset
	isservice*/
?>

  <div id="id_model_item" class="w3-modal">
    <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="width:80%">

      <header class="w3-container w3-teal"> 
        <span onclick="document.getElementById('id_model_item').style.display='none'" class="w3-button w3-xlarge w3-hover-red w3-display-topright" title="Close Modal">&times;</span>        
      	<h2>Item</h2>
      </header>

	<div class="w3-container">
		<div class="w3-row-padding w3-responsive">
			<div class="w3-col m12 w3-responsive">
	          <input type="hidden" id="id_no" value="0">	          
	         </div>
        	<div class="w3-col m12 w3-responsive">
        		<label><b>Item Code</b></label>
          		<input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Enter Item Code" id="id_code" disabled>
          	</div>
          	<div class="w3-col m12 w3-responsive">
				<label><b>Description</b></label>
				<input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Enter Description" id="id_des" required> 
			</div>
			<div class="w3-col m12 w3-responsive">         
			  	<label><b>UOM</b></label>
				<select id="id_uom" class="w3-select w3-border w3-margin-bottom">
					<?php 					
						$this->load->view('uom_option_list');
					?>													
				</select>
			</div>
			<div class="w3-col m12 w3-responsive"> 
				<label><b>Category</b></label>
				<select id="id_item_cat" class="w3-select w3-border w3-margin-bottom">
					<?php 					
						$this->load->view('item_cat_option_list');
					?>													
				</select>
			</div>
			<div class="w3-col m12 w3-responsive"> 
				<label><b>Wherehouse</b></label>
				<select id="id_wherehouse" class="w3-select w3-border w3-margin-bottom">
					<?php 					
						$this->load->view('wherehouse_option_list');
					?>													
				</select>
			</div>			
		</div>	
			
		<div class="w3-row-padding w3-responsive">
			<div class="w3-col m3">
				<input class="w3-check w3-margin-bottom" type="checkbox" id="id_stock">
				<label><b>Maintain Stock</b></label>
			</div>
			<div class="w3-col m3">
				<input class="w3-check w3-margin-bottom" type="checkbox" id="id_asset">
				<label><b>Asset</b></label>
			</div>
			<div class="w3-col m3">			
				<input class="w3-check w3-margin-bottom" type="checkbox" id="id_service">
				<label><b>Service Item</b></label>
			</div>
			<div class="w3-col m3">			
				<input class="w3-check w3-margin-bottom" type="checkbox" checked="checked" id="id_active">
				<label><b>Active Item</b></label>
			</div>          	
        </div>
        
		<div class="w3-row-padding w3-responsive">
			<div class="w3-col m2">
				<label><b>MIN Level</b></label>
				<input class="w3-input w3-border w3-margin-bottom" type="number" id="id_min_level">
			</div>
			<div class="w3-col m2">
				<label><b>MAX Level</b></label>
				<input class="w3-input w3-border w3-margin-bottom" type="number" id="id_max_level">
			</div>
			<div class="w3-col m2">			
				<label><b>ROL</b></label>
				<input class="w3-input w3-border w3-margin-bottom" type="number" id="id_rol">
			</div>
			<div class="w3-col m2">			
				<label><b>ROQ</b></label>
				<input class="w3-input w3-border w3-margin-bottom" type="number" id="id_roq">
			</div> 
			<div class="w3-col m2">			
				<label><b>LT</b></label>
				<input class="w3-input w3-border w3-margin-bottom" type="number" id="id_lt">
			</div>
			<div class="w3-col m2">			
				<label><b>SHP</b></label>
				<input class="w3-input w3-border w3-margin-bottom" type="number" id="id_shp">
			</div> 
        </div>        
        
        <div class="w3-row-padding">
        	<button id="btn_edit_item" class="w3-button w3-block w3-green w3-section w3-padding" onclick="update_Item()">Save</button>
        </div>

      <div class="w3-container w3-border-top w3-padding-16">
        <button onclick="document.getElementById('id_model_item').style.display='none'" type="button" class="w3-button w3-red">Cancel</button>
      </div>

	</div>

    </div>
  </div>

<script type="text/javascript">

	format_table();
		
	function format_table(){
	    var table = document.getElementById("tblItem");
	
	    for (var i = 0, row; row = table.rows[i]; i++)
	    {
	    	table.rows[i].cells[0].style.display = "none";
	    	table.rows[i].cells[4].style.display = "none";
	    	table.rows[i].cells[9].style.display = "none";
	    	table.rows[i].cells[10].style.display = "none";

			if (i > 0){
				var up = "<form method=\"post\" action=\"<?php echo base_url();?>index.php/upload/do_upload/" + table.rows[i].cells[1].innerHTML + "\" enctype=\"multipart/form-data\"/>" +		
						"<input type=\"file\" name=\"userfile\" size=\"20\" />" +
						"<input class=\"w3-button w3-border w3-teal w3-ripple w3-tiny\" type=\"submit\" value=\"upload\" />" +
						"</form>";

				table.rows[i].cells[7].innerHTML = up;
				
				var itmno = table.rows[i].cells[0].innerHTML;
				
				var imdrop = "<div class=\"w3-dropdown-hover\" onmouseover=\"load_image(" + itmno + ")\"> " +
					"<p>" + table.rows[i].cells[1].innerHTML + "</p> " +
					"<div id=\"idimage_" + itmno + "\" class=\"w3-dropdown-content w3-card-4\"> " +
					"</div> " +
				"</div>";

				table.rows[i].cells[1].innerHTML = imdrop;

				var ed = "<button onclick=\"view_edit(" + i + ")\" class=\"w3-button w3-ripple w3-blue\"><i class=\"fa fa-pencil-square-o\" aria-hidden=\"true\"></i> Edit</button>";
				
				table.rows[i].cells[8].innerHTML = ed;
			}	   
	    	
	    	//table.rows[i].cells[13].contentEditable = "true";
	    }	    
	}

	function New_Item(){
		$('#id_no').val("0");
		$('#id_code').val('');	
		$('#id_des').val('');	
		$('#id_uom').val('');
		$('#id_item_cat').val('');	

		$("#id_stock").attr("checked", true);
		$("#id_asset").attr("checked", false);
		$("#id_service").attr("checked", false);
		$("#id_active").attr("checked", true);

		$('#id_wherehouse').val('');
		$('#id_min_level').val("0");
		$('#id_max_level').val("0");
		$('#id_rol').val("0");
		$('#id_roq').val("0");
		$('#id_lt').val("0");
		$('#id_shp').val("0");

		document.getElementById("id_code").disabled = false;

		document.getElementById('id_model_item').style.display='block';
	}
	
	function view_edit(_i){
		//alert(_item);
		
		document.getElementById("id_code").disabled = true;
		
		var table = document.getElementById("tblItem");

		/*no				0
		itemcode			1
		Description			2
		Uom					3
		catno				4
		isstockmaintain		5
		isactive			6
		Upload Image		7
		Edit				8
		isasset				9
		isservice			10
		itemcode			11
		whcode				12
		minlevel			13
		maxlevel			14
		rol					15
		roq					16
		lt					17
		shp					18*/		
		
    	var ar_no = table.rows[_i].cells[0].innerHTML;
    	var ar_itemcode = table.rows[_i].cells[11].innerHTML;
    	var ar_Description = table.rows[_i].cells[2].innerHTML;
    	var ar_Uom = table.rows[_i].cells[3].innerHTML;
    	var ar_catno = table.rows[_i].cells[4].innerHTML;
    	var ar_isstockmaintain = table.rows[_i].cells[5].innerHTML;
    	var ar_isactive = table.rows[_i].cells[6].innerHTML;
		//Upload Image		7
		//Edit				8
		var ar_isasset = table.rows[_i].cells[9].innerHTML;
		var ar_isservice = table.rows[_i].cells[10].innerHTML;
		var ar_whcode = table.rows[_i].cells[12].innerHTML;
		var ar_minlevel = table.rows[_i].cells[13].innerHTML;
		var ar_maxlevel = table.rows[_i].cells[14].innerHTML;
		var ar_rol = table.rows[_i].cells[15].innerHTML;
		var ar_roq = table.rows[_i].cells[16].innerHTML;
		var ar_lt = table.rows[_i].cells[17].innerHTML;
		var ar_shp = table.rows[_i].cells[18].innerHTML;

		<?php 
			/*id_no
			id_code
			id_des
			id_uom
			id_item_cat
			id_stock
			id_asset
			id_service
			id_active
			id_wherehouse
			id_min_level
			id_max_level
			id_rol
			id_roq
			id_lt
			id_shp*/
		?>

		$('#id_no').val(ar_no);	
		$('#id_code').val(ar_itemcode);	
		$('#id_des').val(ar_Description);	
		$('#id_uom').val(ar_Uom);
		$('#id_item_cat').val(ar_catno);

		if(ar_isstockmaintain == "1"){
			$("#id_stock").attr("checked", true);
		}else{
			$("#id_stock").attr("checked", false);
		}

		if(ar_isasset == "1"){
			$("#id_asset").attr("checked", true);
		}else{
			$("#id_asset").attr("checked", false);
		}	

		if(ar_isservice == "1"){
			$("#id_service").attr("checked", true);
		}else{
			$("#id_service").attr("checked", false);
		}

		if(ar_isactive == "1"){
			$("#id_active").attr("checked", true);
		}else{
			$("#id_active").attr("checked", false);
		}

		$('#id_wherehouse').val(ar_whcode);
		$('#id_min_level').val(ar_minlevel);
		$('#id_max_level').val(ar_maxlevel);
		$('#id_rol').val(ar_rol);
		$('#id_roq').val(ar_roq);
		$('#id_lt').val(ar_lt);
		$('#id_shp').val(ar_shp);
		
		document.getElementById('id_model_item').style.display='block';
	}

	function load_image(imgid){
		var divid = "#idimage_" + imgid;
		//alert(divid);

		var im = "<img src=\"<?php echo base_url();?>/uploads/" + imgid +".jpg\" class=\"w3-border w3-padding\" alt=\"" + imgid + "\">";

		$(divid).html(im);
	}
	
    function update_Item() {

    	$("#btn_edit_item").hide();
        
        var _ReqHdrData = new Array();

		<?php 
			/*id_no
			id_code
			id_des
			id_uom
			id_item_cat
			id_stock
			id_asset
			id_service
			id_active
			id_wherehouse
			id_min_level
			id_max_level
			id_rol
			id_roq
			id_lt
			id_shp*/
		?>

		var _id_no = $('#id_no').val();
		var _id_code = $('#id_code').val();
		var _id_des = $('#id_des').val();
		var _id_uom = $('#id_uom').val();
		var _id_item_cat = $('#id_item_cat').val();

		if(_id_no == 'undefined' || _id_no == null || _id_no.length == 0){
			_id_no = 0;
		}

		//alert(_id_no);		

		if(_id_code == 'undefined' || _id_code == null || _id_code.length == 0){
			alert('Item Code Required!');
			return;
		}

		if(_id_des == 'undefined' || _id_des == null || _id_des.length == 0){
			alert('Item Description Required!');
			return;
		}		
		
		var _id_stock = 0;
		
		if($("#id_stock").is(':checked')){
			_id_stock = 1;
		} 

		var _id_asset = 0;

		if($('#id_asset').is(':checked')){
			_id_asset = 1;
		}

		var _id_service = 0;

		if($('#id_service').is(':checked')){
			_id_service = 1;
		}

		var _id_active = 0;

		if($('#id_active').is(':checked')){
			_id_active = 1;
		}
		
		var _id_wherehouse = $('#id_wherehouse').val();
		var _id_min_level = $('#id_min_level').val();
		var _id_max_level = $('#id_max_level').val();
		var _id_rol = $('#id_rol').val();
		var _id_roq = $('#id_roq').val();
		var _id_lt = $('#id_lt').val();
		var _id_shp = $('#id_shp').val(); 

		//alert(escape(_id_des));
		
        _ReqHdrData[0] = { "item_no": _id_no, "des": _id_des, "uom": _id_uom, "item_cat": _id_item_cat, "stock": _id_stock, 
                			"asset": _id_asset, "service": _id_service, "active": _id_active, "wherehouse": _id_wherehouse, 
                			"min_level": _id_min_level, "max_level": _id_max_level, "rol": _id_rol,
                			"roq": _id_roq, "lt": _id_lt, "shp": _id_shp, "code": _id_code,
                			"insertuser": "<?php echo $this->session->userdata['logged_in']['username'];?>" };
        
        var ReqHdrData = $.toJSON(_ReqHdrData);

        //alert(ReqHdrData);
		
        <?php echo "var url = '". base_url()."index.php/transaction/updateitem';"; ?>

		$.ajax({
			type: "POST",
			url: url,
			data: ({pHdrData: ReqHdrData}),
			dataType: 'json',
			cache: false,
			success: function(data){
                //alert (data[1]);

                if (parseInt(data[0]) == 1){  

                	New_Item();
                	
                	alert('Successfuly Updated! ' + data[1]);
                	
                }else{

                	alert('Error! ' + data[1]);
                	
                }
                
			}
		});	

		$("#btn_edit_item").show();	
        
    }

    function searchByCode() {
    	  var input, filter, table, tr, td, i;
    	  input = document.getElementById("searchCode");
    	  filter = input.value.toUpperCase();
    	  table = document.getElementById("tblItem");
    	  tr = table.getElementsByTagName("tr");
    	  for (i = 0; i < tr.length; i++) {
    	    td = tr[i].getElementsByTagName("td")[11];
    	    if (td) {
    	      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
    	        tr[i].style.display = "";
    	      } else {
    	        tr[i].style.display = "none";
    	      }
    	    }
    	  }
    	}  

    function searchByDes() {
  	  var input, filter, table, tr, td, i;
  	  input = document.getElementById("searchDes");
  	  filter = input.value.toUpperCase();
  	  table = document.getElementById("tblItem");
  	  tr = table.getElementsByTagName("tr");
  	  for (i = 0; i < tr.length; i++) {
  	    td = tr[i].getElementsByTagName("td")[2];
  	    if (td) {
  	      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
  	        tr[i].style.display = "";
  	      } else {
  	        tr[i].style.display = "none";
  	      }
  	    }
  	  }
  	}  

</script>