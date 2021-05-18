<?php
require_once(dirname(__FILE__) . '/../../php-lib/fpdf/mbfpdf.php');
require_once(dirname(__FILE__) . '/../../admin/library/pdf/pdf_out.php');

header('Content-type: application/pdf');
header('Content-disposition: attachment; filename="downloaded.pdf"');

$title = "ご購入明細・領収書";
$msg = "このたびはご購入いただきありがとうございます。\n下記の金額を領収いたしました。";


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
$pdf->Text( 20 , 96  , mb_convert_encoding( "領収金額 ： ￥".number_format($sales['payment'] - $sales['payment_loan'] + $option_price)."(税込)", "SJIS", "UTF-8" ) );
$pdf->Line(20, 98, 100, 98);

$pdf->SetFillColor(238, 233, 233);
$pdf->Rect( 15 ,105 ,180 ,10, 'DF');
$pdf->SetFont( GOTHIC, '', 16 );
$pdf->Text( 20 , 112  , mb_convert_encoding( "ご購入・領収明細", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 120 );
$w1 = 140;
$w2 = 40;
$pdf->Cell($w1, 10, mb_convert_encoding( "品目", "SJIS", "UTF-8" ), 1,0,'C',1);
$pdf->Cell($w2, 10, mb_convert_encoding( "金額", "SJIS", "UTF-8" ), 1, 1,'C',1);

$pdf->SetXY( 15, 130 );
$pdf->Cell(180, 10, mb_convert_encoding( "購入金額", "SJIS", "UTF-8" ), 1);

$pdf->SetXY( 15, 140 );
$pdf->Cell($w1, 10, mb_convert_encoding( $course['name'], "SJIS", "UTF-8" ), 1);
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($fixed_price), "SJIS", "UTF-8" ), 1, 1,'R');

$pdf->SetXY( 15, 150 );
$pdf->Cell($w1, 10, mb_convert_encoding( "値引　", "SJIS", "UTF-8" ),  1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($sales['discount']), "SJIS", "UTF-8" ), 1, 1,'R');

$pdf->SetXY( 15, 160 );
$pdf->Cell($w1, 10, mb_convert_encoding( $option_name." ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($option_price), "SJIS", "UTF-8" ), 1, 1,'R');

$pdf->SetXY( 15, 170 );
$pdf->Cell($w1, 10, mb_convert_encoding( "内税(".($tax * 100)."%) ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($sales['price'] - $sales['price'] / $tax2), "SJIS", "UTF-8" ), 1, 1,'R');

$pdf->SetXY( 15, 180 );
$pdf->Cell($w1, 10, mb_convert_encoding( "請求金額合計 ", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($fixed_price - $sales['discount'] + $option_price), "SJIS", "UTF-8" ), 1, 1,'R');

$pdf->SetXY( 15, 190 );
$pdf->Cell(180, 10, mb_convert_encoding( "領収金額", "SJIS", "UTF-8" ), 1);

$pdf->SetXY( 15, 200 );
$pdf->Cell($w1, 10, mb_convert_encoding( "現金　", "SJIS", "UTF-8" ),  1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($sales['payment_cash'] + $sales['option_price']), "SJIS", "UTF-8" ), 1, 1,'R');

$pdf->SetXY( 15, 210 );
$pdf->Cell($w1, 10, mb_convert_encoding( "カード　", "SJIS", "UTF-8" ),  1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($sales['payment_card'] + $sales['option_card']), "SJIS", "UTF-8" ), 1, 1,'R');

$pdf->SetXY( 15, 220 );
$pdf->Cell($w1, 10, mb_convert_encoding( "振込　", "SJIS", "UTF-8" ),  1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($sales['payment_transfer'] + $sales['option_transfer']), "SJIS", "UTF-8" ), 1, 1,'R');

$pdf->SetXY( 15, 230 );
$pdf->Cell($w1, 10, mb_convert_encoding( "領収金額合計 　", "SJIS", "UTF-8" ),  1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($sales['payment_cash'] + $sales['payment_card'] + $sales['payment_transfer'] + $option_price), "SJIS", "UTF-8" ), 1, 1,'R');

$pdf->SetXY( 15, 240 );
$pdf->Cell(180, 10, mb_convert_encoding( "入金予定金額(残金)", "SJIS", "UTF-8" ), 1);

$pdf->SetXY( 15, 250 );
$pdf->Cell($w1, 10, mb_convert_encoding( "ローン申込金額　", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($sales['payment_loan']), "SJIS", "UTF-8" ), 1, 1,'R');

$pdf->SetXY( 15, 260 );
$pdf->Cell($w1, 10, mb_convert_encoding( "残金 　", "SJIS", "UTF-8" ), 1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "￥".number_format($sales['balance']), "SJIS", "UTF-8" ), 1, 1,'R');


$pdf->Output();
?>
