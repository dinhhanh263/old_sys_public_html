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
$pdf->Text( 20 , 96  , mb_convert_encoding( "請求金額 ： ￥".number_format($sales['payment'] + $option_price)."(税込)", "SJIS", "UTF-8" ) );
$pdf->Line(20, 98, 100, 98);

$pdf->SetFillColor(238, 233, 233);
$pdf->Rect( 15 ,105 ,180 ,10, 'DF');
$pdf->SetFont( GOTHIC, '', 16 );
$pdf->Text( 20 , 112  , mb_convert_encoding( "ご購入明細", "SJIS", "UTF-8" ) );
$pdf->SetFont( GOTHIC, '', 12 );
$pdf->Text( 20 , 125  , mb_convert_encoding( "お支払期日 ： ".date("Y/m/d H:i:s"), "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 140 );
$w1 = 140;
$w2 = 40;
$pdf->Cell($w1, 10, mb_convert_encoding( "コース名", "SJIS", "UTF-8" ), 1,0,'C',1);
$pdf->Cell($w2, 10, mb_convert_encoding( "金額(税込)", "SJIS", "UTF-8" ), 1, 1,'C',1);

$pdf->SetXY( 15, 150 );
$pdf->Cell($w1, 10, mb_convert_encoding( $course['name'], "SJIS", "UTF-8" ), 1);
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".($course['type'] == 1 ? number_format($monthly_price) : number_format($contract['price'])), "SJIS", "UTF-8" ), 1, 1,'R');

$pdf->SetXY( 15, 160 );
$pdf->Cell($w1, 10, mb_convert_encoding( "残金支払  ", "SJIS", "UTF-8" ),  1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($sales['payment']), "SJIS", "UTF-8" ), 1, 1,'R');

$pdf->SetXY( 15, 170 );
$pdf->Cell($w1, 10, mb_convert_encoding( $option_name." ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($option_price), "SJIS", "UTF-8" ), 1, 1,'R');

$pdf->SetXY( 15, 180 );
$pdf->Cell($w1, 10, mb_convert_encoding( "支払合計 ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($sales['payment'] + $option_price), "SJIS", "UTF-8" ), 1, 1,'R');

$pdf->SetXY( 15, 190 );
$pdf->Cell($w1, 10, mb_convert_encoding( "支払後残金  ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($sales['balance']), "SJIS", "UTF-8" ), 1, 1,'R');

$pdf->SetXY( 15, 200 );
$pdf->Cell($w1, 10, mb_convert_encoding( "消化回数", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( $r_times, "SJIS", "UTF-8" ), 1, 1,'R');

$pdf->SetXY( 15, 210 );
$pdf->Cell($w1, 10, mb_convert_encoding( "消化単価", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($price_once), "SJIS", "UTF-8" ), 1, 1,'R');

$pdf->SetXY( 15, 220 );
if ($course['type'] == 0 ){
  $pdf->Cell($w1, 10, mb_convert_encoding( "残回数 ", "SJIS", "UTF-8" ), 1,0,'R');
  $pdf->Cell($w2, 10, mb_convert_encoding( $remain_times, "SJIS", "UTF-8" ), 1, 1,'R');
}


$pdf->Output();
?>
