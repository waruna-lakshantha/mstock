<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="w3-panel w3-card-4 w3-light-grey">

	<br>
	
	<div class="w3-row-padding">
		<div class="w3-col m4">
	    	<label>User Name</label>
		    <input id="id_user_name" class="w3-input" type="text" onchange="get_permission('<?php echo base_url()?>',this.value)">	
		</div>	
		<div class="w3-col m4">
	    	<label>Password</label>
		    <input id="id_password" class="w3-input" type="password">	
		</div>
		<div class="w3-col m4">
	    	<label>E Mail</label>
		    <input id="id_email" class="w3-input" type="email">
		</div>			
	</div>	
	
	<div class="w3-row-padding">
		<div class="w3-col m4">
			<label>Company</label>
			<select id="id_company" class="w3-select">
				<?php 					
					if (isset($company_list)) {
						$data['company_list'] = $company_list;
						$this->load->view('company_option_list_no_only', $data);
					}						
				?>													
			</select>		
		</div>
		<div class="w3-col m4">
			<label>Department</label>
			<select id="id_dept" class="w3-select">
				<?php 					
					$this->load->view('department_option_list');
				?>													
			</select>	
		</div>	
	</div>
	
	<br>
	
</div>		

<div class="w3-panel w3-card-4 w3-light-grey">
	<div class="w3-row-padding">
		<div id="id_permission" class="w3-col m12">
		
		</div>
	</div>
</div>	

<div class="w3-panel w3-card-4 w3-light-grey">

	<div class="w3-row-padding">
		<div class="w3-col m8">
			<p></p>
		</div>	
		
		<div class="w3-col m4">
			<br>
			<button id="idupdatebtm" class="w3-btn w3-green w3-right" class="w3-btn" onclick="update_Permision()"><i class="fa fa-floppy-o" aria-hidden="true"></i> Update</button>
		</div>
	</div>	

    <br>     

</div>

<div id="idmessage" class="w3-panel w3-red w3-display-container w3-round w3-card-4 w3-animate-bottom w3-hide">
  <span onclick="this.parentElement.style.display='none'"
  class="w3-button w3-red w3-large w3-display-topright">&times;</span>
  <h3 id="msgHed"></h3>
  <p id="msgDes"></p>
</div> 

<script type="text/javascript">

	function get_permission(path, userid){	
			
		var sys = 'MS';
        var u_path = path + 'index.php/read_user_permission/'+userid+'/'+sys; 
        
        //alert(u_path);
        
        $.getJSON(u_path, function(data, status){        

			//alert(data[0]);
            
    	    if (status == 'success'){
    	    	$("#id_company").val(data[0]).change();
    	    	$("#id_dept").val(data[1]).change();
    	    	$("#id_email").val(data[2]);
    		    $("#id_permission").html(data[3]);
    	    }else{
    		    $("#id_permission").html('0');
    	    }
    	
        }) 		
	}

    function update_Permision(){

    	$("#idupdatebtm").hide();
    	
        var ReqHdrData = JSON.stringify(storeHdrDet());
        var TableData = JSON.stringify(storeTableValue());

        <?php echo "var url = '". base_url()."index.php/transaction/updateuser';"; ?>

        //alert (JSON.stringify(TableData));

		$.ajax({
			type: "POST",
			url: url,
			data: ({pHdrData: ReqHdrData, pTableData: TableData}),
			dataType: 'json',
			cache: false,
			success: function(data){
                //alert (data[1]);

                if (parseInt(data[0]) == 1){                	
                	$("#idmessage").removeClass("w3-red");
                	$("#idmessage").removeClass("w3-yellow");
                	$("#idmessage").removeClass("w3-green");

                	$("#idmessage").addClass("w3-green");
                	$("#msgDes").text("Successfuly Updated!");
                	$("#idmessage").removeClass("w3-hide");
                	document.getElementById('idmessage').style.display = 'block';

                	$("#idupdatebtm").show();
                	return;               	                
                	
                }else{
                	$("#idmessage").removeClass("w3-red");
                	$("#idmessage").removeClass("w3-yellow");
                	$("#idmessage").removeClass("w3-green");

                	$("#idmessage").addClass("w3-red");
                	$("#msgHed").text("Error");
                	$("#msgDes").text(data[1]);
                	$("#idmessage").removeClass("w3-hide");
                	document.getElementById('idmessage').style.display = 'block';

                	$("#idupdatebtm").show();
                	return;
                }
                
			}
		});

        //alert (TableData);
        
    }

    function clear_Form(){
	    $("#idgrn_qty").val(0);	
	    $("#idgrn_freetxt").val('');
	    $('#idsap_refno').val('');

	    $("#idupdatebtm").show();		
    }

    function storeHdrDet() {
        var _ReqHdrData = new Array();

        var _user_name = $('#id_user_name').val();
        var _password = $('#id_password').val();
        var _email = $('#id_email').val();
        var _company = $('#id_company').val();
        var _dept = $('#id_dept').val();
        var _sys = 'MS';        

        _ReqHdrData[0] = { "user_name": _user_name, "insertuser": "<?php echo $this->session->userdata['logged_in']['username'];?>", "password": _password, "email": _email, "company": _company, "dept": _dept, "sys": _sys };

        return _ReqHdrData;
    }

    function storeTableValue() {
        var _TableData = new Array();

        var i, row = 0, div, a;
        
        div = document.getElementById("id_permission");
       	a = div.getElementsByTagName('input');	

       	//alert(a.length);
		
        for (i = 0; i < a.length; i++) {  
        	if (a[i].checked){

        		_TableData[row]={
        	            "menuid" : a[i].value
        	        }
				
        		row++;
        	}
        }	

        //_TableData.shift();

        //alert(JSON.stringify(_TableData));
        
        return _TableData;

    }
</script>
