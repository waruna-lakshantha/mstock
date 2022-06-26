<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$data;

if (isset($para)) {
	$data = $para;
}else{
	return;
}

$item_para = $data[1]['item_para'];

$sub_head_2 = "";

$sub_head_2 = "As at ".date("Y-m-d");

$rpt_det = $this->store->read_item_bin_card_report($item_para);

?>

<!DOCTYPE html>
<html>

<head>

	<meta charset="utf-8">
	<title>Item Bin Card</title>
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
            <td style="text-align: center;"><b>Item Bin Card</b></td>
        </tr>       
        <tr>
            <td style="text-align: center;"><?php echo $sub_head_2;?><hr></td>
        </tr>        
    </table>
    
	<table style="width: 8.5in;">
		<thead>           
        <tr>
            <th width="10%">Item Code</th>
            <th width="30%">Item</th>
            <th width="20%">Company</th>
            <th width="10%">Uom</th>
            <th width="15%" style="text-align: right;">Bal Qty</th>
            <th width="15%" style="text-align: right;">Value</th> 
        </tr>
        </thead>
        
        <?php
        
	        /*select i.itemcode, i.description, c.description as company, i.uom, sum(s.balqty) as balqty, v.value
				from stockmaster s 
				inner join item i on s.itemno = i.no
				inner join (select itemno, comno, FORMAT(sum((inqty - usedqty) * unitcost),2) as value
						from stockindetail
						where (inqty - usedqty) > 0
						group by itemno, comno) v on s.itemno = v.itemno and s.comno = v.comno
				inner join company c on s.comno = c.no
				group by i.itemcode, i.description, i.uom, c.description*/
        	
        	foreach ($rpt_det as $row_dtl)
        	{	
        		echo "<tr>";
        		
        			echo "<td width=\"10%\">".$row_dtl->itemcode."</td>";
        			echo "<td width=\"30%\">".$row_dtl->description."</td>";
        			echo "<td width=\"20%\">".$row_dtl->company."</td>";
        			echo "<td width=\"10%\">".$row_dtl->uom."</td>";
        			echo "<td width=\"15%\" style=\"text-align: right;\">".$row_dtl->balqty."</td>";
        			echo "<td width=\"15%\" style=\"text-align: right;\">".$row_dtl->value."</td>";
        		
        		echo "</tr>";

        	}
        
        ?>
        
    </table>
    <br>    

</body>
</html>
