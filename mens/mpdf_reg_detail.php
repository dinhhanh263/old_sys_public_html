<?php

ini_set( 'include_path', dirname(__FILE__) . "/php-lib/fpdf" );
require_once( 'mbfpdf.php');

header('Content-type: application/pdf');
header('Content-disposition: attachment; filename="downloaded.pdf"');



$title = "ご購入明細・領収書";


$msg = "このたびはご購入いただきありがとうございます。\n下記の内容にてご請求させていただきます。\nご確認くださいますよう、お願いいたします。";

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
$pdf->Text( 76 , 27  , mb_convert_encoding( $title, "SJIS", "UTF-8" ) );

$pdf->SetFont( GOTHIC,'' , 14 );
$pdf->Text( 24 , 40  , mb_convert_encoding( $_GET['name']."　様", "SJIS-win", "UTF-8" ) );

$pdf->SetXY( 24, 55 );
$pdf->SetFont( GOTHIC,'' , 8 );
$pdf->MultiCell( 80 , 4  , mb_convert_encoding( $msg, "SJIS", "UTF-8" ) ,0);


//$pdf->Image("logo.png",140,40,20); //横幅のみ指定
$pdf->Image("./admin/images/shared/logo.png",140,35,20); //横幅のみ指定,24

$pdf->SetXY( 140, 45 );
$pdf->SetFont( GOTHIC,'' , 8 );
$pdf->MultiCell( 80 , 4  , mb_convert_encoding( $shop, "SJIS", "UTF-8" ) ,0);

$pdf->SetFont( GOTHIC, '', 16 );//underline
$pdf->Text( 20 , 83  , mb_convert_encoding( "請求金額 ： ￥".number_format($_GET['payment'] + $_GET['option_price'])."円(税込)", "SJIS", "UTF-8" ) );
$pdf->Line(20, 85, 100, 85);

