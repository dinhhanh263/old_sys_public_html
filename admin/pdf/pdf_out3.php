<?php
require_once(dirname(__FILE__) . '/../../php-lib/fpdf/mbfpdf.php');
require_once(dirname(__FILE__) . '/../../admin/library/pdf/pdf_out.php');

header('Content-type: application/pdf');
header('Content-disposition: attachment; filename="downloaded.pdf"');

$title = "プラン組替処理明細・領収書";
$msg = "このたびはプラン組替いただきありがとうございます。\n下記の内容にてご請求させていただきます。\nご確認くださいますよう、お願いいたします。";


$pdf=new MBFPDF();
//$pdf->FPDF(Portrait ,mm,A4);//Landscape
$pdf->AddMBFont( GOTHIC , 'SJIS' );
$pdf->AddMBFont( PGOTHIC, 'SJIS' );
$pdf->AddMBFont( MINCHO , 'SJIS' );
$pdf->AddMBFont( PMINCHO, 'SJIS' );
$pdf->AddMBFont( KOZMIN , 'SJIS' );
$pdf->Open();
$pdf->AddPage();
//$pdf->SetFont( GOTHIC, 'U', 20 );//underline
//$pdf->SetFont( GOTHIC, 'A', 20 );//download

$pdf->Rect(15 ,20 ,180 ,10);
$pdf->SetFont( GOTHIC,'' , 18 );
//$pdf->Write( 10, mb_convert_encoding( $msg, "SJIS", "UTF-8" ) );
$pdf->Text( 60 , 27  , mb_convert_encoding( $title, "SJIS", "UTF-8" ) );

$pdf->SetFont( GOTHIC,'' , 14 );
$pdf->Text( 24 , 45  , mb_convert_encoding( $customer_name."　様", "SJIS-win", "UTF-8" ) );

$pdf->SetXY( 24, 60 );
$pdf->SetFont( GOTHIC,'' , 8 );
$pdf->MultiCell( 80 , 4  , mb_convert_encoding( $msg, "SJIS", "UTF-8" ) ,0);


$pdf->Image("img/logo.png",140,40,20); //横幅のみ指定

$pdf->SetXY( 140, 50 );
$pdf->SetFont( GOTHIC,'' , 8 );
$pdf->MultiCell( 80 , 4  , mb_convert_encoding( $shop_info, "SJIS", "UTF-8" ) ,0);

$pdf->SetFont( GOTHIC, '', 16 );//underline
$pdf->Text( 20 , 96  , mb_convert_encoding( "請求金額 ： ￥".number_format($new_contract['price'])."(税込)", "SJIS", "UTF-8" ) );
$pdf->Line(20, 98, 100, 98);

$pdf->SetFillColor(238, 233, 233);
$pdf->Rect( 15 ,105 ,180 ,10, 'DF');
$pdf->SetFont( GOTHIC, '', 16 );
$pdf->Text( 20 , 112  , mb_convert_encoding( "プラン組替処理明細", "SJIS", "UTF-8" ) );
$pdf->SetFont( GOTHIC, '', 12 );
$pdf->Text( 20 , 125  , mb_convert_encoding( "処理日 ： ".date("Y/m/d H:i:s"), "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 130 );
$w1 = 140;
$w2 = 40;
$pdf->Cell($w1, 10, mb_convert_encoding( "コース名 ", "SJIS", "UTF-8" ), 1,0,'C',1);
$pdf->Cell($w2, 10, mb_convert_encoding( "金額(税込)", "SJIS", "UTF-8" ), 1, 1,'C',1);

$pdf->SetXY( 15, 140 );
$pdf->Cell($w1, 10, mb_convert_encoding( "旧：".$old_course['name'], "SJIS", "UTF-8" ), 1);
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($contract['fixed_price']), "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 150 );
$pdf->Cell($w1, 10, mb_convert_encoding( "値引き ", "SJIS", "UTF-8" ),  1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($contract['discount']), "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 160 );
$pdf->Cell($w1, 10, mb_convert_encoding( "商品金額（旧) ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($contract['price']), "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 170 );
$pdf->Cell($w1, 10, mb_convert_encoding( "支払済金額  ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($contract['price'] - $contract['balance']), "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 180 );
$pdf->Cell($w1, 10, mb_convert_encoding( "消化回数 ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( $contract['r_times'], "SJIS", "UTF-8" ), 1, 1,'R');

$pdf->SetXY( 15, 190 );
$pdf->Cell("180", 5, " ", 1,0,'R');

$pdf->SetXY( 15, 195 );
$pdf->Cell($w1, 10, mb_convert_encoding( "新：".$new_course['name'], "SJIS", "UTF-8" ), 1);
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($new_contract['fixed_price']), "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 205 );
$pdf->Cell($w1, 10, mb_convert_encoding( "請求金額 ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($new_contract['price']), "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 215 );
$pdf->Cell($w1, 10, mb_convert_encoding( "入金金額 ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($new_contract['payment']), "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 225 );
$pdf->Cell($w1, 10, mb_convert_encoding( "残金  ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($new_contract['balance']), "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 235 );
$pdf->Cell($w1, 10, mb_convert_encoding( "消化回数 ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( $contract['r_times'], "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 245 );
$pdf->Cell($w1, 10, mb_convert_encoding( "消化単価 ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($new_per_price), "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 255 );
$pdf->Cell($w1, 10, mb_convert_encoding( "消化金額 ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($new_used_price), "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 265 );
$pdf->Cell($w1, 10, mb_convert_encoding( "未消化金額 ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($new_remained_price), "SJIS", "UTF-8" ), 1, 1,'R');

$pdf->Output();
?>
