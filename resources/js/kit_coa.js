	delete_specs_list="";
	new_spec_id=0;
	
	jQuery(".edit_button").click(function() {
		// validate and process form
		// first hide any error messages
		
		var description=jQuery('input#description').val();
		var sku=jQuery('input#sku').val();
		var lot=jQuery('input#lot').val();
		var date=jQuery('input#date').val();
		var usage=jQuery('input#usage').val();
		var temp=jQuery('input#temp').val();
		var quality=jQuery('input#quality').val();
		var signer=jQuery('input#signer').val();
		var components=""
		var spec_title="";
		var spec_desc="";
		var spec_order="";
		var spec_id="";
		var i=0;
		while(i<50){
			if(jQuery('input#component'+i).val()!=undefined){
				components+=jQuery('input#component'+i+'').val();
				components+='|||';
			}
			++i;
			//else{ break; }
		}
		
		i=0;
		while(i<50){
			if(jQuery('input#spec_title'+i).val()!=undefined){
				spec_id+=jQuery('input#spec_id'+i+'').val();
				spec_id+='|||';
			
				spec_title+=jQuery('input#spec_title'+i+'').val();
				spec_title+='|||';
				
				spec_desc+=jQuery('input#spec_desc'+i+'').val();
				spec_desc+='|||';
				
				spec_order+=jQuery('input#spec_order'+i+'').val();
				spec_order+='|||';
			}
			++i;
		}
		
		
		var dataString = { description : jQuery('input#description').val(),
							sku : jQuery('input#sku').val(),
							lot : jQuery('input#lot').val(),
							date : jQuery('input#date').val(),
							usage : jQuery('input#usage').val(),
							temp : jQuery('input#temp').val(),
							quality : jQuery('input#quality').val(),
							signer : jQuery('input#signer').val(),
							component : components,
							spec_title : spec_title,
							spec_desc : spec_desc,
							spec_order : spec_order,
							spec_id : spec_id,
							delete_specs : delete_specs_list
							};
					
		jQuery.ajax({
			type: "POST",
			url: "/processors/update_cofa.php",
			//url: "/processors/update_cofa.php?description="+component,
			data: dataString,
			success: function() {
				jQuery('#message').html("<h2 class='update_message'>Certificate of Analysis has been updated</h2>")
				.hide()
				.fadeIn(200, function() {});
				//refresh();
				setTimeout(function(){ 
					jQuery('#message').fadeOut(200, function() {});
				}, 1400 );
			}
		});
		
		return false;
	});
	function add_component(id){
		jQuery('#new_component').replaceWith("<tr id='component_row"+id+"'><td><input name='component"+id+"' id='component"+id+"' style='width: 370px;' value=''/></td><td><Center>Pass</Center></td><td><Center>Yes</Center></td><td style='text-align:center;'><a href='#' class='del_button' onclick='delete_component("+id+"); return false;'>Delete</a></td></tr><tr id='new_component'></tr>");
		id=id+1;
		jQuery('#component_button').replaceWith("<a class='pdf_button' id='component_button' href='#' onclick='add_component("+id+");return false;' style='float:right;'>Add New Component</a>");
	}
	function add_specification(id){
		jQuery('#new_specification').replaceWith("<tr id='spec_row"+id+"'><input type='hidden' name='spec_id"+id+"' id='spec_id"+id+"' value='new_spec"+new_spec_id+"'/><td><input style='width:370px;' name='spec_title"+id+"' id='spec_title"+id+"' value=''/></td><td><input style='width:300px;' name='spec_desc"+id+"' id='spec_desc"+id+"' value=''/></td><td><input style='width:90px;text-align:center;' name='spec_order"+id+"' id='spec_order"+id+"' value=''/></td><td class='del_td'><a href='#' class='del_button' onclick='delete_spec("+id+"); return false;'>Delete</a></td></tr><tr id='new_specification'></tr>");
		new_spec_id=new_spec_id+1;
		id=id+1;
		jQuery('#spec_button').replaceWith("<a class='pdf_button' id='spec_button' href='#' onclick='add_specification("+id+");return false;' style='float:right;'>Add New Specification</a>");
	}
	function delete_component(id){
		jQuery('#component_row'+id).replaceWith("");
	}
	function delete_spec(id, spec_id){
		jQuery('#spec_row'+id).replaceWith("");
		if(spec_id!=undefined){
			delete_specs_list+=spec_id;
			delete_specs_list+="|||";
		}
	}
	function refresh(){
	    jQuery.mobile.changePage(window.location.href, {
	        allowSamePageTransition: true,
	        transition: 'none',
	        reloadPage: true
	    });
	}
	function delete_warning(sku, lot){
		var answer = confirm ("Are you sure you want to delete "+sku+" | "+lot+"?");
		if (answer){
			window.location="/processors/delete_cofa.php?sku="+sku+"&lot="+lot;
		}
	}
	function create_pdf(url){
		window.open(url+jQuery('input#cert_name').val(), '_blank');
	}