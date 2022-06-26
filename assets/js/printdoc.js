function Reprint_Doc(doc, basepath){
	var no = $('#id_reprint_no').val();
	
    if(no === undefined || no === null || ($.trim(no)).length == 0)
    {
    	return;
    }
    
	var print_url = basepath + 'index.php/print/' + doc + '/'+no;
	
	var win = window.open(print_url, '_blank');
	win.focus();
	return;      
}