<?php

	$docno = 0;

	if (isset($doc_no)) {
		$docno = $doc_no;
	}
	
	$pr_hdr = $this->mrn->read_mrn_hdr_by_mrnno($docno);
	
	$mrn_no = "";
	$req_by = "";
	$date = "";
	$req_date = "";
	$remark = "";
	$wherehouseno = 0;
	$sap_ref_no = "";
	$docstatus = "";
	$apprejby = "";
	
	foreach ($pr_hdr->result() as $row_hdr)
	{	
		$mrn_no = $row_hdr->mrno;
		$req_by = $row_hdr->insertuser;
		$date = $row_hdr->insertdatetime;
		$req_date = $row_hdr->reqdate;
		$remark= $row_hdr->remarks;
		$wherehouseno = $row_hdr->wherehouseno;
		$sap_ref_no = $row_hdr->saprefno;
		$docstatus = $row_hdr->docstatus;
		$apprejby = $row_hdr->apprejby;
	}
	
	$wherehousecode = "";
	
	//$wherehouse = $this->wherehouse->read_wherehouse_by_no($wherehouseno);

	//$wherehousecode = $wherehouse->whcode;
	
?>

<!DOCTYPE html>
<html>

<head>

	<meta charset="utf-8">
	<title>MRN - <?php echo $mrn_no;?></title>
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
            <td style="text-align: center;"><b>Material Requisition for Manitenance</b><hr></td>
        </tr>
        
    </table>

    <table style="width: 8.5in;">           
        <tr>
            <td width="10%">MRN No</td>
            <td width="5%">:</td>
            <td width="15%"><?php echo $mrn_no;?></td>
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
            <td width="55%"colspan="4"><?php echo $req_date;?></td>
        </tr>       
    </table>
    
    <br>  
	<table class="bdr" style="width: 8.5in;">
		<thead>           
        <tr>
            <th width="10%">Item Code</th>
            <th width="20%">Item</th>
            <th width="15%">Company</th>
            <th width="15%">Department</th>
            <th width="15%">Machine</th>
            <th width="5%">W House</th>
            <th width="10%" style="text-align: right;">Qty</th>
            <th width="5%">Uom</th>
            <th width="5%">Pur Type</th>
        </tr>
        </thead>
        
        <?php 
        
        	$pr_dtl = $this->mrn->read_mrn_dtl_by_no($docno);
        	
        	foreach ($pr_dtl->result() as $row_dtl)
        	{	
        		
        		if($row_dtl->purtype === "L"){
        			$pur_type = 'Local';
        		}else{
        			$pur_type = 'Import';
        		}
        		
        		echo "<tr>";
        		echo "<td width=\"10%\">".$row_dtl->itemcode."</td>";
	        		echo "<td width=\"20%\">".$row_dtl->Item."</th>";
	        		echo "<td width=\"15%\">".$row_dtl->Company."</d>";
	        		echo "<td width=\"15%\">".$row_dtl->Department."</td>";
	        		echo "<td width=\"15%\">".$row_dtl->Machine."</td>";
	        		echo "<td width=\"5%\">".$row_dtl->whcode."</td>";
	        		echo "<td width=\"10%\" style=\"text-align: right;\">".$row_dtl->Qty."</td>";
	        		echo "<td width=\"5%\">".$row_dtl->Uom."</td>";
	        		echo "<td width=\"5%\">".$pur_type."</td>";
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
            <td width="15%"><?php echo $req_by;?></td>
            <td width="10%">Recomended By :</td>
            <td width="15%"></td>
            <?php
	            if ($docstatus === "A"){
	            	echo "<td width=\"10%\">Approved By :</td>";
	            }else{
	            	echo "<td width=\"10%\">Rejected By :</td>";
				}

				echo "<td width=\"15%\">".$apprejby."</td>";
            ?>
            <td width="10%">Received By :</td>
            <td width="15%"></td>
        </tr>
    </table>        

</body>
</html>