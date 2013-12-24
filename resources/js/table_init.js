$(document).ready(function() {
    $('#example').dataTable({
		//"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"bStateSave": true, 
		/* "bProcessing": true,
		"bDeferRender": true,
        "bRetrieve":true,
        "bFilter":true */  
    });
} );

function initMyDataTable()
{
	$('#example').dataTable({
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"bStateSave": true, 
		/* "bProcessing": true,
		"bDeferRender": true,
        "bRetrieve":true,
        "bFilter":true */  
    });
}