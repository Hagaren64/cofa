<?php
	$filenames = array(
		"http://cofa.zymoresearch.com/pages/create_pdf.php?lot=ZRC174316&sku=D6005&view=0&cert=",
		"http://cofa.zymoresearch.com/pages/create_pdf.php?lot=ZRC174317&sku=D4001&view=0&cert=",
		"http://cofa.zymoresearch.com/pages/create_pdf.php?lot=ZRC174325&sku=D4016&view=0&cert="
	);

	
	require_once('../classes/pclzip-2-8-2/pclzip.lib.php');
	$archive = new PclZip('archive.zip');
	foreach($filenames as $filename) {
		$result = $archive->add($filename);
		if($result==0) {
			die ("Error: " . $archive->errorInfo(true));
		}
	}
	header("Content-type: application/octet-stream");
	header("Content-disposition: attachment; filename=archive.zip");
	readfile("archive.zip");
?>