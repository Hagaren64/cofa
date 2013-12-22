<?php include_once('query.php'); ?>

<?php
	connect();
	
	$lotids="SELECT lot_id from kits_lot";
	$lotids_result=mysql_query($lotids) or die(mysql_error());
	
	while($lotid_rows=mysql_fetch_assoc($lotids_result)){
		// echo $lotid_rows['lot_id']."<br/>";
		
		// Find corresponding Description lot_id
		$description_lots="SELECT lot_id FROM kit_description WHERE lot_id=".$lotid_rows['lot_id'];
		$description_lots_result=mysql_query($description_lots) or die(mysql_error());
		if(mysql_num_rows($description_lots_result)==1){
			// echo "Found description ".$lotid_rows['lot_id']."<br/>";
		} else{
			// echo "Could not find description...  Now inserting<br/>";
			$insert_description_lot="INSERT INTO kit_description(lot_id) VALUES(".$lotid_rows['lot_id'].")";
			// echo $insert_description_lot."<br/>";
			mysql_query($insert_description_lot) or die(mysql_error());
		}
		
		// Find corresponding Component lot_id
		$component_lots="SELECT lot_id FROM components WHERE lot_id=".$lotid_rows['lot_id'];
		$component_lots_result=mysql_query($component_lots) or die(mysql_error());
		if(mysql_num_rows($component_lots_result)==1){
			// echo "Found component ".$lotid_rows['lot_id']."<br/>";
		} else{
			// echo "Could not find component...  Now inserting<br/>";
			$insert_component_lot="INSERT INTO components(lot_id) VALUES(".$lotid_rows['lot_id'].")";
			// echo $insert_component_lot."<br/>";
			mysql_query($insert_component_lot) or die(mysql_error());
		}
	}
	
	header("Location: /?reindex=1");
?>