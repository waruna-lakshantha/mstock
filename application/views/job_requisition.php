<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="w3-panel w3-card-4 w3-light-grey">

	<br>

	<div class="w3-row-padding">
		<div class="w3-col m3">
			<label>Date</label>
			<input id="idgrn_date" class="w3-input" type="text" disabled>
		</div>	
		<div class="w3-col m3">
			<label>Job No</label>
			<input id="idjob_no" class="w3-input" type="text" disabled>	
		</div>	
		<div class="w3-col m3">
			<p></p>
		</div>
		<div class="w3-col m3">
			<label>Enter Doc. No</label>
			<div class="w3-bar">
			  <input id="id_reprint_no" class="w3-bar-item w3-input w3-border" type="text">
			  <button class="w3-bar-item w3-button w3-teal" onclick="Reprint_Doc(11, '<?php echo base_url();?>')"><i class="fa fa-print" aria-hidden="true"></i> Re-Print</button>
			</div>
		</div>
	</div>
	
	<br>
	
	<div class="w3-row-padding">
		<div class="w3-col m2">
			<label>Type of Job</label>
			<select id="id_job_type" class="w3-select">
                <?php $this->load->view('jobtype_option_list');?>                
			</select>
		</div>
		<div class="w3-col m2">
	    	<label>To be Completed</label>
		    <input id="idreq_date" class="w3-input" type="date">
		</div>
		<div class="w3-col m4">
			<label>Company</label>
			<select id="id_company" class="w3-select">
				<?php $this->load->view('company_option_list'); ?>
			</select>
		</div>		
		<div class="w3-col m4">
			<label>Department</label>
			<select id="id_dept" class="w3-select">
				<?php 					
					$this->load->view('department_option_list_user');
				?>
			</select>
		</div>
	</div>	
	
	<br>

    <div class="w3-row-padding">
		<div class="w3-col m12">
			<label>Machine</label>
			<select id="id_machine" class="w3-select">
				<?php 					
					$this->load->view('machine_option_list');											
				?>													
			</select>	
		</div>
	</div>	

    <br>	

	<div class="w3-row-padding">
		<div class="w3-col m10">
			<label>Job Description</label>
			<textarea id="idgrn_freetxt" class="w3-input"></textarea>
		</div>
		<div class="w3-col m2">
			<br>
			<button id="idupdatebtm" class="w3-btn w3-green w3-right" class="w3-btn" onclick="update_Job()"><i class="fa fa-floppy-o" aria-hidden="true"></i> Update</button>
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

	n =  new Date();
	y = n.getFullYear();
	m = n.getMonth() + 1;
	d = n.getDate();
	$("#idgrn_date").val(d + "/" + m + "/" + y);

    function update_Job(){

    	$("#idupdatebtm").hide();
    	
        var _ReqHdrData = new Array();

		var _jobtypeno = $('#id_job_type').val();
		var _companyno = $('#id_company').val();
		var _departmentno = $('#id_dept').val();
		var _jobdescription = $('#idgrn_freetxt').val();
        var _machineno = $('#id_machine').val();

	    if(($.trim(_machineno)).length == 0 || ($.trim(_machineno)).length == 0)
	    {
        	$("#idmessage").removeClass("w3-red");
        	$("#idmessage").removeClass("w3-yellow");
        	$("#idmessage").removeClass("w3-green");

        	$("#idmessage").addClass("w3-yellow");
        	$("#msgHed").text("Required");
        	$("#msgDes").text("Machine Required!");
        	$("#idmessage").removeClass("w3-hide");
        	document.getElementById('idmessage').style.display = 'block';  			

            $('html, body').animate({
                scrollTop: $("#idmessage").offset().top
            }, 2000);

            $("#idupdatebtm").show(); 
            
			return;
	    }	      

        if (_jobdescription.length == 0)
        {
        	$("#idmessage").removeClass("w3-red");
        	$("#idmessage").removeClass("w3-yellow");
        	$("#idmessage").removeClass("w3-green");

        	$("#idmessage").addClass("w3-yellow");
        	$("#msgHed").text("Required");
        	$("#msgDes").text("Job Description Required!");
        	$("#idmessage").removeClass("w3-hide");
        	document.getElementById('idmessage').style.display = 'block';

        	$("#idupdatebtm").show();            
            return;
        }

        if (_jobtypeno == null || _jobtypeno.length == 0 || _jobtypeno == undefined)
        {
        	$("#idmessage").removeClass("w3-red");
        	$("#idmessage").removeClass("w3-yellow");
        	$("#idmessage").removeClass("w3-green");

        	$("#idmessage").addClass("w3-yellow");
        	$("#msgHed").text("Required");
        	$("#msgDes").text("Job Type Required!");
        	$("#idmessage").removeClass("w3-hide");
        	document.getElementById('idmessage').style.display = 'block';

        	$("#idupdatebtm").show();           
            return;
        }

        if (_companyno == null || _companyno.length == 0 || _companyno == undefined)
        {
        	$("#idmessage").removeClass("w3-red");
        	$("#idmessage").removeClass("w3-yellow");
        	$("#idmessage").removeClass("w3-green");

        	$("#idmessage").addClass("w3-yellow");
        	$("#msgHed").text("Required");
        	$("#msgDes").text("Company Required!");
        	$("#idmessage").removeClass("w3-hide");
        	document.getElementById('idmessage').style.display = 'block';

        	$("#idupdatebtm").show();           
            return;
        } 

        if (_departmentno == null || _departmentno.length == 0 || _departmentno == undefined)
        {
        	$("#idmessage").removeClass("w3-red");
        	$("#idmessage").removeClass("w3-yellow");
        	$("#idmessage").removeClass("w3-green");

        	$("#idmessage").addClass("w3-yellow");
        	$("#msgHed").text("Required");
        	$("#msgDes").text("Department Required!");
        	$("#idmessage").removeClass("w3-hide");
        	document.getElementById('idmessage').style.display = 'block';

        	$("#idupdatebtm").show();            
            return;
        }         
        
        if (_machineno == null || _machineno.length == 0 || _departmentno == undefined)
        {
        	$("#idmessage").removeClass("w3-red");
        	$("#idmessage").removeClass("w3-yellow");
        	$("#idmessage").removeClass("w3-green");

        	$("#idmessage").addClass("w3-yellow");
        	$("#msgHed").text("Required");
        	$("#msgDes").text("Machine No Required!");
        	$("#idmessage").removeClass("w3-hide");
        	document.getElementById('idmessage').style.display = 'block';

        	$("#idupdatebtm").show();            
            return;
        }       
    
        var _company_split = _companyno.split("~");

        var _datetobecompleted = get_json_date($('#idreq_date').val()); 

        _ReqHdrData[0] = { "jobtypeno": _jobtypeno, "companyno": _company_split[0], "departmentno": _departmentno, "jobdescription": _jobdescription,  "insertuser": "<?php echo $this->session->userdata['logged_in']['username'];?>", "datetobecompleted": _datetobecompleted, "machineno": _machineno };        
        
        var ReqHdrData = JSON.stringify(_ReqHdrData);

        //alert(ReqHdrData);

        <?php echo "var url = '". base_url()."index.php/transaction/updatejob';"; ?>

		$.ajax({
			type: "POST",
			url: url,
			data: ({pHdrData: ReqHdrData}),
			dataType: 'json',
			cache: false,
			success: function(data){
                //alert (data[1]);

                if (parseInt(data[0]) == 1){                	
                	$("#idmessage").removeClass("w3-red");
                	$("#idmessage").removeClass("w3-yellow");
                	$("#idmessage").removeClass("w3-green");

                	$("#idmessage").addClass("w3-green");
                	$("#msgHed").text("Ref No:" + data[1]);
                	$("#msgDes").text("Successfuly Updated!");
                	$("#idmessage").removeClass("w3-hide");
                	document.getElementById('idmessage').style.display = 'block';

                	clear_Form();

                	$("#idupdatebtm").show();

                	var print_url = '<?php echo base_url();?>index.php/print/11/'+data[3];
                	
                	var win = window.open(print_url, '_blank');
                	win.focus();              	
                	
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
                }
                
			}
		});
        
    }

    function clear_Form(){
	    $("#idgrn_freetxt").val('');	    	
    }    

</script>
<script src="<?php echo base_url();?>assets/js/printdoc.js"></script> 