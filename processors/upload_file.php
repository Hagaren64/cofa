<?php include("insert_cofa.php"); ?>

<?php
if(file_exists("/var/www/html/cofa/upload/cofa.xls")){
	// echo "EXISTS!<BR>";
	unlink ("/var/www/html/cofa/upload/cofa.xls");
}

if ($_FILES["file"]["error"] > 0){
	// echo "Error: " . $_FILES["file"]["error"] . "<br>";
	//header("Location: /");
}
else{
	// echo "Upload: " . $_FILES["file"]["name"] . "<br>";
	// echo "Type: " . $_FILES["file"]["type"] . "<br>";
	$extension = end(explode('.', $_FILES['file']['name']));
	// echo "Extension: " . $extension = end(explode('.', $_FILES['file']['name']));
	// echo "<br>Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
	// echo "Stored in: " . $_FILES["file"]["tmp_name"] . "<br>";
	move_uploaded_file($_FILES["file"]["tmp_name"], "/var/www/html/cofa/upload/cofa.".$extension);
	// echo "/var/www/html/cofa/upload/cofa.".$extension."<BR>";
	// echo $HTTP_POST_FILES['ufile']['tmp_name'];
	  
	read_excel_file();
	  	  
	header("Location: /");
}
?>