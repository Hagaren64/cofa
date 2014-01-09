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