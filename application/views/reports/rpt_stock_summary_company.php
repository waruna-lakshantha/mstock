<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$data;

if (isset($para)) {
	$data = $para;
}else{
	return;
}

$com_para = $data[1]['com_para'];
$item_para = $data[2]['item_para'];

$sub_head_2 = "";

$sub_head_2 = "As at ".date("Y-m-d");

?>

<!DOCTYPE html>
<html>

<head>

	<meta charset="utf-8">
	<title>Stock Summary - Company Wise</title>
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
            <td style="text-align: center;">425/B/4, Jayagath Mawatha, Kahathuduwa, Polgasowita. Sri Lanka.</td>
        </tr>
        <tr>
            <td style="text-align: center;">Tel. +94773718891. E mail. waruna.lakshantha@gmail.com</td>
        </tr>
        <tr>
            <td style="text-align: center;"><b>Stock Valuation - Company Wise</b></td>
        </tr>       
        <tr>
            <td style="text-align: center;"><?php echo $sub_head_2;?><hr></td>
        </tr>        
    </table>    
    
    <?php    
    	if(count($com_para) > 0){
	    	
    		$com = '';
    		
    		for ($row = 0; $row < count($com_para); $row++) {
    			$com = $com_para[$row]['companyno'];
    			
    			$com_det = $this->company->read_selected_company($com);
    			
    			$rpt_det = $this->store->read_stock_summary_company_report($com, $item_para);
    			
    			echo "<table style=\"width: 8.5in;\">";
    			echo "<tr>";
    			echo "<th>".$com_det->description."</th>";
    			echo "</tr>";
    			echo "</table>";
    			
    			echo "<table style=\"width: 8.5in;\">";
    			echo "<thead>";
    			echo "<tr>";
    			echo "<th width=\"10%\">Item Code</th>";
    			echo "<th width=\"30%\">Item</th>";
    			echo "<th width=\"20%\">Warehouse</th>";
    			echo "<th width=\"10%\">Uom</th>";
    			echo "<th width=\"15%\" style=\"text-align: right;\">Bal Qty</th>";
    			echo "<th width=\"15%\" style=\"text-align: right;\">Value</th>";
    			echo "</tr>";
    			echo "</thead>";
    			
    			foreach ($rpt_det as $row_dtl)
    			{
    				echo "<tr>";
    				
    				echo "<td width=\"10%\">".$row_dtl->itemcode."</td>";
    				echo "<td width=\"30%\">".$row_dtl->description."</td>";
    				echo "<td width=\"20%\">".$row_dtl->whcode."</td>";
    				echo "<td width=\"10%\">".$row_dtl->uom."</td>";
    				echo "<td width=\"15%\" style=\"text-align: right;\">".$row_dtl->balqty."</td>";
    				echo "<td width=\"15%\" style=\"text-align: right;\">".$row_dtl->value."</td>";
    				
    				echo "</tr>";
    			}
    			
    			echo "</table>";
    			echo "<br>";
	    	}
	    		    	
	    }        	
    ?>          

</body>
</html>
