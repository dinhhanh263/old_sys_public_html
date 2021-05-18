<?php

ini_set( 'include_path', dirname(__FILE__) . "/php-lib/fpdf" );
require_once( 'mbfpdf.php');
require_once( 'config/config.php');
header('Content-type: application/pdf');
header('Content-disposition: attachment; filename="'.$_GET['name'].'.pdf"');

$pdf=new MBFPDF();
//$pdf->FPDF(Portrait ,mm,A4);//Landscape
$pdf->AddMBFont( GOTHIC , 'SJIS' );
$pdf->AddMBFont( PGOTHIC, 'SJIS' );
$pdf->AddMBFont( MINCHO , 'SJIS' );
$pdf->AddMBFont( PMINCHO, 'SJIS' );
$pdf->AddMBFont( KOZMIN , 'SJIS' );
$pdf->Open();

$today = date("Y年   n月  j日");
// 保証期間の計算
$_GET['p_end'] = date("Y-m-d H:i:s",strtotime($_GET['p_start'] . "+2 year"));
$period = date('Y年 n月 j日 ', strtotime($_GET['p_start'].' +1 day'))."  ～  ".date('Y年 n月 j日 ', strtotime($_GET['p_end']));

//1ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 73 , 24  , mb_convert_encoding( "保証期間延長申請書", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 35 );
$pdf->SetFont( KOZMIN,'B' , 11 );
$pdf->MultiCell(176, 6, mb_convert_encoding( "  平素は、格別のご愛顧をいただき誠にありがとうございます。
弊社でご購入頂いた回数パックプランの保証期間延長を申請して頂くにあたり、下記にご記入、
ご署名をお願い致します。	", "SJIS", "UTF-8" ) , 0, 'L', 0);

// 保証期間延長申請書 フォーム
$pdf->Rect(10 ,60 ,190 ,193);

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 73 , 70  , mb_convert_encoding( "保証期間延長申請書", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 11 );
$pdf->Text( 20 , 80  , mb_convert_encoding( " 株式会社カレント  御中","SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 11 );
$pdf->Text( 140 , 90  , mb_convert_encoding( "申請日  {$today}","SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 11 );
$pdf->Text( 20 , 100  , mb_convert_encoding( "会員番号","SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 11 );
$pdf->Text( 55 , 100  , mb_convert_encoding( "  {$_GET['customer_no']}  ","SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 11 );
$pdf->Text( 20 , 110  , mb_convert_encoding( "ご契約様お名前","SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 11 );
$pdf->Text( 55 , 110  , mb_convert_encoding( "  {$_GET['name']}    ","SJIS-win", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 11 );
$pdf->Text( 20 , 120  , mb_convert_encoding( "ご住所","SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 11 );
$pdf->Text( 55 , 120  , mb_convert_encoding( "  〒{$_GET['zip']}  {$_GET['address']}  ","SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 11 );
$pdf->Text( 20 , 130  , mb_convert_encoding( "お電話番号","SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 11 );
$pdf->Text( 55 , 130  , mb_convert_encoding( "  {$_GET['tel']}  ","SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 11 );
$pdf->Text( 20 , 140  , mb_convert_encoding( "ご契約コース","SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 11 );
$pdf->Text( 55 , 140  , mb_convert_encoding( "  {$_GET['course_name']}  ","SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 11 );
$pdf->Text( 55 , 150  , mb_convert_encoding( "  {$_GET['course_name2']}  ","SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 11 );
$pdf->Text( 55 , 160  , mb_convert_encoding( "  {$_GET['course_name3']}  ","SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 11 );
$pdf->Text( 20 , 170  , mb_convert_encoding( "保証延長期間","SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 11 );
$pdf->Text( 55 , 170  , mb_convert_encoding( "  {$period}   ","SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 11 );
$pdf->Text( 20 , 185  , mb_convert_encoding( "期間延長理由","SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'U' , 11 );
$pdf->Text( 55 , 185  , mb_convert_encoding( "                                                                                                           ","SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 11 );
$pdf->Text( 20 , 194  , mb_convert_encoding( "ご署名","SJIS", "UTF-8" ) );
$pdf->Rect( 55 , 190  ,100 ,15);


$pdf->SetFont( KOZMIN,'' , 11 );
$pdf->Text( 20 , 215  , mb_convert_encoding( " トリートメント施術を上記期間まで延長することを保証致します。","SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'B' , 11 );
$pdf->Text( 110 , 228  , mb_convert_encoding( "株式会社カレント", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 110 , 234  , mb_convert_encoding( $company_zip_code, "SJIS", "UTF-8" ) );
$pdf->Text( 110 , 239  , mb_convert_encoding( $company_address, "SJIS", "UTF-8" ) );
$pdf->Text( 110 , 244  , mb_convert_encoding( "代表取締役 ".$mens_kireimo_ceo, "SJIS", "UTF-8" ) );

// サロン使用欄 フォーム
$pdf->SetDrawColor(128,128,128);
$pdf->Rect(10 ,260 ,190 ,10);
$pdf->Rect(10 ,270 ,190 ,10);

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 85 , 266  , mb_convert_encoding( "【サロン使用欄】", "SJIS", "UTF-8" ) );
$pdf->Text( 20 , 276  , mb_convert_encoding( "受領日:  {$today}     サロン名:  {$_GET['shop_name']}     担当者名:  {$_GET['staff_name']}","SJIS-win", "UTF-8" ) );

//$pdf->Output();
$pdf->Output('延長_'.$_GET['customer_no'].'_'.$_GET['name'],"I");

?>
