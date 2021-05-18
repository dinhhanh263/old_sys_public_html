<?php

ini_set( 'include_path', dirname(__FILE__) . "/../../php-lib/fpdf" );
require_once( 'mbfpdf.php');
require_once( '../../config/config.php');
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

// $today = date("Y年   n月  j日");
// $extension_edit_date = explode("-",$_GET['extension_edit_date']); // 申請日
// $extension_edit_date = $extension_edit_date[0]."年".$extension_edit_date[1]."月".$extension_edit_date[2]."日";
$extension_edit_date = date('Y年 n月 j日 ', strtotime($_GET['extension_edit_date']));
// 保証期間の計算
if($_GET['extension_flg'] == 1){
  $period = date('Y年 n月 j日 ', strtotime($_GET['p_end'].'+ 1 day -2 year'))."  ～  ".date('Y年 n月 j日 ', strtotime($_GET['p_end']));
}else{
  $period = date('Y年 n月 j日 ', strtotime($_GET['p_end'].' +1 day'))."  ～  ".date('Y年 n月 j日 ', strtotime($_GET['p_end'] . "+2 year"));
}

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
$pdf->Rect(10 ,60 ,190 ,180);

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 73 , 70  , mb_convert_encoding( "保証期間延長申請書", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 11 );
$pdf->Text( 20 , 80  , mb_convert_encoding( " 株式会社ヴィエリス  御中","SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 11 );
$pdf->Text( 140 , 90  , mb_convert_encoding( "申請日  {$extension_edit_date}","SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 11 );
$pdf->Text( 20 , 100  , mb_convert_encoding( "会員番号","SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 11 );
$pdf->Text( 55 , 100  , mb_convert_encoding( "  {$_GET['customer_no']}  ","SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 11 );
$pdf->Text( 20 , 110  , mb_convert_encoding( "ご契約者様お名前","SJIS", "UTF-8" ) );
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
$pdf->Text( 20 , 150  , mb_convert_encoding( "保証延長期間","SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 11 );
$pdf->Text( 55 , 150  , mb_convert_encoding( "  {$period}   ","SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 11 );
$pdf->Text( 20 , 165  , mb_convert_encoding( "期間延長理由","SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'U' , 11 );
$pdf->Text( 55 , 165  , mb_convert_encoding( "                                                                                                           ","SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 11 );
$pdf->Text( 20 , 184  , mb_convert_encoding( "ご署名","SJIS", "UTF-8" ) );
$pdf->Rect( 55 , 175  ,100 ,15);


$pdf->SetFont( KOZMIN,'' , 11 );
$pdf->Text( 20 , 205  , mb_convert_encoding( " トリートメント施術を上記期間まで延長することを保証致します。","SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'B' , 11 );
$pdf->Text( 103 , 218  , mb_convert_encoding( "株式会社ヴィエリス", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 103 , 224  , mb_convert_encoding( $company_zip_code, "SJIS", "UTF-8" ) );
$pdf->Text( 103 , 229  , mb_convert_encoding( $company_address, "SJIS", "UTF-8" ) );
$pdf->Text( 103 , 234  , mb_convert_encoding( "代表取締役 ".$kireimo_ceo, "SJIS", "UTF-8" ) );

// サロン使用欄 フォーム
$pdf->SetDrawColor(128,128,128);
$pdf->Rect(10 ,250 ,190 ,10);
$pdf->Rect(10 ,260 ,190 ,10);

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 85 , 256  , mb_convert_encoding( "【サロン使用欄】", "SJIS", "UTF-8" ) );
$pdf->Text( 20 , 266  , mb_convert_encoding( "受領日:  {$extension_edit_date}     サロン名:  {$_GET['shop_name']}     担当者名:  {$_GET['staff_name']}","SJIS-win", "UTF-8" ) );

//$pdf->Output();
$pdf->Output('延長_'.$_GET['customer_no'].'_'.$_GET['name'].'.pdf',"I");

?>