$pdf->SetFillColor(238, 233, 233);
$pdf->Rect( 15 ,90 ,180 ,10, 'DF');
$pdf->SetFont( GOTHIC, '', 16 );
$pdf->Text( 20 , 97  , mb_convert_encoding( "ご購入明細", "SJIS", "UTF-8" ) );
$pdf->SetFont( GOTHIC, '', 12 );
$pdf->Text( 20 , 110  , mb_convert_encoding( "お支払期日 ： ".date("Y/m/d H:i:s"), "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 115 );
$w1 = 150;
$w2 = 30;
// 契約部位がある場合、部位名を表示する
if($_GET['contract_part'])$_GET['contract_part'] = "（". $_GET['contract_part']. " ）";
if($_GET['contract_part2'])$_GET['contract_part2'] = "（". $_GET['contract_part2']. " ）";
if($_GET['contract_part3'])$_GET['contract_part3'] = "（". $_GET['contract_part3']. " ）";
if($_GET['contract_part4'])$_GET['contract_part4'] = "（". $_GET['contract_part4']. " ）";
if($_GET['contract_part5'])$_GET['contract_part5'] = "（". $_GET['contract_part5']. " ）";

// コース金額
if($_GET['fixed_price']) $_GET['fixed_price']   = "￥".number_format($_GET['fixed_price']);
if($_GET['fixed_price2']) $_GET['fixed_price2'] = "￥".number_format($_GET['fixed_price2']);
if($_GET['fixed_price3']) $_GET['fixed_price3'] = "￥".number_format($_GET['fixed_price3']);
if($_GET['fixed_price4']) $_GET['fixed_price4'] = "￥".number_format($_GET['fixed_price4']);
if($_GET['fixed_price5']) $_GET['fixed_price5'] = "￥".number_format($_GET['fixed_price5']);
// オプション
if($_GET['discount']){
	$discount_name    = "割引　";
	$_GET['discount'] = "￥".number_format($_GET['discount']);
} else {
	$discount_name    ="";
	$_GET['discount'] ="";
}
if($_GET['discount2']){
	$discount_name2    = "割引　";
	$_GET['discount2'] = "￥".number_format($_GET['discount2']);
} else {
	$discount_name2    ="";
	$_GET['discount2'] ="";
}
if($_GET['discount3']){
	$discount_name3    = "割引　";
	$_GET['discount3'] = "￥".number_format($_GET['discount3']);
} else {
	$discount_name3    ="";
	$_GET['discount3'] ="";
}
if($_GET['discount4']){
	$discount_name4    = "割引　";
	$_GET['discount4'] = "￥".number_format($_GET['discount4']);
} else {
	$discount_name4    ="";
	$_GET['discount4'] ="";
}
if($_GET['discount5']){
	$discount_name5    = "割引　";
	$_GET['discount5'] = "￥".number_format($_GET['discount5']);
} else {
	$discount_name5    ="";
	$_GET['discount5'] ="";
}
// オプション
if($_GET['option_price']){
	$_GET['option_name'] = "オプション代(".($_GET['option_name']).")";
	$option_price  = "￥".number_format($_GET['option_price']);
	$option_filtering    = "(オプション代除く)";
} else {
	$option_price = "";
	$_GET['option_name']   = "";
}

$pdf->Cell($w1, 10, mb_convert_encoding( "コース名", "SJIS", "UTF-8" ), 1,0,'C',1);
$pdf->Cell($w2, 10, mb_convert_encoding( "金額(税込)", "SJIS", "UTF-8" ), 1, 1,'C',1);
$pdf->SetXY( 15, 125 );
$pdf->Cell($w1, 10, mb_convert_encoding( $_GET['course_name'].$_GET['contract_part'], "SJIS", "UTF-8" ), 1);
$pdf->Cell($w2, 10, mb_convert_encoding( $_GET['fixed_price'], "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 135 );
$pdf->Cell($w1, 10, mb_convert_encoding( $discount_name, "SJIS", "UTF-8" ),  1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( $_GET['discount'], "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 145 );
$pdf->Cell($w1, 10, mb_convert_encoding( $_GET['course_name2'].$_GET['contract_part2'], "SJIS", "UTF-8" ), 1);
$pdf->Cell($w2, 10, mb_convert_encoding( $_GET['fixed_price2'], "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 155 );
$pdf->Cell($w1, 10, mb_convert_encoding( $discount_name2, "SJIS", "UTF-8" ),  1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( $_GET['discount2'], "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 165 );
$pdf->Cell($w1, 10, mb_convert_encoding( $_GET['course_name3'].$_GET['contract_part3'], "SJIS", "UTF-8" ), 1);
$pdf->Cell($w2, 10, mb_convert_encoding( $_GET['fixed_price3'], "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 175 );
$pdf->Cell($w1, 10, mb_convert_encoding( $discount_name3, "SJIS", "UTF-8" ),  1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( $_GET['discount3'], "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 185 );
$pdf->Cell($w1, 10, mb_convert_encoding( $_GET['course_name4'].$_GET['contract_part4'], "SJIS", "UTF-8" ), 1);
$pdf->Cell($w2, 10, mb_convert_encoding( $_GET['fixed_price4'], "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 195 );
$pdf->Cell($w1, 10, mb_convert_encoding( $discount_name4, "SJIS", "UTF-8" ),  1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( $_GET['discount4'], "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 205 );
$pdf->Cell($w1, 10, mb_convert_encoding( $_GET['course_name5'].$_GET['contract_part5'], "SJIS", "UTF-8" ), 1);
$pdf->Cell($w2, 10, mb_convert_encoding( $_GET['fixed_price5'], "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 215 );
$pdf->Cell($w1, 10, mb_convert_encoding( $discount_name4, "SJIS", "UTF-8" ),  1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( $_GET['discount5'], "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 225 );
$pdf->Cell($w1, 10, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,'C',1);
$pdf->Cell($w2, 10, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1, 1,'C',1);
$pdf->SetXY( 15, 235 );
$pdf->Cell($w1, 10, mb_convert_encoding( "契約金合計".$option_filtering." ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($_GET['price']), "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 245 );
$pdf->Cell($w1, 10, mb_convert_encoding( $_GET['option_name']." ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( $option_price, "SJIS", "UTF-8" ), 1, 1,'R');

// $pdf->SetXY( 15, 220 );
// $pdf->Cell($w1, 10, mb_convert_encoding( "内税(". ($_GET['tax']*100)."%) ", "SJIS", "UTF-8" ), 1,0,'R');
// $pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($_GET['price']-$_GET['price']/$_GET['tax2']), "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 255 );
$pdf->Cell($w1, 10, mb_convert_encoding( "本日支払合計  ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($_GET['payment']+$_GET['payment_loan_kari']+$_GET['option_price']), "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, 265 );
$pdf->Cell($w1, 10, mb_convert_encoding( "契約残金  ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($_GET['balance']-$_GET['payment_loan_kari']), "SJIS", "UTF-8" ), 1, 1,'R');

$pdf->Output();
?>
