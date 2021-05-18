<?php

ini_set( 'include_path', dirname(__FILE__) . "/php-lib/fpdf" );
require_once( 'mbfpdf.php');

header('Content-type: application/pdf');
header('Content-disposition: attachment; filename="downloaded.pdf"');

// えらべる部位
if($_GET['old_contract_part'])$old_contract_part = "(".$_GET['old_contract_part'].")"; // 旧契約部位
if($_GET['new_contract_part'])$new_contract_part = "(".$_GET['new_contract_part'].")"; // 新契約部位

$title = "プラン変更処理明細・領収書";

$msg = "このたびはプラン変更をいただきありがとうございます。\n下記の内容にてご請求させていただきます。\nご確認くださいますよう、お願いいたします。";

$shop = "http://www.kireimo.jp/mens/\n\n株式会社カレント\nMEN'S KIREIMO ".$_GET['shop_name']."\n〒".$_GET['shop_zip']."\n".$_GET['shop_pref']."".$_GET['shop_address1']."\n".$_GET['shop_address2']."\nTEL:".$_GET['shop_tel']."\nEmail:mens.info@kireimo.jp";

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
// $pdf->Text( 24 , 45  , mb_convert_encoding( $_GET['name']."　様", "SJIS-win", "UTF-8" ) );
$pdf->Text( 24 , 45  , mb_convert_encoding( $_GET['name']."　様", "SJIS-win", "UTF-8" ) );

$pdf->SetXY( 24, 60 );
$pdf->SetFont( GOTHIC,'' , 8 );
$pdf->MultiCell( 80 , 4  , mb_convert_encoding( $msg, "SJIS", "UTF-8" ) ,0);


// $pdf->Image("logo.png",140,40,20); //横幅のみ指定
$pdf->Image("./admin/images/shared/logo.png",140,40,20); //横幅のみ指定

$pdf->SetXY( 140, 50 );
$pdf->SetFont( GOTHIC,'' , 8 );
$pdf->MultiCell( 80 , 4  , mb_convert_encoding( $shop, "SJIS", "UTF-8" ) ,0);

$pdf->SetFont( GOTHIC, '', 16 );//underline
$pdf->Text( 20 , 96  , mb_convert_encoding( "請求金額 ： ￥".number_format($_GET['new_price'])."円(税込)", "SJIS", "UTF-8" ) );
$pdf->Line(20, 98, 100, 98);

$pdf->SetFillColor(238, 233, 233);
$pdf->Rect( 15 ,105 ,180 ,10, 'DF');
$pdf->SetFont( GOTHIC, '', 16 );
$pdf->Text( 20 , 112  , mb_convert_encoding( "プラン変更処理明細", "SJIS", "UTF-8" ) );
$pdf->SetFont( GOTHIC, '', 12 );
$pdf->Text( 20 , 125  , mb_convert_encoding( "期日 ： ".date("Y/m/d H:i:s"), "SJIS", "UTF-8" ) );


$pdf->SetXY( 15, 140 );
$w1 = 140;
$w2 = 40;
$pdf->Cell($w1, 10, mb_convert_encoding( "コース名 ", "SJIS", "UTF-8" ), 1,0,'C',1);
$pdf->Cell($w2, 10, mb_convert_encoding( "金額(税込)", "SJIS", "UTF-8" ), 1, 1,'C',1);
$pdf->SetXY( 15, 150 );
$pdf->Cell($w1, 10, mb_convert_encoding( "旧：".$_GET['old_course_name'].$old_contract_part, "SJIS", "UTF-8" ), 1);
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($_GET['fixed_price']), "SJIS", "UTF-8" ), 1, 1,'R');
// $pdf->SetXY( 15, 160 );
// $pdf->Cell($w1, 10, mb_convert_encoding( "値引き ", "SJIS", "UTF-8" ),  1,0,'R');
// $pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($_GET['discount']), "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 160 );
$pdf->Cell($w1, 10, mb_convert_encoding( "商品金額（旧) ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($_GET['price']), "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 170 );
$pdf->Cell($w1, 10, mb_convert_encoding( "支払済金額  ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($_GET['payed_price']), "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 180 );
$pdf->Cell($w1, 10, mb_convert_encoding( "消化回数 ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( $_GET['r_times']. '/' .$_GET['times'], "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 190 );
$pdf->Cell($w1, 10, mb_convert_encoding( "消化単価 ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($_GET['per_price']), "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 200 );
$pdf->Cell($w1, 10, mb_convert_encoding( "消化金額 ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($_GET['usered_price']), "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 210 );
$pdf->Cell($w1, 10, mb_convert_encoding( "未消化金額 ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($_GET['remained_price']), "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 220 );
$pdf->Cell($w1, 10, mb_convert_encoding( $_GET['new_course_name'].$new_contract_part, "SJIS", "UTF-8" ), 1);
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($_GET['new_fixed_price']), "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 230 );
$pdf->Cell($w1, 10, mb_convert_encoding( "請求金額 ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($_GET['new_price']), "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 240 );
$pdf->Cell($w1, 10, mb_convert_encoding( "入金金額 ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($_GET['payment']), "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 250 );
$pdf->Cell($w1, 10, mb_convert_encoding( "売掛金 ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($_GET['balance']), "SJIS", "UTF-8" ), 1, 1,'R');

$pdf->Output();
?>
