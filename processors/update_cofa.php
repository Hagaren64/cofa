<?php include_once('query.php'); ?>

<?php
	connect();

	$description=$_POST['description'];
	$sku=$_POST['sku'];
	$lot=$_POST['lot'];
	$date=$_POST['date'];
	$usage=$_POST['usage'];
	$temp=$_POST['temp'];
	$quality=$_POST['quality'];
	$signer=$_POST['signer'];
	$component=$_POST['component'];
	$component=substr(str_replace('|||', '#', $component),0,-1);
	$spec_title=explode("|||", $_POST['spec_title']);
	$spec_desc=explode("|||", $_POST['spec_desc']);
	$spec_order=explode("|||", $_POST['spec_order']);
	$spec_id=explode("|||", $_POST['spec_id']);
	$delete_specs=explode("|||", $_POST['delete_specs']);
	
	// Get Lot ID
	$result=get_lot_id($sku,$lot);
	$lot_id=mysql_result($result,0);
	
	// Update Description
	$query="UPDATE kit_description SET description='$description', temp='$temp', usages='$usage', quality='$quality' WHERE lot_id=$lot_id";
	mysql_query($query);
	
	// Update Components
	$query="UPDATE components SET comp='$component' WHERE lot_id=$lot_id";
	mysql_query($query);
	
	// Get Original Name
	//$query="SELECT signer from kits_lot_copy WHERE lot_number='$lot' AND sku='$sku'";
	//$result=mysql_query($query);
	//$name=mysql_result($result,0);
	
	// Update Kits_lot
	$query="UPDATE kits_lot SET date='$date', signer='$signer' WHERE lot_number='$lot' AND sku='$sku'";
	mysql_query($query);
	
	// Update Signer_profile
	//$query="UPDATE signer_profile_copy SET signer_name='$signer' WHERE signer_name='$signer'";
	//mysql_query($query);
	
	// Update Specifications
	$size=sizeof($spec_title);
	for($i=0; $i<$size; $i++){
		if($spec_title[$i]!=""){
			$spec_exist=mysql_query("SELECT * FROM kits_spec WHERE spec_id=$spec_id[$i]");
			$num_rows=mysql_num_rows($spec_exist);
			if($num_rows==1){ 
				$query="UPDATE specs SET title='$spec_title[$i]', description='$spec_desc[$i]', ordering=$spec_order[$i] WHERE spec_id=$spec_id[$i]";
				mysql_query($query) or die(mysql_error());
			} else if($num_rows==0){ 
				mysql_query("insert into test(lot, sku, descrip) value('$lot', '$sku', '$lot_id')");
				$query="INSERT INTO specs(title, description, ordering, lot_id) VALUES('$spec_title[$i]','$spec_desc[$i]','$spec_order[$i]',$lot_id)";
				mysql_query($query) or die(mysql_error());
				
				$get_new_id="SELECT spec_id FROM specs ORDER BY spec_id DESC LIMIT 1";
				$result=mysql_query($get_new_id) or die(mysql_error());
				$new_id=mysql_result($result, 0) or die(mysql_error());
				
				$query="INSERT INTO kits_spec(sku, spec_id, lot_id) VALUES('$sku', $new_id, $lot_id)";
				mysql_query($query) or die(mysql_error());
			}
		}
	}
	
	// Delete Specifications
	$size=sizeof($delete_specs);
	for($i=0; $i<$size; $i++){
		if($delete_specs[$i]!=""){
			$query="DELETE FROM specs
					WHERE spec_id=$delete_specs[$i]";
			mysql_query($query);
			$query="DELETE FROM kits_spec
					WHERE spec_id=$delete_specs[$i]";
			mysql_query($query);
		}
	}	
?>