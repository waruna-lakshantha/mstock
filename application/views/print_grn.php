<?php

	$docno = 0;

	if (isset($doc_no)) {
		$docno = $doc_no;
	}
	
	$pr_hdr = $this->grn->read_grn_hdr_by_minno($docno);
	
	$grn_no = "";
	$req_by = "";
	$date = "";
	$remark = "";
	$storeno = 0;
	$wherehouseno = 0;
	$sap_ref_no = "";
	$insertuser = "";
	$docstatus = "";
	$apprejby = "";
	
	foreach ($pr_hdr->result() as $row_hdr)
	{	
		$grn_no = $row_hdr->grnno;
		$req_by = $row_hdr->insertuser;
		$date = $row_hdr->insertdatetime;
		$remark = $row_hdr->remarks;
		$storeno = $row_hdr->storelocationno;
		$wherehouseno = $row_hdr->wherehouseno;
		$sap_ref_no = $row_hdr->saprefno;
		$insertuser = $row_hdr->insertuser;
		$docstatus =  $row_hdr->docstatus;
		$apprejby = $row_hdr->apprejby;
	}
	
	$store_location = "";
	$wherehousecode = "";
	
	$store_det = $this->store->read_store_by_no($storeno);
	$wherehouse = $this->wherehouse->read_wherehouse_by_no($wherehouseno);
	
	if(isset($store_det)){
		$store_location = $store_det->description;
	}
	//$wherehousecode = $wherehouse->whcode;
	
?>

<!DOCTYPE html>
<html>

<head>

	<meta charset="utf-8">
	<title>GRN - <?php echo $grn_no;?></title>
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
            <td style="text-align: center;">1/265, Cemetery Road, Pamunugama, Allubomulla, Panadura.</td>
        </tr>
        <tr>
            <td style="text-align: center;">Tel. 0094382235539 Fax.0094382234568. E mail. silvmi@westernpapersl.com</td>
        </tr>
        <tr>
            <td style="text-align: center;"><b>Goods Received Note</b><hr></td>
        </tr>
        
    </table>

    <table style="width: 8.5in;">           
        <tr>
            <td width="9%">GRN No</td>
            <td width="2%">:</td>
            <td width="15%"><?php echo $grn_no;?></td>
            <td width="9%">SAP Ref</td>
            <td width="2%">:</td>
            <td width="15%"><?php echo $sap_ref_no;?></td>
            <td width="9%">Date</td>
            <td width="2%">:</td>
            <td width="10%"><?php echo $date;?></td>
            <td width="9%">Store Loc</td>
            <td width="2%">:</td>
            <td width="16%"><?php echo $store_location;?></td>            
        </tr>      
    </table>
    
    <br>  
	<table class="bdr" style="width: 8.5in;">
		<thead>           
        <tr>
            <th width="5%">Item Code</th>
            <th width="25%">Item</th>
            <th width="5%">MRP No</th>
            <th width="5%">Req Date</th>
            <th width="5%">Rec Date</th>
            <th width="20%">Supplier</th>
            <th width="10%">PO No</th>
            <th width="10%">W house</th>
            <th width="10%" style="text-align: right;">Qty</th>
            <th width="5%">Uom</th>			
        </tr>
        </thead>
        
        <?php 
        
        	$GrandTotal = 0;
        
        	$pr_dtl = $this->grn->read_grn_dtl_by_no($docno);
        	
        	foreach ($pr_dtl->result() as $row_dtl)
        	{	
        		echo "<tr>";
        		echo "<td width=\"5%\">".$row_dtl->itemcode."</td>";
	        		echo "<td width=\"25%\">".$row_dtl->Item."</th>";
	        		echo "<td width=\"5%\">".$row_dtl->prno."</th>";
                    echo "<td width=\"5%\">".$row_dtl->reqdate."</th>";
                    echo "<td width=\"5%\">".$row_dtl->receiveddate."</th>";
                    echo "<td width=\"20%\">".$row_dtl->name."</d>";
	        		echo "<td width=\"10%\">".$row_dtl->pono."</td>";
	        		echo "<td width=\"10%\">".$row_dtl->whcode."</td>";
	        		echo "<td width=\"10%\" style=\"text-align: right;\">".$row_dtl->Qty."</td>";
	        		echo "<td width=\"5%\">".$row_dtl->Uom."</td>";
        		echo "</tr>";
        		
        		$GrandTotal += $row_dtl->total;
        	}
        
        ?>
        
    </table>
    <br>
    
    <table style="width: 8.5in;">
		<tr>
			<td width="5%"><p></p></td>
			<td width="25%"><p></p></td>
			<td width="5%"><p></p></td>
			<td width="10%"><p></p></td>
			<td width="10%"><p></p></td>
			<td width="10%"><p></p></td>
			<td width="10%"><p></p></td>
			<td width="5%"><p></p></td>
			<td width="10%"><p></p></td>
			<td width="10%" style="text-align: right;"><?php echo $GrandTotal;?></td>
		</tr>    
    </table>     
    
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
            <td width="15%"><?php echo $insertuser;?></td>
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