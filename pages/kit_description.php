<?php include('../include/header.php'); ?><script src="/resources/js/kit_coa.js" type="text/javascript"></script><?php	session_start();?><center>	<div id="message"></div></center><?php 	include('../processors/query.php');	$lot=$_GET['lot'];	$sku=$_GET['sku'];	$result=get_lot_description($lot, $sku);	$spec_results=get_sku_specs($sku,$lot);?>	<center>		<?php 		if($_GET['coatype']=="coa"){			$type_url="coatype=coa"; 			$cert_type="coa";		} else if($_GET['coatype']=="doc"){			$type_url="coatype=doc"; 			$cert_type="doc";		} 	?>		<a href="/<?php echo "?".$type_url; ?>" data-transition="slide" style="float: left;margin-top: 12px;font-weight: bold;color: green;">< Go Back</a>	<?php if($_SESSION['group']<2){ ?>		<a onclick="delete_warning('<?php echo $sku; ?>', '<?php echo $lot; ?>'); return false;" class="del_coa" href="/processors/delete_cofa.php?sku=<?php echo $sku; ?>&lot=<?php echo $lot; ?>">DELETE</a>	<?php } ?>	<h1 class="main_h1"><?php echo $sku." | ".$lot; ?></h1>	<div id="contact_form">	<?php while($row=mysql_fetch_assoc($result)){ ?>		<form name="edit_cofa" action="#">			<table class="cofa_table">				<tbody>						<tr>						<td colspan="4" style="border:none;">							<a class="pdf_button" href="#" style="float:left;" target="_blank" onclick="refresh(); return false;">Refresh</a>							<a class="pdf_button" href="/pages/pdf.php?lot=<?php echo $lot; ?>&sku=<?php echo $sku; ?>&view=1&cert=<?php echo $cert_type; ?>" onclick="create_pdf(this.href); return false;" style="float:right;" target="_blank">View PDF</a>							<a class="pdf_button" href="/pages/pdf.php?lot=<?php echo $lot; ?>&sku=<?php echo $sku; ?>&view=0&cert=<?php echo $cert_type; ?>" onclick="create_pdf(this.href); return false;" style="float:right;">Print PDF</a>						</td>					</tr>					<tr>						<td colspan="3" style="border:none;">							&nbsp;						</td>						<td colspan="1">							<font style="font-weight:bold;" class="td_title">Type of Certificate: </font><input type="text" id="cert_name" value=""/ style="width: 185px;">						</td>					</tr>					<tr>						<td colspan="2"><strong class="td_title">Kit Name: </strong><input name="description" id="description" style="width: 590px;" value="<?php echo $row['kdescription']; ?>"/></td>						<td style="border:none;">&nbsp;</td>						<td><strong class="td_title">Catalog Number: </strong><input style="background-color: #e9e9e9;width:185px;" name="sku" id="sku" readonly="readonly" value="<?php echo $row['ksku']; ?>"/></td>					</tr>					<tr class="empty_row"><td style="border: none;"></td></tr>					<tr>						<td colspan="2"><strong class="td_title">Lot #: </strong><input style="background-color: #e9e9e9;width:590px;" name="lot" id="lot" readonly="readonly" value="<?php echo $row['lot_number']; ?>"/></td>						<td style="border:none;">&nbsp;</td>						<td><strong class="td_title">Date: </strong><input name="date" id="date" style="width: 185px;" value="<?php echo $row['ldate']; ?>"/></td>					</tr>					<tr class="empty_row"><td style="border: none;"></td></tr>					<tr>						<td colspan="1"><strong class="td_title">Storage Temperature: </strong><input type="text" name="temp" id="temp" style="width:220px;" value="<?php echo $row['ktemp']; ?>"/></td>						<td colspan="3" style="border:none;">&nbsp;</td>					</tr>					<tr class="empty_row"><td style="border: none;"></td></tr>					<tr>						<td colspan="4"><strong class="td_title">Usage: </strong><input name="usage" id="usage" style="width: 970px;" value="<?php echo $row['kusage']; ?>"/></td>					</tr>					<tr class="empty_row"><td style="border: none;"></td></tr>					<tr>						<td colspan="4"><strong class="td_title">Kit overall Quality Checks: </strong><input name="quality" id="quality" style="width:970px;" value="<?php echo $row['kquality']; ?>"/></td>					</tr>					<tr class="empty_row"><td colspan="3" style="border: none;"></td></tr>					<?php 						$components=explode("#",$row['kcomponents']); 						$i=0;						$size=sizeof($components)-1;						if($components[$size]==""){							$size=$size-1;						} ?>					<?php if($_SESSION['group']<2){ ?>						<tr>							<td colspan="4" style="border: none;"><a class="pdf_button" id="component_button" href="#" onclick="add_component(<?php echo $size+1; ?>);return false;" style="float:right;">Add New Component</a></td>						</tr>					<?php } ?>					<tr class="tr_header">						<td style="width:370px;"><strong>Kit Components:</strong></td>						<td style="width:300px;"><strong><center>Results:</center></strong></td>						<td style="width:90px;"><strong><center>Approved for Release</center></strong></td>						<?php if($_SESSION['group']<2){ ?>							<td class="del_td"><strong>Delete</strong></td>						<?php } ?>					</tr>					<?php foreach($components as $comp){ ?>						<tr id="component_row<?php echo $i; ?>" class="comp_rows">							<?php if($comp != ""){ ?>								<td><input name="component<?php echo $i; ?>" id="component<?php echo $i; ?>" style="width: 370px;" value="<?php echo $comp; ?>"/></td>								<td><center>Pass</center></td>								<td><center>Yes</center></td>								<?php if($_SESSION['group']<2){ ?>									<td class="del_td"><a class="del_button" href="#" onclick="delete_component(<?php echo $i; ?>); return false;">Delete</a></td>								<?php } ?>								<?php $i++; ?>							<?php } ?>						</tr>					<?php } ?>					<tr id="new_component">					</tr>					<tr class="empty_row"><td colspan="3" style="border: none;"></td></tr>					<?php 						$spec_size=mysql_num_rows($spec_results)-1; 						$spec_num=0;					?>					<?php if($_SESSION['group']<2){ ?>						<tr>							<td colspan="4" style="border: none;"><a class="pdf_button" id="spec_button" href="#" onclick="add_specification(<?php echo $spec_size+1; ?>);return false;" style="float:right;">Add New Specification</a></td>						</tr>					<?php } ?>					<tr class="tr_header">						<td style="width:370px;"><strong>Specification:</strong></td>						<td><strong>Description:</strong></td>						<td><strong>Order:</strong></td>						<?php if($_SESSION['group']<2){ ?>							<td class="del_td"><strong>Delete</strong></td>						<?php } ?>					</tr>					<?php while($spec=mysql_fetch_assoc($spec_results)){ ?>						<tr id="spec_row<?php echo $spec_num; ?>">							<input type="hidden" name="spec_id<?php echo $spec_num; ?>" id="spec_id<?php echo $spec_num; ?>" value="<?php echo $spec['s_id']; ?>"/>							<td><input style="width:370px;" name='spec_title<?php echo $spec_num; ?>' id='spec_title<?php echo $spec_num; ?>' value='<?php echo $spec['stitle']; ?>'/></td>							<td><input style="width:300px;" name='spec_desc<?php echo $spec_num; ?>' id='spec_desc<?php echo $spec_num; ?>' value='<?php echo $spec['sdesc']; ?>'/></td>							<td><input style="width:90px;text-align:center;" name='spec_order<?php echo $spec_num; ?>' id='spec_order<?php echo $spec_num; ?>' value='<?php echo $spec['sorder']; ?>'/></td>							<?php if($_SESSION['group']<2){ ?>								<td class="del_td"><a href="#" class="del_button" onclick="delete_spec(<?php echo $spec_num; ?>, <?php echo $spec['s_id']; ?>); return false;">Delete</a></td>							<?php } ?>						</td>					<?php $spec_num++; } ?>					<tr id="new_specification">					</tr>					<tr class="empty_row"><td style="border: none;"></td></tr>					<tr>						<td colspan="1"><strong class="td_title">Signer:</strong><input style="background-color: #e9e9e9;width:310px;float:left;" readonly="readonly" name="signer" id="signer" value="<?php echo $row['signer']; ?>"/></td>						<?php if($_SESSION['group']<2){ ?>							<td colspan="2" style="border:none;">								&nbsp;							</td>							<td colspan="1" style="border: none;">								<input type="submit" name="submit" class="edit_button" id="submit_btn" value='SAVE' style="background-color: yellow;"/>							</td>					<?php } ?>					</tr>				</tbody>			</table>		</form>	<?php } ?>	</div>	</center>	<?php include('../include/footer.php'); ?>