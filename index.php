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
				<option value="/?coatype=coa" <?php if($coa_type=="coa"){ echo "selected"; } ?>>Certificate of Analysis</option>
				<option value="/?coatype=doc" <?php if($coa_type=="doc"){ echo "selected"; } ?>>Declaration of Conformity</option>
			</select>
		</div>
		
		<?php include('pages/kit_list.php'); ?>		
		
	<?php } ?>

<?php include_once("include/footer.php"); ?>