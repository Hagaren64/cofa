<?php include_once('query.php'); ?>

<?php
	connect();
/*
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
	
	//$query="INSERT INTO test(desc) 
	//		values('$description')";
	$query="INSERT INTO test(descrip, sku, lot, date, usages, temp, quality, signer, comp) 
			values('".$description."', '".$sku."', '".$lot."', '".$date."', '".$usage."', '".$temp."', '".$quality."', '".$signer."', '".$component."')";
	mysql_query($query);
*/

function add_new_cofa($sku, $lot, $date, $prod_type){
	connect();
	
	// echo "---INSERT---<br>";
	
	// Format Date
	$date_arr=explode("/",$date);
	$newDate=$date_arr[2]."-".$date_arr[0]."-".$date_arr[1];
	
	// INSERT INTO kits
	$query="INSERT INTO kits(sku) VALUE('$sku')";
	// echo $query."<br>";
	mysql_query($query) or die(mysql_error());
	
	// INSERT INTO kits_lot
	$query="INSERT INTO kits_lot(date,sku,lot_number,signer) VALUE('$newDate', '$sku', '$lot', 'Seth Ruga')";
	// echo $query."<br>";
	mysql_query($query) or die(mysql_error());
	
	// Get lot_id
	$query="SELECT lot_id FROM kits_lot WHERE sku='$sku' AND lot_number='$lot'";
	// echo $query."<br>";
	$query=mysql_query($query) or die(mysql_error());
	$lot_id=mysql_result($query,0);
	
	// INSERT INTO kit_description
	$query="INSERT INTO kit_description(lot_id) VALUE('$lot_id')";
	// echo $query."<br>";
	mysql_query($query) or die(mysql_error());
	
	// INSERT INTO components
	$query="INSERT INTO components(lot_id) VALUE('$lot_id')";
	// echo $query."<br>";
	mysql_query($query) or die(mysql_error());
}

function add_new_lot($sku, $lot, $date, $prod_type){
	// echo "---INSERT Existing---<br>";
	
	// Format Date
	$date_arr=explode("/",$date);
	$newDate=$date_arr[2]."-".$date_arr[0]."-".$date_arr[1];
	
	// INSERT INTO kits_lot
	$query="INSERT INTO kits_lot(date,sku,lot_number,signer) VALUE('$newDate', '$sku', '$lot', 'Seth Ruga')";
	// echo $query."<br>";
	mysql_query($query) or die(mysql_error());
	
	// Get lot_id
	$query="SELECT lot_id FROM kits_lot WHERE sku='$sku' AND lot_number='$lot'";
	// echo $query."<br>";
	$result=mysql_query($query) or die(mysql_error());
	$lot_id=mysql_result($result,0);
	// echo "LOT ID: ".$lot_id."<BR>";
	
	// Get last entered information from same sku
	$query="SELECT lot_id FROM kits_lot WHERE sku='$sku' ORDER BY date DESC LIMIT 2";
	// echo $query."<br>";
	$result=mysql_query($query);
	$last_lot_id=mysql_result($result,0);
	if($last_lot_id==$lot_id){
		$last_lot_id=mysql_result($result,1);
	}
	// echo "LAST LOT ID: ".$last_lot_id."<BR>";
	
	// Get Last entered description
	$query="SELECT * FROM kit_description WHERE lot_id=$last_lot_id";
	// echo $query."<br>";
	$result=mysql_query($query);
	
	// Enter Last entered description
	while($description=mysql_fetch_assoc($result)){
		// INSERT INTO kit_description
		$query="INSERT INTO kit_description(lot_id, description, temp, usages, quality) VALUE('$lot_id', '".$description['description']."', '".$description['temp']."', '".$description['usages']."', '".$description['quality']."')";
		// echo $query."<br>";
		mysql_query($query) or die(mysql_error());
	}
	
	// Get Last entered components
	$query="SELECT * FROM components WHERE lot_id=$last_lot_id";
	// echo $query."<br>";
	$result=mysql_query($query);
	
	// Enter Last entered description
	while($comp=mysql_fetch_assoc($result)){ 
		// INSERT INTO components
		$query="INSERT INTO components(lot_id, comp) VALUE('$lot_id', '".$comp['comp']."')";
		// echo $query."<br>";
		mysql_query($query) or die(mysql_error());
	}
	
	// Get Last entered specs
	$query="SELECT * FROM specs WHERE lot_id=$last_lot_id";
	// echo $query."<br>";
	$result=mysql_query($query);
	
	// Enter Last entered specs
	while($spec=mysql_fetch_assoc($result)){ 
		// INSERT INTO specs
		$query="INSERT INTO specs(lot_id, title, description, ordering) VALUE('$lot_id', '".$spec['title']."', '".$spec['description']."', '".$spec['ordering']."')";
		// echo $query."<br>";
		mysql_query($query) or die(mysql_error());
	}
	
	// Get newly created spec ids
	$query="SELECT spec_id FROM specs WHERE lot_id=$lot_id";
	// echo $query."<br>";
	$result=mysql_query($query);
	
	// Enter newly created spec ids
	while($kitspec=mysql_fetch_assoc($result)){ 
		// INSERT INTO specs
		$query="INSERT INTO kits_spec(lot_id, sku, spec_id) VALUE('$lot_id', '$sku', '".$kitspec['spec_id']."')";
		// echo $query."<br>";
		mysql_query($query) or die(mysql_error());
	}
}
?>

