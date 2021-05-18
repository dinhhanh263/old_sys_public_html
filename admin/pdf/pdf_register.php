<?php
require_once(dirname(__FILE__) . '/../../php-lib/fpdf/mbfpdf.php');
require_once(dirname(__FILE__) . '/../../admin/library/pdf/pdf_out.php');

header('Content-type: application/pdf');
header('Content-disposition: attachment; filename="downloaded.pdf"');

$title = "ご購入明細・領収書";
$msg = "このたびはご購入いただきありがとうございます。\n下記の内容にてご請求させていただきます。\nご確認くださいますよう、お願いいたします。";


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
$pdf->Text( 24 , 45  , mb_convert_encoding( $customer_name."　様", "SJIS-win", "UTF-8" ) );

$pdf->SetXY( 24, 60 );
$pdf->SetFont( GOTHIC,'' , 8 );
$pdf->MultiCell( 80 , 4  , mb_convert_encoding( $msg, "SJIS", "UTF-8" ) ,0);


$pdf->Image("img/logo.png",140,40,20); //横幅のみ指定

$pdf->SetXY( 140, 50 );
$pdf->SetFont( GOTHIC,'' , 8 );
$pdf->MultiCell( 80 , 4  , mb_convert_encoding( $shop_info, "SJIS", "UTF-8" ) ,0);

$pdf->SetFont( GOTHIC, '', 16 );//underline
$pdf->Text( 20 , 96  , mb_convert_encoding( "請求金額 ： ￥".number_format($option_price), "SJIS", "UTF-8" ) );
$pdf->Line(20, 98, 100, 98);

$pdf->SetFillColor(238, 233, 233);
$pdf->Rect( 15 ,105 ,180 ,10, 'DF');
$pdf->SetFont( GOTHIC, '', 16 );
$pdf->Text( 20 , 112  , mb_convert_encoding( "ご購入明細", "SJIS", "UTF-8" ) );
$pdf->SetFont( GOTHIC, '', 12 );
$pdf->Text( 20 , 125  , mb_convert_encoding( "お支払日 ： ".$sales['reg_date'], "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 140 );
$w1 = 140;
$w2 = 40;
$target_y = 160; //商品欄の座標
$pdf->Cell($w1, 10, mb_convert_encoding( "商品名", "SJIS", "UTF-8" ), 1,0,'C',1);
$pdf->Cell($w2, 10, mb_convert_encoding( "金額(税込)", "SJIS", "UTF-8" ), 1, 1,'C',1);

$pdf->SetXY( 15, 150 );
for ($i = 0; $i < count($product_stocks); $i++) {
  $pdf->Cell($w1, 10, mb_convert_encoding( $product_name[$i]. "￥".number_format($product_fixed_price[$i])."×".$product_count[$i], "SJIS", "UTF-8" ), 1);
  $pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($product_price[$i]), "SJIS", "UTF-8" ), 1, 1,'R');
  $pdf->SetXY( 15, $target_y + ($i*10) );
}

// $pdf->Cell($w1, 10, mb_convert_encoding( $_GET['product_name0'].$_GET['product_count0'], "SJIS", "UTF-8" ), 1);
// $pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($_GET['fixed_price0']), "SJIS", "UTF-8" ), 1, 1,'R');
// $pdf->SetXY( 15, 160 );

// $pdf->Cell($w1, 10, mb_convert_encoding( $_GET['product_name1'].$_GET['product_count1'], "SJIS", "UTF-8" ), 1);
// $pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($_GET['fixed_price1']), "SJIS", "UTF-8" ), 1, 1,'R');

// $pdf->SetXY( 15, 170 );
// $pdf->Cell($w1, 10, mb_convert_encoding( $_GET['option_name'], "SJIS", "UTF-8" ), 1,0,'R');
// $pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($_GET['option_price']), "SJIS", "UTF-8" ), 1, 1,'R');
// $pdf->SetXY( 15, 170 );

$pdf->Cell($w1, 10, mb_convert_encoding( "合計　", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($product_total_price), "SJIS", "UTF-8" ), 1, 1,'R');
$pdf->SetXY( 15, $target_y + ($i*10));
// $pdf->Cell($w1, 10, mb_convert_encoding( "内税(". ($tax * 100)."%) ", "SJIS", "UTF-8" ), 1,0,'R');
// $pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($product_total_price - $product_total_price / $tax2), "SJIS", "UTF-8" ), 1, 1,'R');
// $pdf->SetXY( 15, $target_y + ($i*10) +10);
$pdf->Cell($w1, 10, mb_convert_encoding( "入金金額 ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($option_price), "SJIS", "UTF-8" ), 1, 1,'R');
// $pdf->SetXY( 15, 200 );
// $pdf->Cell($w1, 10, mb_convert_encoding( "残金  ", "SJIS", "UTF-8" ), 1,0,'R');
// $pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($_GET['balance']-$_GET['payment_loan_kari']), "SJIS", "UTF-8" ), 1, 1,'R');


$pdf->Output();
?>
