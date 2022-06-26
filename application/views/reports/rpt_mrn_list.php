<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$data;

if (isset($para)) {
	$data = $para;
}else{
	return;
}

$date_para = $data[1]['date_para'];
$com_para = $data[2]['com_para'];
$dept_para = $data[3]['dept_para'];
$item_para = $data[4]['item_para'];
$status_para = $data[5]['status_para'];
$machine_para = $data[6]['machine_para'];

$isasat = (empty($date_para[0]['isasat']) ? 0 : $date_para[0]['isasat']);
$isperiod = (empty($date_para[0]['isperiod']) ? 0 : $date_para[0]['isperiod']);
$date_asat = $date_para[0]['date_asat'];
$date_from = $date_para[0]['date_from'];
$date_to = $date_para[0]['date_to'];
$approve = $status_para[0]['approve'];
$accept = $status_para[0]['accept'];
$proceed = (empty($status_para[0]['proceed']) ? 0 : $status_para[0]['proceed']);

$sub_head_1 = "Approve Status : ";

if($approve === 'P'){
	$sub_head_1 .= "Pending ";
}

if($approve === 'A'){
	$sub_head_1 .= "Approved ";
}

$sub_head_2 = "";

if ($isasat)
{
	$sub_head_2 = "As at ".$date_asat;
}

if ($isperiod)
{
	$sub_head_2 = "From ".$date_from." To ".$date_to;
}

$pr_det = $this->mrn->read_mrn_list_report($date_para, $com_para, $dept_para, $item_para, $status_para, $machine_para);

?>

<!DOCTYPE html>
<html>

<head>

	<meta charset="utf-8">
	<title>MRN List</title>
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
	        padding: 2px;
            font-size: 10pt;
	    }
	    
	    th{
	        //border:1px solid black;
	        border-collapse:collapse;
	        overflow: hidden;
	        vertical-align: top;
	        padding: 2px;
            font-size: 10pt;
            text-align: left;
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
            <td style="text-align: center;">1/265, Cemetery Road, Pamunugama, Allubomulla, Panadura.</td>
        </tr>
        <tr>
            <td style="text-align: center;">Tel. 0094382235539 Fax.0094382234568. E mail. silvmi@westernpapersl.com</td>
        </tr>
        <tr>
            <td style="text-align: center;"><b>MRN List</b></td>
        </tr>
        <tr>
            <td style="text-align: center;"><?php echo $sub_head_1;?></td>
        </tr>        
        <tr>
            <td style="text-align: center;"><?php echo $sub_head_2;?><hr></td>
        </tr>        
    </table>
    
	<table style="width: 8.5in;">
		<thead>           
        <tr>
            <th width="10%">MR No</th>
            <th width="10%">Item Code</th>
            <th width="20%">Item</th>
            <th width="10%" style="text-align: right;">Qty</th>
            <th width="5%">Uom</th>            
            <th width="5%">Date</th> 
            <th width="5%">Sts</th>
			<th width="10%">Company</th>
            <th width="10%">Dept</th>
            <th width="10%">Machine</th>
            <th width="5%">Type</th>                                           	
        </tr>
        </thead>
        
        <?php
        
	        /*select h.mrno, i.itemcode, i.description Item, d.qty, d.uom, h.date,
				h.docstatus, c.description company, de.description dept, m.machineno, d.purtype
				from mr_dtl d
				inner join mr_hdr h on d.no = h.no
				inner join item i on d.itemno = i.no
				inner join company c on d.companyno = c.no
				inner join department de on d.departmentno = de.no
				inner join machine m on d.machineno = m.no*/      
        	
        	foreach ($pr_det as $row_dtl)
        	{	
        		echo "<tr>";
        		
        			echo "<td width=\"10%\">".$row_dtl->mrno."</td>";
        			echo "<td width=\"10%\">".$row_dtl->itemcode."</td>";
        			echo "<td width=\"20%\">".$row_dtl->Item."</td>";
	        		echo "<td width=\"10%\" style=\"text-align: right;\">".$row_dtl->qty."</td>";
	        		echo "<td width=\"5%\">".$row_dtl->uom."</td>";
	        		echo "<td width=\"5%\">".$row_dtl->date."</td>";
	        		echo "<td width=\"5%\">".$row_dtl->docstatus."</td>";
	        		echo "<td width=\"10%\">".$row_dtl->company."</td>";
	        		echo "<td width=\"10%\">".$row_dtl->dept."</td>";
	        		echo "<td width=\"10%\">".$row_dtl->machineno."</td>";
	        		echo "<td width=\"5%\">".$row_dtl->purtype."</td>";        		
        		
        		echo "</tr>";

        	}
        
        ?>
        
    </table>
    <br>    

</body>
</html>
