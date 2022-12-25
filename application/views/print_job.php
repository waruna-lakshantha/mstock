<?php

	$docno = 0;

	if (isset($doc_no)) {
		$docno = $doc_no;
	}
	
	$job_hdr = $this->job_requisition->read_job_dtl_by_no($docno);
	
	$job_eng = $this->job_requisition->read_job_engineer($docno);	
	
?>

<!DOCTYPE html>
<html>

<head>

	<meta charset="utf-8">
	<title>MRN - <?php if(isset($job_hdr)){ echo $job_hdr->jobno;}?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel = "stylesheet" type = "text/css" 
       href = "<?php echo base_url(); ?>assets/font-awesome-4.3.0/css/font-awesome.min.css">       
    <script src="<?php echo base_url();?>assets/js/jquery-3.1.1.min.js"></script>
    <script src="<?php echo base_url();?>assets/json/jquery.json.min.js"></script>   	

	<style>
			
	    @media print {
			
		    @page :left {
		    margin: 0.5cm;
		    }

		    @page :right {
		    margin: 0.8cm;
		    }	
				
		    #s1 {font-size: 8pt; line-height: 100%; margin:0cm 0cm 0cm 0cm;}

		    #s2 {font-size: 9pt; line-height: 100%; margin:0cm 0cm 0cm 0cm;}

		    #s3 {font-size: 10pt; line-height: 100%; margin:0cm 0cm 0cm 0cm;}
			
		    body {
		        //font: 12pt Georgia, \"Times New Roman\", Times, serif;
		        line-height: 1.5;
                font-size: 10pt; 
                //line-height: 100%;
                margin:0cm 0cm 0cm 0cm;
		        margin-top: 9;
		        margin-bottom: 0;
		        //margin: 1;
		    }

	    }
			
	    table{
	        //table-layout: fixed;
	        //border: 1px solid black;
	        border-collapse: collapse;
	        width: 8.0in;
	        margin:0cm 0.5cm 0cm 1cm;
            font-size: 10pt;
	    }
			
	    td{
	        //border:1px solid black;
	        border-collapse:collapse;
	        overflow: hidden;
	        vertical-align: top;
	        padding: 0px;
            font-size: 10pt;
	    }
     
        .bdr table
        {
            border: 1px solid black; 
            border-collapse:collapse; 
            font-size: 10pt; 
        }

        .bdr th
        {
            font-size:11px;
            border: 1px solid black; 
            border-collapse:collapse;
            padding: 5px; 
            font-size: 10pt; 
        }
     
        .bdr td
        {
            font-size:12px;
            border: 1px solid black;
            border-collapse:collapse;
            padding: 5px; 
            font-size: 10pt;  
        }          
			
	</style>
</head>

