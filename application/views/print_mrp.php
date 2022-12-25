<?php

	$docno = 0;

	if (isset($doc_no)) {
		$docno = $doc_no;
	}
	
	$pr_hdr = $this->pr->read_pr_hdr_by_prno($docno);
	
	$pr_no = "";
	$req_by = "";
	$date = "";
	$req_date = "";
	$remark = "";	
	$pur_type = "-";
	$sap_ref_no = "";
	
	$docstatus = "";
	$apprejby = "";
	$acceptrej = "";
	$acceptrejby = "";
	$proceed = "";
	$proceedby = "";
	
	foreach ($pr_hdr->result() as $row_hdr)
	{	
		$pr_no = $row_hdr->prno;
		$req_by = $row_hdr->insertuser;
		$date = $row_hdr->insertdatetime;
		$req_date = $row_hdr->reqdate;
		$remark = $row_hdr->remarks;
		$sap_ref_no = $row_hdr->saprefno;
		
		if($row_hdr->purtype === "L"){
			$pur_type = 'Local';
		}else{
			$pur_type = 'Import';
		}
		
		$docstatus = $row_hdr->docstatus;
		$apprejby = $row_hdr->apprejby;
		$acceptrej = $row_hdr->acceptrej;
		$acceptrejby = $row_hdr->acceptrejby;
		$proceed = $row_hdr->proceed;
		$proceedby = $row_hdr->proceedby;
	}
	
?>

<!DOCTYPE html>
<html>

<head>

	<meta charset="utf-8">
	<title>MRP - <?php echo $pr_no;?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel = "stylesheet" type = "text/css" 
       href = "<?php echo base_url(); ?>assets/font-awesome-4.3.0/css/font-awesome.min.css">       
    <script src="<?php echo base_url();?>assets/js/jquery-3.1.1.min.js"></script>
    <script src="<?php echo base_url();?>assets/json/jquery.json.min.js"></script>   	

	<style>
			
	    @media print {
			
		    @page :left {
		    margin: 0.3cm;
		    }

		    @page :right {
		    margin: 0.3cm;
		    }	
				
		    #s1 {font-size: 8pt; line-height: 100%; margin:0cm 0cm 0cm 0cm;}

		    #s2 {font-size: 9pt; line-height: 100%; margin:0cm 0cm 0cm 0cm;}

		    #s3 {font-size: 10pt; line-height: 100%; margin:0cm 0cm 0cm 0cm;}
			
		    body {
		        //font: 12pt Georgia, \"Times New Roman\", Times, serif;
		        line-height: 1.5;
                font-size: 12pt; 
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
	        margin:0cm 0.3cm 0cm 0.3cm;
            font-size: 12pt;
	    }
			
	    td{
	        //border:1px solid black;
	        border-collapse:collapse;
	        overflow: hidden;
	        vertical-align: top;
	        padding: 0px;
            font-size: 12pt;
	    }
     
        .bdr table
        {
            border: 1px solid black; 
            border-collapse:collapse; 
            font-size: 12pt; 
        }

        .bdr th
        {
            font-size:11px;
            border: 1px solid black; 
            border-collapse:collapse;
            padding: 2px; 
            font-size: 12pt; 
        }
     
        .bdr td
        {
            font-size:12px;
            border: 1px solid black;
            border-collapse:collapse;
            padding: 2px; 
            font-size: 12pt;  
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
            <td style="text-align: center;"><b>Material Requisition for Purchase (MRP)</b><hr></td>
        </tr>
        
    </table>

    <table style="width: 8.5in;">           
        <tr>
            <td width="10%">MRP No</td>
            <td width="5%">:</td>
            <td width="15%"><?php echo $pr_no;?></td>
            <td width="10%">Req By</td>
            <td width="5%">:</td>
            <td width="20%"><?php echo $req_by;?></td>
            <td width="10%">SAP Ref</td>
            <td width="5%">:</td>
            <td width="20%"><?php echo $sap_ref_no;?></td>
        </tr>
        <tr>
            <td width="10%">Date</td>
            <td width="5%">:</td>
            <td width="15%"><?php echo $date;?></td>
            <td width="10%">Req Date</td>
            <td width="5%">:</td>
            <td width="20%"><?php echo $req_date;?></td>
            <td width="10%">Pur Type</td>
            <td width="5%">:</td>
            <td width="20%"><?php echo $pur_type;?></td>          
        </tr>
    </table>
    
    <br>  
	<table class="bdr" style="width: 8.5in;">
		<thead>           
        <tr>
            <th width="10%">Item Code</th>
            <th width="30%">Item</th>
            <th width="15%">Company</th>
            <th width="15%">Department</th>
            <th width="15%">Machine</th>
            <th width="10%" style="text-align: right;">Qty</th>
            <th width="5%">Uom</th>
        </tr>
        </thead>
        
        <?php 
        
        	$pr_dtl = $this->pr->read_pr_dtl_by_no($docno);
        	
        	foreach ($pr_dtl->result() as $row_dtl)
        	{	
        		echo "<tr>";
        		echo "<td width=\"10%\">".$row_dtl->itemcode."</td>";
	        		echo "<td width=\"30%\">".$row_dtl->Item."</th>";
	        		echo "<td width=\"15%\">".$row_dtl->Company."</d>";
	        		echo "<td width=\"15%\">".$row_dtl->Department."</td>";
	        		echo "<td width=\"15%\">".$row_dtl->Machine."</td>";
	        		echo "<td width=\"10%\" style=\"text-align: right;\">".$row_dtl->Qty."</td>";
	        		echo "<td width=\"5%\">".$row_dtl->Uom."</td>";
        		echo "</tr>";
        	}
        
        ?>
        
    </table>
    <br>
    
    <table style="width: 8.5in;">
        <tr>
            <td width="10%">Remarks</td>
            <td width="5%">:</td>
            <td width="85%"><?php echo $remark;?></td>
        </tr>
    </table>  
    
    <br><br>
    <table style="width: 8.5in;">
        <tr>
            <td width="10%">Prepared By :</td>
            <td width="10%"><?php echo $req_by;?></td>
            
			<?php
	            if ($docstatus === "A"){
	            	echo "<td width=\"10%\">Approved By :</td>";
	            }else{
	            	echo "<td width=\"10%\">Rejected By :</td>";
				}

				echo "<td width=\"10%\">".$apprejby."</td>";
            ?>
            
			<?php
				if ($acceptrej === "A"){
	            	echo "<td width=\"10%\">Accepted By :</td>";
	            }else{
	            	echo "<td width=\"10%\">Accept Rejected By :</td>";
				}

				echo "<td width=\"10%\">".$acceptrejby."</td>";
            ?>  
            
			<?php
				echo "<td width=\"10%\">Proceed By :</td>";

				echo "<td width=\"10%\">".$proceedby."</td>";
            ?>  
            
            <td width="10%">Received By :</td>
            <td width="10%"></td>            
        </tr>
    </table>        

</body>
</html>