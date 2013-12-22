<?php 
	include_once('../processors/query.php');
	require('../classes/fpdf17/fpdf.php');
	$sku=$_GET['sku'];
	$lot=$_GET['lot'];
	$view=$_GET['view'];
	$cert_name=$_GET['cert'];
	$test=$_FILES;
	
	$result=get_lot_description($lot,$sku);
	$spec_results=get_sku_specs($sku,$lot);

	while($info=mysql_fetch_assoc($result)){
		$description=iconv('UTF-8', 'windows-1252', stripslashes($info['kdescription']));
		
		$date=$info['ldate'];
		$date_arr=explode('-',$date);
		$date=$date_arr[1]."/".$date_arr[2]."/".$date_arr[0];
		
		$temp=iconv('UTF-8', 'windows-1252', stripslashes($info['ktemp']));
		$usage=$info['kusage'];
		$quality=$info['kquality'];
		$component=iconv('UTF-8', 'windows-1252', stripslashes($info['kcomponents']));
		$signer=$info['signer'];
		$position=$info['position'];
		$signature=$info['signature'];
	}
	$components=explode("#",$component);
	
	class PDF extends FPDF{
		function header(){
			global $description, $cert_name, $test;
			
			// logo
			$this->Image('../resources/images/logo.png',75,8,60);
			// Line Break
			$this->Ln(28);
			
			$this->SetFillColor(52,139,84);
			$this->Cell(0,0.2," ",0,0,'L',true);
			$this->Ln(3);

			$this->SetFont('Arial','',18);
			if($cert_name==""){
				$this->Cell(0,10,"Certificate of Analysis ",0,0,'C');
			}
			else{
				$this->Cell(0,10,"Certificate of ".$cert_name,0,0,'C');
			}
			$this->Ln(15);
			
			// Arial 16, Bold
			$this->SetFont('Arial','B',16);
			$this->SetTextColor(255,255,255);
			// Background color
			$this->SetFillColor(52,139,84);
			$this->Cell(0,10,"  $description",0,1,'L',true);
		}
		function Footer(){	
			global $signer, $position, $signature;
			
			// Signature
			$this->Image('http://cofa.zymoresearch.com/'.$signature,10,255,50);
			// Line Break
			//$this->Ln(20);
			
			// Position at 2.0 cm from bottom
			$this->SetY(-58);
			
			//$this->Cell(3);
			$this->SetFont('Arial','',11);
			$this->Cell(0,10,"Zymo Research Corp. certifies that each component of this kit has been tested to meet the specifications");
			$this->Ln(5);	
			//$this->Cell(3);
			$this->Cell(0,10,"set forth by Zymo Research Corp.");
			$this->Ln(22);	
			
			// Arial 12
			$this->SetFont('Arial','',11);
			$this->Cell(0,10,"$signer");
			$this->Ln(1);
			$this->Cell(0,18,"$position");
			$this->Ln(15);
		
			// Arial 10
			$this->SetFont('Arial','',10);
			// Move 58 cm to the right
			$this->Cell(58);
			$this->Cell(2,10,"17062 Murphy Ave.");
			$this->Cell(1,18,"Irvine, CA 92614");
			// Move additional 35 cm to the right
			$this->Cell(35);
			$this->Cell(5,10,"Toll Free: (888) 882-9682");
			$this->Cell(1,18,"Fax: (949) 266-9452");
		}
		function LotDescription(){
			global $sku, $lot, $date, $temp;
			$this->Cell(3);
			
			// Catalog Number
			$this->SetFont('Arial','B',11);
			$this->Cell(50,10,"Cat. No.:");
			$this->SetFont('Arial','',11);
			$this->Cell(50,10,"$sku");
			
			// Lot Number
			$this->SetFont('Arial','B',11);
			$this->Cell(30,10,"Lot No.:");
			$this->SetFont('Arial','',11);
			$this->Cell(70,10,"$lot");
			
			$this->Ln(6);
			$this->Cell(3);
			
			// Assembly Date
			$this->SetFont('Arial','B',11);
			$this->Cell(50,10,"Assembly Date:");
			$this->SetFont('Arial','',11);
			$this->Cell(50,10,"$date");
			
			$this->Ln(6);
			$this->Cell(3);
			
			// Storage Temperature
			if(strpos($temp,'-')>-1){ $msg="(see protocol for component specific storage conditions)"; }
			
			$this->SetFont('Arial','B',11);
			$this->Cell(50,10,"Storage Temperature:");
			$this->SetFont('Arial','',11);
			$this->Cell(70,10,"$temp $msg");		

			$this->Ln(10);
			$this->Cell(3);
			
			// Intended Use
			$this->SetFont('Arial','B',11);
			$this->Cell(27,10,"Intended Use:");
			$this->SetFont('Arial','',11);
			$this->Cell(70,10,"All kit contents and parts are intended for research use only.  Not for diagnostic use.");	

			$this->Ln(13);			
		}
		function KitQuality(){
			global $quality;
		
			// Arial 16, Bold
			$this->SetFont('Arial','B',16);
			$this->SetTextColor(255,255,255);
			// Background color
			$this->SetFillColor(52,139,84);
			$this->Cell(0,10,"  Kit Overall Quality",0,0,'L',true);
		
			$this->Ln(10);
			$this->Cell(3);
			// Font Color - Black
			$this->SetTextColor(0,0,0);
			
			// Criteria
			$this->SetFont('Arial','B',11);
			$this->Cell(17,10,"Criteria:");
			$this->SetFont('Arial','',11);
			$this->Cell(70,10,"$quality");
			$this->Ln(13);				
		}
		function FancyTable($header, $data){
			$this->Cell(3);
			// Colors, line width and bold font
			//$this->SetFillColor(255,255,255);
			$this->SetFillColor(255,255,255);
			$this->SetDrawColor(192,192,192);
			$this->SetLineWidth(.3);
			$this->SetFont('','B');
			// Header
			$w = array(84, 35, 65);
			for($i=0;$i<count($header);$i++){
				if($i!=0){ $align='C'; } else{ $align='L'; }
				$this->Cell($w[$i],7,' '.$header[$i],1,0,$align,true);
			}
			$this->Ln();
			//$this->SetTextColor(255,255,255);
			
			// Color and font restoration
			$this->SetFillColor(203,225,150);
			$this->SetTextColor(0);
			
			if(sizeof($data)>10){
				$this->SetFont('Arial','',10);
				$height=5;
			}
			else{
				$this->SetFont('');
				$height=6;
			}
			
			
			// Data
			$fill = false;
			$x = $this->GetX()+3;
			$y = $this->GetY();
			$i = 0;
			
			foreach($data as $row)
			{
				if($row!=""){
					$this->Cell(3);
					$y1 = $this->GetY();
					$this->MultiCell($w[0],$height,''.$row,'LR','L');
					$y2 = $this->GetY();
					$yH = $y2 - $y1;					
					$this->SetXY($x + $w[0], $this->GetY() - $yH);
					$this->Cell($w[1],$yH,"Pass",'LR',0,'C');
					$this->Cell($w[2],$yH,"Yes",'LR',0,'C');

					$this->Ln();
					$this->Cell(3);
					$this->Cell(array_sum($w),0,'','T');
					$this->Ln();
					$fill = !$fill;
				}
				else{
					break;
				}
			}
			// Closing line
			
			$this->Cell(3);
			$this->Cell(array_sum($w),0,'','T');
			$this->Ln(3);
		}
		function Specifications(){
			global $spec_results;
			
			while($spec=mysql_fetch_assoc($spec_results)){
				$this->SetFont('Arial','B',11);
				$title_width=$this->GetStringWidth($spec['stitle'])-1;
				$this->Cell(3);
				$this->Cell($title_width,10,$spec['stitle'],0,0,'L');
				$this->SetFont('Arial','',11);
				$this->Cell(3);
				$this->Cell(0,10,$spec['sdesc']);
				$this->Ln(7);
			}
		}
		function create_cofa(){
			global $sku, $lot, $description, $date, $temp, $usage, $quality, $components;
			$table_header=array('Kit Component','Results','Approved for Release');
			
			$this->AddPage();
			$this->LotDescription();
			$this->KitQuality();
			$this->FancyTable($table_header, $components);
			$this->Specifications();
		}
	}

	$pdf = new PDF();
	$pdf->SetTitle($sku." | ".$lot." Certificate of Analysis");
	$pdf->SetAuthor("Zymo Research");
	$pdf->create_cofa();
	if($view==1){
		$pdf->Output('['.$sku.'] CoA - Lot '.$lot.'.pdf', 'I');
	} else if($view==0){
		$pdf->Output('['.$sku.'] CoA - Lot '.$lot.'.pdf', 'D');
	}
?>