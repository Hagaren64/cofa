<?php include_once("include/header.php"); ?>
<?php include('processors/query.php'); ?>

	<?php if($logged_in){ ?>
		<h1 class="main_h1">CoA - Kits</h1>

		<div style="width:290px;border: 1px solid lightgray;margin-bottom: 10px;padding: 7px; margin-top: -80px;background-color:#FFFBDA;border-radius:7px;">
			<form action="/processors/upload_file.php" method="post" enctype="multipart/form-data" data-ajax="false">
			<label for="file">Upload Excel File</label>
			<input type="file" name="file" id="file">
			<input type="submit" name="submit" value="Submit">
			</form>
			<p style="margin-top: 5px;color: white;font-weight: 120%;background-color: #C74545;padding: 5px;text-align: center;margin-bottom: 5px;">Must be in .xls format</p>
		</div>
				
			<?php $result=get_kits(); ?>	
		
		<form name="download_cofa" action="#">
			<!-- http://stackoverflow.com/questions/2871783/generate-multiple-pdfs-using-fpdf-class -->
			<!--<input type="submit" name="submit" class="pdf_button" id="download_all" value='Download All *not functional*' style="margin-top: -52px; cursor: pointer;"/>-->
			<!--<a href="#" class="pdf_button" style="margin-top: -52px;">Download All *not functional*</a>-->
		
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
				<thead>
					<tr>
						<th>Lot #</th>
						<th>Catalog #</th>
						<th>Description</th>
						<th>Date</th>
					</tr>
				</thead>
				<tbody>
					<?php while($row = mysql_fetch_assoc($result)){ ?>
						<tr>
							<td><a data-transition="slide" href="/pages/kit_description.php?lot=<?php echo $row['lot_number']; ?>&sku=<?php echo $row['ksku'] ?>"><?php echo $row['lot_number']; ?></a></td>
							<td><?php echo $row['ksku']; ?></td>
							<td><?php echo $row['kdescription']; ?></td>
							<td><?php echo $row['ldate']; ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
		
		<script src="/resources/js/jquery.dataTables.min.js" type="text/javascript"></script>
		<link type="text/css" rel="stylesheet" href="/resources/style/demo_page.css">
		<link type="text/css" rel="stylesheet" href="/resources/style/demo_table.css">
		<link type="text/css" rel="stylesheet" href="/resources/style/jquery.dataTables.css">
		<link type="text/css" rel="stylesheet" href="/resources/style/jquery.dataTables_themeroller.css">
		<link type="text/css" rel="stylesheet" href="/resources/style/jquery-ui-1.8.4.custom.css">
	<?php } ?>

<?php include_once("include/footer.php"); ?>