<body>

    <table style="width: 8.5in;">
        <tr>
            <td style="text-align: center;" width="100%"><img src="<?php echo base_url();  ?>assets/image/logo.jpg" alt="WPI Logo" style="width:40%;"></td>
        </tr>
        <tr>
            <td style="text-align: center;">425/B/4, Jayagath Mawatha, Kahathuduwa, Polgasowita. Sri Lanka.</td>
        </tr>
        <tr>
            <td style="text-align: center;">Tel. +94773718891. E mail. waruna.lakshantha@gmail.com</td>
        </tr>
        <tr>
            <td style="text-align: center;"><b>Job Requisition Form</b><hr></td>
        </tr>
        
    </table>

    <table style="width: 8.5in;">           
        <tr>
            <td width="10%">Job No</td>
            <td width="5%">:</td>
            <td width="15%"><?php if(isset($job_hdr)){ echo $job_hdr->jobno;}?></td>
            <td width="30%">Type of Job</td>
            <td width="5%">:</td>
            <td width="35%"><?php if(isset($job_hdr)){ echo $job_hdr->jobtype;}?></td>
        </tr>
        <tr>
            <td width="10%">Date</td>
            <td width="5%">:</td>
            <td width="15%"><?php if(isset($job_hdr)){ echo $job_hdr->date;}?></td>
            <td width="30%">Date to be Completed</td>
            <td width="5%">:</td>
            <td width="35%"colspan="4"><?php if(isset($job_hdr)){ echo $job_hdr->datetobecompleted;}?></td>
        </tr>
    </table>
    
    <br>
    <table style="width: 8.5in;">           
        <tr>
            <td width="10%">Company</td>
            <td width="5%">:</td>
            <td width="85%"><?php if(isset($job_hdr)){ echo $job_hdr->company;}?></td>
        </tr>
        <tr>
            <td width="10%">Department</td>
            <td width="5%">:</td>
            <td width="85%"><?php if(isset($job_hdr)){ echo $job_hdr->department;}?></td>
        </tr>
       	<tr>
            <td width="10%">Machine</td>
            <td width="5%">:</td>
            <td width="85%"><?php if(isset($job_hdr)){ echo $job_hdr->machineno;}?></td>
        </tr>
    </table>  
    
    <br>
	<table style="width: 8.5in;">           
        <tr>
            <td width="10%">Description</td>
            <td width="5%">:</td>
            <td width="85%"><?php if(isset($job_hdr)){ echo $job_hdr->jobdescription;}?></td>
        </tr>
    </table>     
    
    <br>
    <table style="width: 8.5in;">
        <tr>
            <td width="10%">Section Head :</td>
            <td width="15%"></td>
            <td width="10%">Department Head :</td>
            <td width="15%"></td>
        </tr>
    </table>
    
    <br>
    <table style="width: 8.5in;">
        <tr>
            <td style="text-align: center;"><hr></td>
        </tr>
        
    </table>
    
    <table style="width: 8.5in;">
        <tr>
            <td width="100%">For the use of engineering dept.</td>
        </tr>        
    </table> 
    
    <br> 
    
	<table style="width: 8.5in;">           
        <tr>
            <td width="35%">Estimated date of completion (EDC)</td>
            <td width="5%">:</td>
            <td width="60%"><?php if(isset($job_hdr)){ echo $job_hdr->estimatedatecomplete;}?></td>
        </tr>
        <tr>
            <td width="35%">Date Successfully completed</td>
            <td width="5%">:</td>
            <td width="60%"><?php if(isset($job_hdr)){ echo $job_hdr->completedate;}?></td>
        </tr>
        <tr>
            <td width="35%">Estimated Cost</td>
            <td width="5%">:</td>
            <td width="60%"><?php if(isset($job_hdr)){ echo $job_hdr->estimatedcost;}?></td>
        </tr>
        <tr>
            <td width="35%">Actual Cost</td>
            <td width="5%">:</td>
            <td width="60%"><?php if(isset($job_hdr)){ echo $job_hdr->actualcost;}?></td>
        </tr>        
		<tr>
            <td width="35%">Engineer</td>
            <td width="5%">:</td>
            <td width="60%"><?php if(isset($job_eng)){ echo $job_eng->engineer;}?></td>
        </tr> 
		<tr>
            <td width="35%">HOD</td>
            <td width="5%">:</td>
            <td width="60%"></td>
        </tr>         
    </table>   
    
    <br>
    <table style="width: 8.5in;">
        <tr>
            <td style="text-align: center;"><hr></td>
        </tr>        
    </table> 
    
    <table style="width: 8.5in;">
        <tr>
            <td width="100%">Job History</td>
        </tr>        
    </table>     
     
	<table class="bdr" style="width: 8.5in;">
		<thead>           
        <tr>        
            <th width="10%">Date</th>
            <th width="40%">Comment</th>
            <th width="10%">User</th>
            <th width="10%">Approve</th>
            <th width="10%">Accept</th>
            <th width="10%">Complete</th>
            <th width="10%">Feedback</th>
        </tr>
        </thead>
        
        <?php 
        
        	$job_st_log = $this->job_requisition->read_job_status_log($docno);
	
	        foreach ($job_st_log as $row)
	        {
	        	echo "<tr>";
	        	
	        	echo "<td width=\"10%\">".$row->datetime."</td>";
	        	echo "<td width=\"40%\">".$row->remark."</td>";
	        	echo "<td width=\"10%\">".$row->user."</td>";
	        	
	        	if($row->docstatus === 'A'){
	        		echo "<td width=\"10%\">Approve</td>";
	        	}elseif($row->docstatus === 'P'){
	        		echo "<td width=\"10%\">Pending</td>";
	        	}elseif($row->docstatus === 'R'){
	        		echo "<td width=\"10%\">Reject</td>";
	        	}else{
	        		echo "<td width=\"10%\"><p></p></td>";
	        	}
	        	
	        	if($row->acceptrej === 'A'){
	        		echo "<td width=\"10%\">Accept</td>";
	        	}elseif($row->acceptrej=== 'P'){
	        		echo "<td width=\"10%\">Pending</td>";
	        	}elseif($row->acceptrej=== 'R'){
	        		echo "<td width=\"10%\">Reject</td>";
	        	}else{
	        		echo "<td width=\"10%\"><p></p></td>";
	        	}
	        	
	        	if($row->complete === 'Y'){
	        		echo "<td width=\"10%\">Complete</td>";
	        	}elseif($row->complete === 'N'){
	        		echo "<td width=\"10%\">No</td>";
	        	}else{
	        		echo "<td width=\"10%\"><p></p></td>";
	        	}
	        	
	        	if($row->acknowledgement === 'A'){
	        		echo "<td width=\"10%\">Accept</td>";
	        	}elseif($row->acknowledgement === 'R'){
	        		echo "<td width=\"10%\">Reoprn</td>";
	        	}else{
	        		echo "<td width=\"10%\"><p></p></td>";
	        	}
	        	
	        	echo "</tr>";
	        }               
        
        ?>
        
    </table>
    <br>      

</body>
</html>