<?php
function read_excel_file(){
	//  Include PHPExcel_IOFactory
	include '../classes/PHPExcel_1.7.9/PHPExcel.php';

	$inputFileName = '../upload/cofa.xls';

	//  Read your Excel workbook
	try {
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
	} catch(Exception $e) {
		die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
	}

	//  Get worksheet dimensions
	$sheet = $objPHPExcel->getSheet(0); 
	$highestRow = $sheet->getHighestRow(); 
	$highestColumn = $sheet->getHighestColumn();

	//  Loop through each row of the worksheet in turn
	for ($row = 2; $row <= $highestRow; $row++){ 
		//  Read a row of data into an array
		$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
										NULL,
										TRUE,
										FALSE);
										
		$date=PHPExcel_Style_NumberFormat::toFormattedString($rowData[0][0], "M/D/YYYY");
		$sku=$rowData[0][3];
		$prod_type=$rowData[0][2];
		$lot=$rowData[0][4];
		
		// echo "//////////////////////////////////////////<br/>";
		// echo "Lot: ".$lot."<BR>";
		// echo "sku: ".$sku."<BR>";
		// echo "prod_type: ".$prod_type."<BR>";
		// echo "//////////////////////////////////////////<br/>";
		
		if(str_replace(" ","",strtoupper($prod_type))=="KIT"){
			if($sku!="#VALUE!" AND $sku!=""){
				if($lot!="#VALUE!" AND $lot!=""){
					// echo "Date: ".$date."<br/>";
					// echo "Cat No: ".$sku."<br/>";
					// echo "Lot No.: ".$lot."<br/>";
					// echo "Product Type: ".$prod_type."<br/>";
					
					$result=mysql_query("SELECT k.kit_id, k.sku as ksku, l.lot_number as llot_number FROM kits k, kits_lot l WHERE k.sku='".$sku."' AND l.lot_number='".$lot."'");
					$result2=mysql_query("SELECT k.kit_id, k.sku as ksku FROM kits k WHERE k.sku='".$sku."'");
					
					// If result is found
					if(mysql_num_rows($result)>0){
						// echo "found and ";
						while($SQLrow=mysql_fetch_assoc($result)){
							// echo "retrieved: ".$SQLrow['ksku']." | ".$SQLrow['llot_number']."<br/>";
						}
					}
					// if sku is found, but not 
					else if(mysql_num_rows($result2)>0){
						// echo "sku exists, add new lot"."<BR>";
						add_new_lot($sku, $lot, $date, $prod_type);
					}
					// else create a new record
					else{
						// echo "nothing found<br/>";
						add_new_cofa($sku, $lot, $date, $prod_type);
					}
					
					// echo "===================<br/>";
				}
			}
		}
	}
}
?>