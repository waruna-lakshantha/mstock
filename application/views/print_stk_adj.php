<?php

	$docno = 0;
	
	if (isset($doc_no)) {
		$docno = $doc_no;
	}
	
	$pr_hdr = $this->stk_adj->read_stk_hdr_adj_no($docno);
	
	$AdjNo = "";
	$date = "";
	$remarks = "";
	$insertuser = "";
	
	foreach ($pr_hdr->result() as $row_hdr)
	{		
		$AdjNo = $row_hdr->AdjNo;
		$date = $row_hdr->date;
		$remarks = $row_hdr->remarks;
		$insertuser = $row_hdr->insertuser;
	}

?>

<!DOCTYPE html>
<html>

<head>

	<meta charset="utf-8">
	<title>ADJ - <?php echo $AdjNo;?></title>
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
            <td style="text-align: center;"><b>Stock Adjustment</b><hr></td>
        </tr>
        
    </table>

    <table style="width: 8.5in;">           
        <tr>
            <td width="10%">ADJ No</td>
            <td width="5%">:</td>
            <td width="15%"><?php echo $AdjNo;?></td>
            <td width="10%">Date</td>
            <td width="5%">:</td>
            <td width="20%"><?php echo $date;?></td>
            <td width="10%">Updated By</td>
            <td width="5%">:</td>
            <td width="20%"><?php echo $insertuser;?></td>
        </tr>     
    </table>
    
    <br>  
	<table class="bdr" style="width: 8.5in;">
		<thead>           
        <tr>
            <th width="5%">Item Code</th>
            <th width="30%">Item</th>
            <th width="10%">Company</th>
            <th width="5%">Uom</th>
            <th width="10%" style="text-align: right;">Old Bal</th>            
			<th width="10%" style="text-align: right;">Adj Qty</th>
			<th width="10%" style="text-align: right;">New Bal</th>	
			<th width="10%" style="text-align: right;">U Cost</th>
			<th width="10%" style="text-align: right;">Adj Value</th>	
        </tr>
        </thead>
        
        <?php
        
        	$pr_dtl = $this->stk_adj->read_stk_adj_dtl_by_no($docno);
        	
        	foreach ($pr_dtl->result() as $row_dtl)
        	{	
        		echo "<tr>";        		
	        		echo "<td width=\"5%\">".$row_dtl->itemcode."</td>";
	        		echo "<td width=\"30%\">".$row_dtl->Item."</th>";
	        		echo "<td width=\"10%\">".$row_dtl->Company."</th>";
	        		echo "<td width=\"5%\">".$row_dtl->uom."</th>";
	        		echo "<td width=\"10%\" style=\"text-align: right;\">".$row_dtl->curbal."</td>";
	        		echo "<td width=\"10%\" style=\"text-align: right;\">".$row_dtl->qty."</td>";
	        		echo "<td width=\"10%\" style=\"text-align: right;\">".$row_dtl->newbal."</td>";
	        		echo "<td width=\"10%\" style=\"text-align: right;\">".$row_dtl->unitcost."</td>";
	        		echo "<td width=\"10%\" style=\"text-align: right;\">".$row_dtl->total."</td>";
        		echo "</tr>";

        	}
        
        ?>
        
    </table>
    <br>    
    
    <table style="width: 8.5in;">
        <tr>
            <td width="10%">Remarks</td>
            <td width="5%">:</td>
            <td width="85%"><?php echo $remarks;?></td>
        </tr>
    </table>  
    
    <br><br>
    <table style="width: 8.5in;">
        <tr>
            <td width="10%">Prepared By :</td>
            <td width="15%"><?php echo $insertuser;?></td>
            <td width="10%">Approved By :</td>
            <td width="15%"></td>
        </tr>
    </table>        

</body>
</html>