<?php include_once("include/header.php"); ?>
<?php include('processors/query.php'); ?>

	<?php if($logged_in){ ?>
		<h1 class="main_h1">CoA - Kits</h1>

		<div class="upload_div">
			<form action="/processors/upload_file.php" method="post" enctype="multipart/form-data" data-ajax="false">
			<label for="file">Upload Excel File</label>
			<input type="file" name="file" id="file">
			<input type="submit" name="submit" value="Submit">
			</form>
			<p style="margin-top: 5px;color: white;font-weight: 120%;background-color: #C74545;padding: 5px;text-align: center;margin-bottom: 5px;">Must be in .xls format</p>
		</div>
		
		<?php $coa_type=$_GET['coatype']; ?>
		
		<div class="coa_type">
			<p>Type of Certificate:</p>
			<select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
				<option value="" <?php if(!isset($coa_type)){ echo "selected"; } ?>></option>
				<option value="/?coatype=coa" <?php if($coa_type=="coa"){ echo "selected"; } ?>>Certificate of Authenticity</option>
				<option value="/?coatype=doc" <?php if($coa_type=="doc"){ echo "selected"; } ?>>Declaration of Conformity</option>
			</select>
		</div>
				
		<?php $result=get_kits(); ?>	
		<?php if($coa_type=="coa"){ $type_url="&coatype=coa"; } else if($coa_type=="doc"){ $type_url="&coatype=doc"; } ?>
		
		<form name="download_cofa" action="#">
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
							<td><a data-transition="slide" href="/pages/kit_description.php?lot=<?php echo $row['lot_number']; ?>&sku=<?php echo $row['ksku'] ?><?php echo $type_url; ?>"><?php echo $row['lot_number']; ?></a></td>
							<td><?php echo $row['ksku']; ?></td>
							<td><?php echo $row['kdescription']; ?></td>
							<td><?php echo $row['ldate']; ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
		
		
	<?php } ?>

<?php include_once("include/footer.php"); ?>