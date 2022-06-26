
function filter_Item_Option_List(ele_id) {
    var input, filter, ul, li, a, i;
    input = document.getElementById("id_item_search");
    filter = input.value.toUpperCase();
    div = document.getElementById("div_item");
    a = div.getElementsByTagName("option");
    
    var _val;
    var _seli = 0;
    
    for (i = 0; i < a.length; i++) {    	        	
        if (a[i].innerHTML.toUpperCase().indexOf(filter) > -1) {
            a[i].style.display = "";                       
            
            if(_seli == 0){
            	_val = a[i].value;
            }
            
            _seli++;
            
        } else {
            a[i].style.display = "none";
        }
    }  
        
    $('#'+ele_id).val(_val);
    $('#'+ele_id).change();
    
}	