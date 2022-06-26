function replaceAll(str, target, replacement) {
    return str.split(target).join(replacement);
}

function get_json_date(sd){
	//var d = new Date(sd);      	
	//var fdate = d.getFullYear() + '_' + d.getMonth() + '_' + d.getDate();
	//fdate = sd.toJSON();
	//alert(fdate);
	
	var fdate = replaceAll(sd, '/', '_');
	fdate = replaceAll(sd, '-', '_');	
	fdate = replaceAll(sd, ' ', '_');
	
	return fdate;
}

function read_stk_bal_item_com_global(path, id){
	var com = $("#idgrn_company").val();
	var company_split = com.split("~");
	if(com !== undefined){
		read_stk_bal_item(path, id, company_split[0]);
	}
}

function read_stk_bal_item(path, id, com){
	
    var item_split = id.split("~");
    var stk_path = path + 'index.php/read_stk_bal/'+item_split[0];   
    
    $("#id_w_house").val(item_split[3]);
    $("#id_w_house_code").val(item_split[4]);
    
    $("#idgrn_uom").val(item_split[2]);
    
    $( "#idgrn_uom" ).prop( "disabled", true );
    $( "#idgrn_uom" ).addClass("w3-gray");
    
    $.get(stk_path, function(data, status){
    
    	if (status == 'success'){
    		$("#id_stk_bal").text(data);
    	}else{
    		$("#id_stk_bal").text('0');
    	}
    	
    })

    if(com !== undefined){
        stk_path = path + 'index.php/read_stk_bal_com/'+item_split[0]+'/'+com; 
    
        $.get(stk_path, function(data, status){
    
    	    if (status == 'success'){
    		    $("#id_stk_bal_com").text(data);
    	    }else{
    		    $("#id_stk_bal_com").text('0');
    	    }
    	
        })        
    }
    
    //id_sel_img
    
    var img_path = path + 'uploads/' + item_split[0] + '.jpg';
    
    var im = "<img src=\"" + img_path + "\" class=\"w3-border w3-padding w3-image\" alt=\"" + item_split[0] + "\">";
    
    $('#id_sel_img').html(im);
    
    $('#idgrn_uom').val(item_split[2].toUpperCase());
    $('#idgrn_uom').change();           
	
}


