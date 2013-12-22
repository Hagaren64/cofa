<?php include_once('query.php'); ?>

<?php
	connect();
	
	$sku=$_GET['sku'];
	$lot=$_GET['lot'];

	$query="SELECT lot_id FROM kits_lot WHERE sku='$sku' AND lot_number='$lot'";
	$result=mysql_query($query);
	
	$lot_id=mysql_result($result,0);
	$delete_comp="DELETE FROM components WHERE lot_id=$lot_id";
	$delete_desc="DELETE FROM kit_description WHERE lot_id=$lot_id";
	$delete_lot="DELETE FROM kits_lot WHERE lot_id=$lot_id";
	$delete_specs="DELETE FROM specs WHERE lot_id=$lot_id";
	$delete_kits_specs="DELETE FROM kits_specs WHERE lot_id=$lot_id";
	
	mysql_query($delete_comp);
	mysql_query($delete_desc);
	mysql_query($delete_lot);
	mysql_query($delete_specs);
	mysql_query($delete_kits_specs);
	header("Location: /");
?>