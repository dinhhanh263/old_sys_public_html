<?php

ini_set( 'include_path', dirname(__FILE__) . "/php-lib/fpdf" );
require_once( 'mbfpdf.php');
require_once( 'config/config.php');

header('Content-type: application/pdf');
header('Content-disposition: attachment; filename="'.$_GET['name'].'.pdf"');

$title = "";

$msg = "このたびはご契約頂いた".$_GET['course_name']."プランをご解約致しました。\nご確認下さいませ。\n宜しくお願い致します。";

$shop = "http://www.kireimo.jp/mens/\n\n株式会社カレント\nMEN'S KIREIMO ".$_GET['shop_name']."\n".$company_zip_code."\n".$company_address."\n".$company_tel_no."\nEmail:mens.info@kireimo.jp";

$balance_name = $_GET['balance'] ? "残金" : "";
$_GET['shop_tel']="0120-444-276";

$pdf=new MBFPDF();
//$pdf->FPDF(Portrait ,mm,A4);//Landscape
$pdf->AddMBFont( GOTHIC , 'SJIS' );
$pdf->AddMBFont( PGOTHIC, 'SJIS' );
$pdf->AddMBFont( MINCHO , 'SJIS' );
$pdf->AddMBFont( PMINCHO, 'SJIS' );
$pdf->AddMBFont( KOZMIN , 'SJIS' );
$pdf->Open();

// コース金額(単価)
if($_GET['fixed_per_price']) $_GET['fixed_per_price']   = number_format($_GET['fixed_per_price']);
if($_GET['fixed_per_price2']) $_GET['fixed_per_price2'] = number_format($_GET['fixed_per_price2']);
if($_GET['fixed_per_price3']) $_GET['fixed_per_price3'] = number_format($_GET['fixed_per_price3']);
if($_GET['fixed_per_price4']) $_GET['fixed_per_price4'] = number_format($_GET['fixed_per_price4']);
if($_GET['fixed_per_price5']) $_GET['fixed_per_price5'] = number_format($_GET['fixed_per_price5']);
if($_GET['discount_per_price']) $_GET['discount_per_price']   = number_format($_GET['discount_per_price']);
if($_GET['discount_per_price2']) $_GET['discount_per_price2'] = number_format($_GET['discount_per_price2']);
if($_GET['discount_per_price3']) $_GET['discount_per_price3'] = number_format($_GET['discount_per_price3']);
if($_GET['discount_per_price4']) $_GET['discount_per_price4'] = number_format($_GET['discount_per_price4']);
if($_GET['discount_per_price5']) $_GET['discount_per_price5'] = number_format($_GET['discount_per_price5']);

// 有効期限
if($_GET['end_date']){
	$contract_period = ($_GET['end_date']=="0000-00-00") ? "" : str_replace("-", "/",$_GET['contract_date'])."～".str_replace("-", "/",$_GET['end_date']);
}
if($_GET['end_date2']){
	$contract_period2 = ($_GET['end_date2']=="0000-00-00") ? "" : str_replace("-", "/",$_GET['contract_date'])."～".str_replace("-", "/",$_GET['end_date2']);
}
if($_GET['end_date3']){
	$contract_period3 = ($_GET['end_date3']=="0000-00-00") ? "" : str_replace("-", "/",$_GET['contract_date'])."～".str_replace("-", "/",$_GET['end_date3']);
}
if($_GET['end_date4']){
	$contract_period4 = ($_GET['end_date4']=="0000-00-00") ? "" : str_replace("-", "/",$_GET['contract_date'])."～".str_replace("-", "/",$_GET['end_date4']);
}
if($_GET['end_date5']){
	$contract_period5 = ($_GET['end_date5']=="0000-00-00") ? "" : str_replace("-", "/",$_GET['contract_date'])."～".str_replace("-", "/",$_GET['end_date5']);
}


//1ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();
$pdf->SetDrawColor(255,0,0);
$pdf->Rect(15 ,20 ,180 ,10);
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->SetTextColor(255, 0, 0);
$pdf->Text( 17 , 24  , mb_convert_encoding( "この書面は、当サロンのサービス及び商品の内容をご理解いただくために、特定商取引法第42条に基づきお渡しするもので、契約書ではあ", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 28  , mb_convert_encoding( "りません。この書面の内容を十分にお読み下さい。", "SJIS", "UTF-8" ) );

$pdf->SetDrawColor(0,0,0);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 165 , 36  , mb_convert_encoding( "No. ".substr($_GET['hope_date'], 2,2).substr($_GET['hope_date'], 5,2)."- ". $_GET['no']."  ", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 18 );
$pdf->Text( 75 , 40  , mb_convert_encoding( "概 要 書 面", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 14 );
$pdf->Text( 107 , 40  , mb_convert_encoding( "(事前説明書)", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 16 , 48  , mb_convert_encoding( substr($_GET['hope_date'], 0,4)." 年 ".substr($_GET['hope_date'], 5,2)." 月 ".substr($_GET['hope_date'], 8,2)." 日", "SJIS", "UTF-8" ) );



// if($_GET['shop_id']==6 && $_GET['hope_date']<'2015-01-04'){

// $pdf->Image("./admin/img/pdf/ckr.png",155,38,24); //横幅のみ指定,24

// $pdf->SetFont( KOZMIN,'' , 8 );
// $pdf->Text( 107 , 48  , mb_convert_encoding( "会  社  名", "SJIS", "UTF-8" ) );
// $pdf->SetFont( KOZMIN,'B' , 10 );
// $pdf->Text( 122 , 48  , mb_convert_encoding( "株式会社CKR", "SJIS", "UTF-8" ) );

// $pdf->SetFont( KOZMIN,'' , 8 );
// $pdf->Text( 107 , 52  , mb_convert_encoding( "所  在  地", "SJIS", "UTF-8" ) );
// $pdf->Text( 122 , 52  , mb_convert_encoding( "〒150- 0012　東京都渋谷区広尾5-25-5", "SJIS", "UTF-8" ) );
// $pdf->Text( 122 , 55  , mb_convert_encoding( "広尾アネックスビル7F", "SJIS", "UTF-8" ) );

// $pdf->Text( 107 , 59  , mb_convert_encoding( "電話番号", "SJIS", "UTF-8" ) );
// $pdf->Text( 122 , 59  , mb_convert_encoding( "TEL: 03- 5422- 7501　FAX:03- 3447- 6086", "SJIS", "UTF-8" ) );

// $pdf->Text( 107 , 63  , mb_convert_encoding( "代  表  者", "SJIS", "UTF-8" ) );
// $pdf->Text( 122 , 63  , mb_convert_encoding( "代表取締役社長　大澤　美加", "SJIS", "UTF-8" ) );

// }else{

$pdf->Image("./admin/img/pdf/mens_stamp.png",155,38,24); //横幅のみ指定,24

$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Text( 107 , 48  , mb_convert_encoding( "会  社  名", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 122 , 48  , mb_convert_encoding( "株式会社　カレント", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Text( 107 , 52  , mb_convert_encoding( "所  在  地", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 52  , mb_convert_encoding( $company_zip_code, "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 55  , mb_convert_encoding( $company_address, "SJIS", "UTF-8" ) );

$pdf->Text( 107 , 59  , mb_convert_encoding( "電話番号", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 59  , mb_convert_encoding( "TEL: ".$company_tel_no."　FAX: ".$company_fax_no, "SJIS", "UTF-8" ) );

$pdf->Text( 107 , 63  , mb_convert_encoding( "代  表  者", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 63  , mb_convert_encoding( "代表取締役社長　".$mens_kireimo_ceo, "SJIS", "UTF-8" ) );

//}



$pdf->Text( 107 , 67  , mb_convert_encoding( "サロン名", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 122 , 67  , mb_convert_encoding( "MEN'S KIREIMO", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Text( 16 , 67  , mb_convert_encoding( "お名前　".$_GET['name']."　　　　　様 ", "SJIS-win", "UTF-8" ) );
$pdf->Text( 106 , 74  , mb_convert_encoding( "店  舗  名： ".$_GET['shop_name']."                                                              ", "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 78  , mb_convert_encoding( "店舗住所： ".$_GET['shop_address'], "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 82  , mb_convert_encoding( "電話番号： ".$_GET['shop_tel']."                                               ", "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 86  , mb_convert_encoding( "作  成  者： ".$_GET['staff']."                                                         ", "SJIS-win", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Text( 16 , 90  , mb_convert_encoding( "ご利用を希望されるサービスの内容をご確認ください。", "SJIS", "UTF-8" ) );


$pdf->Text( 16 , 95  , mb_convert_encoding( "1.ご利用希望サービス", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 16 , 100  , mb_convert_encoding( "■コース", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 102 );
$pdf->Cell(70, 10, mb_convert_encoding( "脱毛内容明細（コース名）", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(10, 10, mb_convert_encoding( "回数", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetFont( KOZMIN,'' , 7 );
$pdf->Cell(15, 5, mb_convert_encoding( "1回のお手れ", "SJIS", "UTF-8" ), "LTR",0,"L");
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Cell(40, 5, mb_convert_encoding( "定価", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(45, 5, mb_convert_encoding( "割引後金額", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 95, 107 );
$pdf->SetFont( KOZMIN,'' , 7 );
$pdf->Cell(15, 5, mb_convert_encoding( "時間(分)", "SJIS", "UTF-8" ), "LRB",0,"C");
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Cell(20, 5, mb_convert_encoding( "単価(円)", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "料金(円)", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "単価(円)", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "料金(円)", "SJIS", "UTF-8" ), 1,0,"C");

// コース1
$pdf->SetXY( 15, 112 );
$pdf->Cell(6,  10, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( $_GET['course_name'], "SJIS", "UTF-8" ), 0,0,"L");
$pdf->Cell(10, 10, mb_convert_encoding( $_GET['times'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(15, 10, mb_convert_encoding( $_GET['length'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( $_GET['fixed_per_price'], "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['fixed_price']), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( $_GET['discount_per_price'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( number_format($_GET['discount_fixed_price']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->SetXY( 110, 117 );
$pdf->Cell(20, 5, mb_convert_encoding( "契約期間", "SJIS", "UTF-8" ), 1,0,"C");
//$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( $contract_period , "SJIS", "UTF-8" ), 1,0,"C");
// コース1 部位
$pdf->SetXY( 15, 117 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Cell(6,  0, mb_convert_encoding( "", "SJIS", "UTF-8" ), 0,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( $_GET['contract_part'], "SJIS", "UTF-8" ), 1,0,"B");
$pdf->SetFont( KOZMIN,'' , 10 );

// コース2
$pdf->SetXY( 15, 122 );
$pdf->Cell(6,  10, mb_convert_encoding( "2", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( $_GET['course_name2'], "SJIS", "UTF-8" ), 0,0,"L");
$pdf->Cell(10, 10, mb_convert_encoding( $_GET['times2'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(15, 10, mb_convert_encoding( $_GET['length2'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( $_GET['fixed_per_price2'], "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['fixed_price2']), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( $_GET['discount_per_price2'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( number_format($_GET['discount_fixed_price2']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->SetXY( 110, 127 );
$pdf->Cell(20, 5, mb_convert_encoding( "契約期間", "SJIS", "UTF-8" ), 1,0,"C");
//$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( $contract_period2 , "SJIS", "UTF-8" ), 1,0,"C");
// コース2 部位
$pdf->SetXY( 15, 127 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Cell(6,  0, mb_convert_encoding( "", "SJIS", "UTF-8" ), 0,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( $_GET['contract_part2'], "SJIS", "UTF-8" ), 1,0,"B");
$pdf->SetFont( KOZMIN,'' , 10 );

// コース3
$pdf->SetXY( 15, 132 );
$pdf->Cell(6,  10, mb_convert_encoding( "3", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( $_GET['course_name3'], "SJIS", "UTF-8" ), 0,0,"L");
$pdf->Cell(10, 10, mb_convert_encoding( $_GET['times3'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(15, 10, mb_convert_encoding( $_GET['length3'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( $_GET['fixed_per_price3'], "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['fixed_price3']), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( $_GET['discount_per_price3'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( number_format($_GET['discount_fixed_price3']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->SetXY( 110, 137 );
$pdf->Cell(20, 5, mb_convert_encoding( "契約期間", "SJIS", "UTF-8" ), 1,0,"C");
//$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( $contract_period3 , "SJIS", "UTF-8" ), 1,0,"C");
// コース3 部位
$pdf->SetXY( 15, 137 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Cell(6,  0, mb_convert_encoding( "", "SJIS", "UTF-8" ), 0,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( $_GET['contract_part3'], "SJIS", "UTF-8" ), 1,0,"B");
$pdf->SetFont( KOZMIN,'' , 10 );

// コース4
$pdf->SetXY( 15, 142 );
$pdf->Cell(6,  10, mb_convert_encoding( "4", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( $_GET['course_name4'], "SJIS", "UTF-8" ), 0,0,"L");
$pdf->Cell(10, 10, mb_convert_encoding( $_GET['times4'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(15, 10, mb_convert_encoding( $_GET['length4'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( $_GET['fixed_per_price4'], "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['fixed_price4']), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( $_GET['discount_per_price4'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( number_format($_GET['discount_fixed_price4']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->SetXY( 110, 147 );
$pdf->Cell(20, 5, mb_convert_encoding( "契約期間", "SJIS", "UTF-8" ), 1,0,"C");
//$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( $contract_period4 , "SJIS", "UTF-8" ), 1,0,"C");
// コース4 部位
$pdf->SetXY( 15, 147 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Cell(6,  0, mb_convert_encoding( "", "SJIS", "UTF-8" ), 0,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( $_GET['contract_part4'], "SJIS", "UTF-8" ), 1,0,"B");
$pdf->SetFont( KOZMIN,'' , 10 );

// コース5
$pdf->SetXY( 15, 152 );
$pdf->Cell(6,  10, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( $_GET['course_name5'], "SJIS", "UTF-8" ), 0,0,"L");
$pdf->Cell(10, 10, mb_convert_encoding( $_GET['times5'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(15, 10, mb_convert_encoding( $_GET['length5'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( $_GET['fixed_per_price5'], "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['fixed_price5']), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( $_GET['discount_per_price5'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( number_format($_GET['discount_fixed_price5']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->SetXY( 110, 157 );
$pdf->Cell(20, 5, mb_convert_encoding( "契約期間", "SJIS", "UTF-8" ), 1,0,"C");
//$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( $contract_period5 , "SJIS", "UTF-8" ), 1,0,"C");
// コース5 部位
$pdf->SetXY( 15, 157 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Cell(6,  0, mb_convert_encoding( "", "SJIS", "UTF-8" ), 0,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( $_GET['contract_part5'], "SJIS", "UTF-8" ), 1,0,"B");
$pdf->SetFont( KOZMIN,'' , 10 );


// $pdf->SetXY( 15, 135 );
// $pdf->Cell(6, 10, mb_convert_encoding( "3", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(64, 10, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(10, 10, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(15, 10, mb_convert_encoding( "", "SJIS", "UTF-8" ), "1",0,"C");
// $pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->SetXY( 110, 140 );
// $pdf->Cell(20, 5, mb_convert_encoding( "契約期間", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");



$pdf->SetFont( KOZMIN,'' , 7 );
$pdf->Text( 17 , 166  , mb_convert_encoding( "※1回当たりのお手入れ時間は、目安となります。", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 170  , mb_convert_encoding( "※コース金額につきましては、概要書面発行日当日にご契約いただいた場合の金額となります。", "SJIS", "UTF-8" ) );
// $pdf->Text( 17 , 167  , mb_convert_encoding( "※上記PREMIUMコースの回数は返金保証回数であり、役務の提供は期間・回数共に無制限といたします。", "SJIS", "UTF-8" ) );


$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 16 , 175  , mb_convert_encoding( "■特典明細", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 177 );
$pdf->Cell(135, 5, mb_convert_encoding( "特典明細", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "回数", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "特典価格（円）", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 182 );
$pdf->Cell(6, 5, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 187 );
$pdf->Cell(6, 5, mb_convert_encoding( "2", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 192 );
$pdf->Cell(6, 5, mb_convert_encoding( "3", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 197 );
$pdf->Cell(6, 5, mb_convert_encoding( "4", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 202 );
$pdf->Cell(6, 5, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 16 , 214  , mb_convert_encoding( "■割引", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 10 );

// 以前までの書き方（コースが増えたら下記でレイアウト変更する
$pdf->SetXY( 15, 217 );
$pdf->Cell(70, 10, mb_convert_encoding( "割引対象コース", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 10, mb_convert_encoding( "割引明細", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(45, 5, mb_convert_encoding( "割引金額", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 150, 222 );
$pdf->Cell(20, 5, mb_convert_encoding( "単価(円)", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "料金(円)", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 227 );
$pdf->Cell(6, 5, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( ($_GET['discount'] ? $_GET['course_name'] : ""), "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( ($_GET['discount']  ? ($_GET['times'] ? number_format(round($_GET['discount']/ $_GET['times'])) : $_GET['discount']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($_GET['discount'] ? number_format($_GET['discount']) : ""), "SJIS", "UTF-8" ), 1,0,"R");

$pdf->SetXY( 15, 232 );
$pdf->Cell(6, 5, mb_convert_encoding( "2", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( ($_GET['discount2'] ? $_GET['course_name2'] : ""), "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( ($_GET['discount2']  ? ($_GET['times2'] ? number_format(round($_GET['discount2']/ $_GET['times2'])) : $_GET['discount2']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($_GET['discount2'] ? number_format($_GET['discount2']) : ""), "SJIS", "UTF-8" ), 1,0,"R");

$pdf->SetXY( 15, 237 );
$pdf->Cell(6, 5, mb_convert_encoding( "3", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( ($_GET['discount3'] ? $_GET['course_name3'] : ""), "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( ($_GET['discount3']  ? ($_GET['times3'] ? number_format(round($_GET['discount3']/ $_GET['times3'])) : $_GET['discount3']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($_GET['discount3'] ? number_format($_GET['discount3']) : ""), "SJIS", "UTF-8" ), 1,0,"R");

$pdf->SetXY( 15, 242 );
$pdf->Cell(6, 5, mb_convert_encoding( "4", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( ($_GET['discount4'] ? $_GET['course_name4'] : ""), "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( ($_GET['discount4']  ? ($_GET['times4'] ? number_format(round($_GET['discount4']/ $_GET['times4'])) : $_GET['discount4']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($_GET['discount4'] ? number_format($_GET['discount4']) : ""), "SJIS", "UTF-8" ), 1,0,"R");

$pdf->SetXY( 15, 247 );
$pdf->Cell(6, 5, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( ($_GET['discount5'] ? $_GET['course_name5'] : ""), "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( ($_GET['discount5']  ? ($_GET['times5'] ? number_format(round($_GET['discount5']/ $_GET['times5'])) : $_GET['discount5']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($_GET['discount5'] ? number_format($_GET['discount5']) : ""), "SJIS", "UTF-8" ), 1,0,"R");

// 割引をまとめたときの書き方
// $pdf->Cell(70, 10, mb_convert_encoding( "割引対象コース", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(65, 10, mb_convert_encoding( "割引明細", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(45, 5, mb_convert_encoding( "割引金額", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->SetXY( 150, 210 );
// $pdf->Cell(45, 5, mb_convert_encoding( "料金(円)", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->SetXY( 15, 215 );
// $pdf->Cell(6, 5, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(64, 5, mb_convert_encoding( ($_GET['discount'] ? $_GET['course_name'] : ""), "SJIS", "UTF-8" ), 1,0,"L");
// $pdf->Cell(65, 15, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C"); // 割引明細
// $pdf->Cell(45, 15, mb_convert_encoding( ($_GET['discount'] ? number_format($_GET['discount']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
// $pdf->SetXY( 15, 230 );
// 割引対象コース（複数コース時）
// $pdf->SetXY( 15, 220 );
// $pdf->Cell(6, 5, mb_convert_encoding( "2", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(64, 5, mb_convert_encoding( ($_GET['discount'] ? $_GET['course_name2'] : ""), "SJIS", "UTF-8" ), 1,0,"L");
// $pdf->SetXY( 15, 225 );
// $pdf->Cell(6, 5, mb_convert_encoding( "3", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(64, 5, mb_convert_encoding( ($_GET['discount'] ? $_GET['course_name3'] : ""), "SJIS", "UTF-8" ), 1,0,"L");

$pdf->SetFillColor(238, 233, 233);
$pdf->Rect( 120 ,256 ,30 ,10, 'DF');
$pdf->SetXY( 120, 256);
$pdf->Cell(30, 10, mb_convert_encoding( "定価合計", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(45, 10, mb_convert_encoding( number_format($_GET['fixed_price']+$_GET['fixed_price2']+$_GET['fixed_price3']+$_GET['fixed_price4']+$_GET['fixed_price5'])."円", "SJIS", "UTF-8" ), 1,0,"R");
// $pdf->Rect( 120 ,256 ,30 ,10, 'DF');
// $pdf->SetXY( 120, 256);
// $pdf->Cell(30, 10, mb_convert_encoding( "割引合計", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(45, 10, mb_convert_encoding( number_format($_GET['discount']+$_GET['discount2']+$_GET['discount3'])."円", "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Rect( 120 ,266 ,30 ,10, 'DF');
$pdf->SetXY( 120, 266);
$pdf->Cell(30, 10, mb_convert_encoding( "総支払合計", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(45, 10, mb_convert_encoding( number_format(($_GET['fixed_price']+$_GET['fixed_price2']+$_GET['fixed_price3']+$_GET['fixed_price4']+$_GET['fixed_price5'])-$_GET['discount']-$_GET['discount2']-$_GET['discount3']-$_GET['discount4']-$_GET['discount5'])."円", "SJIS", "UTF-8" ), 1,0,"R");

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "1/13" );

//2ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 16 , 20  , mb_convert_encoding( "■備考", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 22);
$pdf->MultiCell(180, 60, mb_convert_encoding( $_GET['memo'], "SJIS", "UTF-8" ), 1,"T");


$pdf->Text( 16 , 89  , mb_convert_encoding( "2.お支払いの方法・時期", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 90 );
$pdf->Cell(135, 5, mb_convert_encoding( "お支払い方法", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "金額", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "入金日", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 95 );
$pdf->Cell(6, 5, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "現金", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['payment_cash']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($_GET['payment_cash'] ? str_replace("-", "/",$_GET['hope_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 100 );
$pdf->Cell(6, 5, mb_convert_encoding( "2", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "カード", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['payment_card']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($_GET['payment_card'] ? str_replace("-", "/",$_GET['hope_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 105 );
$pdf->Cell(6, 5, mb_convert_encoding( "3", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "銀行振込", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['payment_transfer']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($_GET['payment_transfer'] ? str_replace("-", "/",$_GET['hope_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 110 );
$pdf->Cell(6, 5, mb_convert_encoding( "4", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "ローン", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['payment_loan']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($_GET['payment_loan'] ? str_replace("-", "/",$_GET['hope_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 115 );
if($_GET['payment_coupon']){
$pdf->Cell(6, 5, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "クーポン", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['payment_coupon']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($_GET['payment_coupon'] ? str_replace("-", "/",$_GET['hope_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 120 );
$pdf->Cell(6, 5, mb_convert_encoding( "6", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( $balance_name, "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( ($_GET['balance'] ? number_format($_GET['balance']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
}else{
$pdf->Cell(6, 5, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( $balance_name, "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( ($_GET['balance'] ? number_format($_GET['balance']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 120 );
$pdf->Cell(6, 5, mb_convert_encoding( "6", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
}

$pdf->SetXY( 15, 125 );
$pdf->Cell(6, 5, mb_convert_encoding( "7", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 130 );
$pdf->Cell(6, 5, mb_convert_encoding( "8", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 135 );
$pdf->Cell(6, 5, mb_convert_encoding( "9", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 140 );
$pdf->Cell(6, 5, mb_convert_encoding( "10", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");

$pdf->SetFillColor(238, 233, 233);
$pdf->Rect( 15 ,145 ,135 ,5, 'DF');

$pdf->SetXY( 15, 145 );
$pdf->Cell(135, 5, mb_convert_encoding( "合計", "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['payment_cash']+$_GET['payment_card']+$_GET['payment_transfer']+$_GET['payment_loan']+$_GET['payment_coupon']+$_GET['balance']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( "-", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 150 );
$pdf->Cell(180, 5, mb_convert_encoding( "前払い金の保金処置：なし", "SJIS", "UTF-8" ), 1,0,"L");


$pdf->SetFont( KOZMIN,'' , 7 );
$pdf->Text( 17 , 158  , mb_convert_encoding( "※クレジットご利用の場合、抗弁権の接続ができます。詳細については契約書を良くお読み下さい。", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 162  , mb_convert_encoding( "前受け金の保全措置はありません。", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 17 , 170  , mb_convert_encoding( "3.特約事項：なし", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 180  , mb_convert_encoding( "4.契約の解除に関する事項：クーリング・オフ並びに中途解約につきましては、概要書面の該当欄をご確認ください。", "SJIS", "UTF-8" ) );


$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 17 , 200  , mb_convert_encoding( "お客様記入欄", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Text( 20 , 205  , mb_convert_encoding( "私はこの書面によりサービス内容の説明を受け、概要書面を確かに受け取りました。", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 20 , 220  , mb_convert_encoding( substr($_GET['hope_date'], 0,4)." 年 ".substr($_GET['hope_date'], 5,2)." 月 ".substr($_GET['hope_date'], 8,2)." 日", "SJIS", "UTF-8" ) );


$pdf->Rect(95 ,240 ,100 ,15);

$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 257  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "2/13" );

//3ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'' , 12 );
$pdf->SetTextColor(255, 0, 0);
$pdf->Text( 17 , 7  , mb_convert_encoding( "<クーリング・オフについて>", "SJIS", "UTF-8" ) );

$pdf->SetDrawColor(255,0,0);
$pdf->Rect(15 ,9 ,180 ,49);

$pdf->SetFont( KOZMIN,'' , 7.7 );
$pdf->Text( 17 , 12  , mb_convert_encoding( "■法定継続的役務提供受領者（エステティック契約者）は、締結した契約書面を受領した日から起算して8日以内であれば、書面により、", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 17  , mb_convert_encoding( "関連商品を含めその契約を解除（クーリング・オフ）できます。但し、エステティック契約者が、クーリング・オフに関し当社から不実のことを", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 22  , mb_convert_encoding( "告げられ誤認し又は威迫により困惑しクーリング・オフを行わなかった場合には、当該期間経過後も書面によりその契約をクーリング・オフする", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 27  , mb_convert_encoding( "ことができます。但し、関連商品において、その全部若しくは一部を消費（開封若しくは使用）したときは、その対象ではございませんが、", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 32  , mb_convert_encoding( "当社がお客様に商品を使用させ、また消費させた場合はこの限りではありません。", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 37  , mb_convert_encoding( "■クーリング・オフは、書面を当社宛に発信したときにその効力が生じます。", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 42  , mb_convert_encoding( "■クーリング・オフに伴う損害賠償、違約金、本契約役務ご利用代金の支払い請求はいたしません。", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 47  , mb_convert_encoding( "■（クーリング・オフ対象）関連商品の引渡しが既にされているとき、その返還に要する費用は、当社が負担します。", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 52  , mb_convert_encoding( "当社が既に受領した代金は（クーリング・オフ対象外の関連商品代金は除く）速やかにエステティック契約者が指定した口座へ振込みで", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 57  , mb_convert_encoding( "返還致します。（振込み手数料は、当社が負致します。）", "SJIS", "UTF-8" ) );

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 65  , mb_convert_encoding( "< 中 途 解 約 >", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 67 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 4.5, mb_convert_encoding( "クーリング・オフ期間を過ぎた場合でも契約を解約できます。
また1コースにつき、1契約となるため、1コース毎の解約が可能です。但し関連商品のみの解約はできません。解約時の返金額等の算出方法としては、コース・プラン1回あたりの料金である補正単価（契約金額を契約回数で除した結果が割り切れない場合1円未満を四捨五入）に利用回数をかけた金額を消化額とし、支払総額から消化額を引いた金額を残金とします。
但し、各PREMIUMコースについては、各コースで定めている単価で計算するものとします。
1コース毎に途中解約手数料金額として残金の10%（￥20,000以内）を差し引かせて頂きます。

    解約手数料金額  = ｛ 支払総額  -  ( 1回あたりの料金   ×   利用回数 )  ｝×10% （￥20,000以内）
    清算金  =  残金  -  解約手数料金額

なお、クーリング・オフ期間外で契約期間内にお手入れを一度も行っていないコース・プランの解約につきましては契約した金額から解約手数料金額として10%(￥20，000以内)を頂戴致します。

お支払いの方法としてローンをご契約のお客様につきましては、中途解約の場合、上記解約手数料金額に加えて、ローンキャンセル手数料がお客様のご負担となります。

    解約手数料金額  = ｛ 支払総額  -  ( 1回あたりの料金   ×   利用回数 )  ｝×10% （￥20,000以内）
    清算金  =  残金  -  解約手数料金額   -  ローンキャンセル手数料
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetTextColor(255, 0, 0);
$pdf->SetXY( 19, 152 );
$pdf->SetFont( KOZMIN,'' , 8.5 );
$pdf->MultiCell(166, 4.5, mb_convert_encoding( "※ローンキャンセル手数料の算出方法につきましては、ローン会社の規定に従い算出させていただきます。
※複数箇所でコース1回分とみなす契約（全身パック、キャンペーン時におけるセットのコース）につきましてはコースに 含まれる箇所を1箇所でもお手入れした場合、コース1回分を消化したとみなし、解約時の返金対象とはなりません。
（例）全身コース6回契約時、ヒザ下1回のみお手入れ後、解約を希望した場合の返金対象は、全身5回分となります。
※現金の受け渡しによる返金は行っておりません。金融機関への振込とさせて頂きます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY( 15, 175 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "以下のいずれかに該当する場合は、当社より契約の解除をさせていただく場合がございます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 19, 179 );
$pdf->SetFont( KOZMIN,'' , 8.5 );
$pdf->MultiCell(176, 4.5, mb_convert_encoding( "※契約金の金額が、契約日より起算して90日以内にお支払いいただけない場合。
※お客様の体質等に起因して、お手入れの継続が困難だと当社が判断した時。
※お客様との信頼関係の維持が困難と判断した時。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 199  , mb_convert_encoding( "< システム補足 >", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 201 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 4.5, mb_convert_encoding( "●未成年のお客様については、親権者の同意が必要となります。同意書には、親権者の続柄・ご連絡先の電話番号
   のご記入と署名・捺印が必要です。また、追ってサロンから親権者の方へ確認の連絡をさせて頂きます。
   (同意書の提出がない場合は契約無効とさせていただきます）
●ご契約に関しては、お支払い代金の一部(1回当たりの料金以上の金額)を手付金としてお支払いいただくことで、
   当日の契約が可能です。ただし、契約日から90日以内に残りの代金をお支払いいただけない場合は、サービス
   を提供できないことと手付金を放棄したものとして、契約を解除させていただきますのでご注意下さい。
   なお、契約日から30日以内に残りの代金をお支払いいただいていない場合は、契約解除手続きについての
   お知らせをお送りすることがあります。
●お手入れの間隔は30日以上、お顔は14日以上空けて下さい。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

//$pdf->SetTextColor(255, 0, 0);
//$pdf->SetXY( 15, 241 );
//$pdf->SetFont( KOZMIN,'' , 9 );
//$pdf->MultiCell(180, 4.5, mb_convert_encoding( "●PREMIUMコースの契約期間については返金の保証期間とさせていただいております。
//●PREMIUMコースの役務の提供は期間、回数共に無制限といたします。
//●エステティック契約書記載の役務提供期間を過ぎて残回数が残っている場合は返金の対象外になります。
//やむを得ない事情により、有効期間を延長した場合(弊社指定の手続きが必要です。)も返金の対象外となります。
//なお、PREMIUMコースも同様、契約期間(保証期間)を過ぎて保証回数が残っている場合についても返金の対象外になります。
//", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetTextColor(0, 0, 0);
//$pdf->SetXY( 15, 263 );
$pdf->SetXY( 15, 241 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 4.5, mb_convert_encoding( "●各種特典のご利用については、ご契約いただいた回数の最後に適用可能です。有料の特典については未使用の
   場合のみ、特典分として受領した金額が返金対象となります。また無料の特典については返金対象外です。
●ご購入頂いたコース毎に施術を行います。同コースを部位毎に分けて別日で施術を行うことは出来ません。
", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "3/13" );

//4ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();
//$pdf->SetFont( KOZMIN, 'U', 20 );//underline
//$pdf->SetFont( KOZMIN, 'A', 20 );//download
$pdf->SetDrawColor(255,0,0);
$pdf->Rect(15 ,21 ,40 ,5);
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->SetTextColor(255, 0, 0);
$pdf->Text( 17 , 24  , mb_convert_encoding( "約款を必ずお読みください。", "SJIS", "UTF-8" ) );

$pdf->SetDrawColor(0,0,0);
$pdf->SetFont( KOZMIN,'B' , 18 );
$pdf->SetTextColor(0, 0, 0);
$pdf->Text( 16 , 33  , mb_convert_encoding( "エステティックサービス契約書", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->SetTextColor(255, 0, 0);
$pdf->Text( 17 , 38  , mb_convert_encoding( "別紙の約款に基づき以下のとおり契約を締結します。", "SJIS", "UTF-8" ) );

$pdf->SetTextColor(0, 0, 0);

$pdf->SetXY( 15, 40 );
$pdf->Cell(30, 5, mb_convert_encoding( "お客様番号", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(60, 5, mb_convert_encoding( $_GET['no'], "SJIS", "UTF-8" ), 1);
$pdf->Cell(30, 5, mb_convert_encoding( "契約日", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(60, 5, mb_convert_encoding( str_replace("-", "/",$_GET['hope_date']), "SJIS", "UTF-8" ), 1);
$pdf->SetXY( 15, 45 );
$pdf->MultiCell(6, 6.25, mb_convert_encoding( "ご契約者", "SJIS", "UTF-8" ), 1, 'CC', 0);
$pdf->SetXY( 21, 45 );
$pdf->Cell(24, 5, mb_convert_encoding( "フリガナ", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(150, 5, mb_convert_encoding(  $_GET['name_kana'], "SJIS", "UTF-8" ), 1);
$pdf->SetXY( 21, 50 );
$pdf->Cell(24, 5, mb_convert_encoding( "お名前", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(150, 5, mb_convert_encoding(  $_GET['name'], "SJIS-win", "UTF-8" ), 1);
$pdf->SetXY( 21, 55 );
$pdf->Cell(24, 5, mb_convert_encoding( "生年月日", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(150, 5, mb_convert_encoding(  $_GET['birthday'], "SJIS", "UTF-8" ), 1);
$pdf->SetXY( 21, 60 );
$pdf->Cell(24, 5, mb_convert_encoding( "ご住所", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(150, 5, mb_convert_encoding(  $_GET['address'], "SJIS", "UTF-8" ), 1);
$pdf->SetXY( 21, 65 );
$pdf->Cell(24, 5, mb_convert_encoding( "ご連絡先", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(150, 5, mb_convert_encoding( "携帯：". str_replace("-", "- ",$_GET['tel']), "SJIS", "UTF-8" ), 1);
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 16 , 78  , mb_convert_encoding( "■コース", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 80 );
$pdf->Cell(70, 10, mb_convert_encoding( "脱毛内容明細（コース名）", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(10, 10, mb_convert_encoding( "回数", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetFont( KOZMIN,'' , 7 );
$pdf->Cell(15, 5, mb_convert_encoding( "1回のお手れ", "SJIS", "UTF-8" ), "LTR",0,"L");
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Cell(40, 5, mb_convert_encoding( "定価", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(45, 5, mb_convert_encoding( "割引後金額", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 95, 85 );
$pdf->SetFont( KOZMIN,'' , 7 );
$pdf->Cell(15, 5, mb_convert_encoding( "時間(分)", "SJIS", "UTF-8" ), "LRB",0,"C");
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Cell(20, 5, mb_convert_encoding( "単価(円)", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "料金(円)", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "単価(円)", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "料金(円)", "SJIS", "UTF-8" ), 1,0,"C");

// コース1
$pdf->SetXY( 15, 90 );
$pdf->Cell(6,  10, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding(  $_GET['course_name'], "SJIS", "UTF-8" ), 0,0,"L");
$pdf->Cell(10, 10, mb_convert_encoding( $_GET['times'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(15, 10, mb_convert_encoding( $_GET['length'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( $_GET['fixed_per_price'], "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['fixed_price']), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( $_GET['discount_per_price'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( number_format($_GET['discount_fixed_price']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->SetXY( 110, 95 );
$pdf->Cell(20, 5, mb_convert_encoding( "契約期間", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( $contract_period , "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 95 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Cell(6,  0, mb_convert_encoding( "", "SJIS", "UTF-8" ), 0,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( $_GET['contract_part'], "SJIS", "UTF-8" ), 1,0,"B");
$pdf->SetFont( KOZMIN,'' , 10 );

// コース2
$pdf->SetXY( 15, 100 );
$pdf->Cell(6,  10, mb_convert_encoding( "2", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding(  $_GET['course_name2'], "SJIS", "UTF-8" ), 0,0,"L");
$pdf->Cell(10, 10, mb_convert_encoding( $_GET['times2'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(15, 10, mb_convert_encoding( $_GET['length2'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( $_GET['fixed_per_price2'], "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['fixed_price2']), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( $_GET['discount_per_price2'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( number_format($_GET['discount_fixed_price2']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->SetXY( 110, 105 );
$pdf->Cell(20, 5, mb_convert_encoding( "契約期間", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( $contract_period2 , "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 105 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Cell(6,  0, mb_convert_encoding( "", "SJIS", "UTF-8" ), 0,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( $_GET['contract_part2'], "SJIS", "UTF-8" ), 1,0,"B");
$pdf->SetFont( KOZMIN,'' , 10 );

// コース3
$pdf->SetXY( 15, 110 );
$pdf->Cell(6, 10, mb_convert_encoding( "3", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding(  $_GET['course_name3'], "SJIS", "UTF-8" ), 0,0,"L");
$pdf->Cell(10, 10, mb_convert_encoding( $_GET['times3'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(15, 10, mb_convert_encoding( $_GET['length3'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( $_GET['fixed_per_price3'], "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['fixed_price3']), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( $_GET['discount_per_price3'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( number_format($_GET['discount_fixed_price3']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->SetXY( 110, 115 );
$pdf->Cell(20, 5, mb_convert_encoding( "契約期間", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( $contract_period3 , "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 115 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Cell(6,  0, mb_convert_encoding( "", "SJIS", "UTF-8" ), 0,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( $_GET['contract_part3'], "SJIS", "UTF-8" ), 1,0,"B");
$pdf->SetFont( KOZMIN,'' , 10 );

// コース4
$pdf->SetXY( 15, 120 );
$pdf->Cell(6, 10, mb_convert_encoding( "4", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding(  $_GET['course_name4'], "SJIS", "UTF-8" ), 0,0,"L");
$pdf->Cell(10, 10, mb_convert_encoding( $_GET['times4'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(15, 10, mb_convert_encoding( $_GET['length4'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( $_GET['fixed_per_price4'], "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['fixed_price4']), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( $_GET['discount_per_price4'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( number_format($_GET['discount_fixed_price4']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->SetXY( 110, 125 );
$pdf->Cell(20, 5, mb_convert_encoding( "契約期間", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( $contract_period4 , "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 125 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Cell(6,  0, mb_convert_encoding( "", "SJIS", "UTF-8" ), 0,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( $_GET['contract_part4'], "SJIS", "UTF-8" ), 1,0,"B");
$pdf->SetFont( KOZMIN,'' , 10 );

// コース5
$pdf->SetXY( 15, 130 );
$pdf->Cell(6, 10, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding(  $_GET['course_name5'], "SJIS", "UTF-8" ), 0,0,"L");
$pdf->Cell(10, 10, mb_convert_encoding( $_GET['times5'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(15, 10, mb_convert_encoding( $_GET['length5'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( $_GET['fixed_per_price5'], "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['fixed_price5']), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( $_GET['discount_per_price5'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( number_format($_GET['discount_fixed_price5']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->SetXY( 110, 135 );
$pdf->Cell(20, 5, mb_convert_encoding( "契約期間", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( $contract_period5 , "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 135 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Cell(6,  0, mb_convert_encoding( "", "SJIS", "UTF-8" ), 0,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( $_GET['contract_part5'], "SJIS", "UTF-8" ), 1,0,"B");
$pdf->SetFont( KOZMIN,'' , 10 );

$pdf->SetFont( KOZMIN,'' , 7 );
$pdf->Text( 17 , 144  , mb_convert_encoding( "※1回当たりのお手入れ時間は、目安となります。", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 148  , mb_convert_encoding( "※コース金額につきましては、概要書面発行日当日にご契約いただいた場合の金額となります。", "SJIS", "UTF-8" ) );
// $pdf->Text( 17 , 142  , mb_convert_encoding( "※上記PREMIUMコースの回数は返金保証回数であり、役務の提供は期間・回数共に無制限といたします。", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 16 , 158  , mb_convert_encoding( "■特典明細", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 10 );

$pdf->SetXY( 15, 160 );
$pdf->Cell(135, 5, mb_convert_encoding( "特典明細", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "回数", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "特典価格（円）", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 165 );
$pdf->Cell(6, 5, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 170 );
$pdf->Cell(6, 5, mb_convert_encoding( "2", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 175 );
$pdf->Cell(6, 5, mb_convert_encoding( "3", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 180 );
$pdf->Cell(6, 5, mb_convert_encoding( "4", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 185 );
$pdf->Cell(6, 5, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 16 , 203  , mb_convert_encoding( "■割引", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 10 );

// 以前までの書き方（コースが増えたら下記でレイアウト変更する
$pdf->SetXY( 15, 205 );
$pdf->Cell(70, 10, mb_convert_encoding( "割引対象コース", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 10, mb_convert_encoding( "割引明細", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(45, 5, mb_convert_encoding( "割引金額", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 150, 210 );
$pdf->Cell(20, 5, mb_convert_encoding( "単価(円)", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "料金(円)", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 215 );
$pdf->Cell(6, 5, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( ($_GET['discount'] ? $_GET['course_name'] : ""), "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( ($_GET['discount']  ? ($_GET['times'] ? number_format(round($_GET['discount']/ $_GET['times'])) : $_GET['discount']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($_GET['discount'] ? number_format($_GET['discount']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->SetXY( 15, 220 );
$pdf->Cell(6, 5, mb_convert_encoding( "2", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( ($_GET['discount2'] ? $_GET['course_name2'] : ""), "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( ($_GET['discount2']  ? ($_GET['times2'] ? number_format(round($_GET['discount2']/ $_GET['times2'])) : $_GET['discount2']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($_GET['discount2'] ? number_format($_GET['discount2']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->SetXY( 15, 225 );
$pdf->Cell(6, 5, mb_convert_encoding( "3", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( ($_GET['discount3'] ? $_GET['course_name3'] : ""), "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( ($_GET['discount3']  ? ($_GET['times3'] ? number_format(round($_GET['discount3']/ $_GET['times3'])) : $_GET['discount3']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($_GET['discount3'] ? number_format($_GET['discount3']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->SetXY( 15, 230 );
$pdf->Cell(6, 5, mb_convert_encoding( "4", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( ($_GET['discount4'] ? $_GET['course_name4'] : ""), "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( ($_GET['discount4']  ? ($_GET['times4'] ? number_format(round($_GET['discount4']/ $_GET['times4'])) : $_GET['discount4']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($_GET['discount4'] ? number_format($_GET['discount4']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->SetXY( 15, 235 );
$pdf->Cell(6, 5, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( ($_GET['discount5'] ? $_GET['course_name5'] : ""), "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( ($_GET['discount5']  ? ($_GET['times5'] ? number_format(round($_GET['discount5']/ $_GET['times5'])) : $_GET['discount5']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($_GET['discount5'] ? number_format($_GET['discount5']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
// $pdf->SetXY( 15, 180 );
// $pdf->Cell(70, 10, mb_convert_encoding( "割引対象コース", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(65, 10, mb_convert_encoding( "割引明細", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(45, 5, mb_convert_encoding( "割引金額", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->SetXY( 150, 185 );
// //$pdf->Cell(20, 5, mb_convert_encoding( "単価(円)", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(45, 5, mb_convert_encoding( "料金(円)", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->SetXY( 15, 190 );
// $pdf->Cell(6, 5, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(64, 5, mb_convert_encoding( ($_GET['discount'] ? $_GET['course_name'] : ""), "SJIS", "UTF-8" ), 1,0,"L");
// $pdf->Cell(65, 15, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C"); // 割引明細
// //$pdf->Cell(20, 15, mb_convert_encoding( ($_GET['discount']  ? ($_GET['times'] ? number_format(round($_GET['discount']/ $_GET['times'])) : $_GET['discount']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
// $pdf->Cell(45, 15, mb_convert_encoding( ($_GET['discount'] ? number_format($_GET['discount']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
// $pdf->SetXY( 15, 230 );

// // 割引対象コース（複数コース時）
// $pdf->SetXY( 15, 195 );
// $pdf->Cell(6, 5, mb_convert_encoding( "2", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(64, 5, mb_convert_encoding( ($_GET['discount'] ? $_GET['course_name2'] : ""), "SJIS", "UTF-8" ), 1,0,"L");
// $pdf->SetXY( 15, 200 );
// $pdf->Cell(6, 5, mb_convert_encoding( "3", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(64, 5, mb_convert_encoding( ($_GET['discount'] ? $_GET['course_name3'] : ""), "SJIS", "UTF-8" ), 1,0,"L");

$pdf->SetFillColor(238, 233, 233);
$pdf->Rect( 120 ,246 ,30 ,10, 'DF');
$pdf->SetXY( 120, 246);
$pdf->Cell(30, 10, mb_convert_encoding( "定価合計", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(45, 10, mb_convert_encoding( number_format($_GET['fixed_price']+$_GET['fixed_price2']+$_GET['fixed_price3']+$_GET['fixed_price4']+$_GET['fixed_price5']."円")."円", "SJIS", "UTF-8" ), 1,0,"R");
// $pdf->Rect( 120 ,236 ,30 ,10, 'DF');
// $pdf->SetXY( 120, 236);
// $pdf->Cell(30, 10, mb_convert_encoding( "割引合計", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(45, 10, mb_convert_encoding( number_format($_GET['discount']+$_GET['discount2']+$_GET['discount3'])."円", "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Rect( 120 ,256 ,30 ,10, 'DF');
$pdf->SetXY( 120, 256);
$pdf->Cell(30, 10, mb_convert_encoding( "総支払合計", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(45, 10, mb_convert_encoding( number_format(($_GET['fixed_price']+$_GET['fixed_price2']+$_GET['fixed_price3']+$_GET['fixed_price4']+$_GET['fixed_price5'])."円"-$_GET['discount']-$_GET['discount2']-$_GET['discount3']-$_GET['discount4']-$_GET['discount5'])."円", "SJIS", "UTF-8" ), 1,0,"R");

// $pdf->SetXY( 15, 180 );
// $pdf->Cell(70, 10, mb_convert_encoding( "割引対象コース", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(65, 10, mb_convert_encoding( "割引明細", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(45, 5, mb_convert_encoding( "割引金額", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->SetXY( 150, 185 );
// //$pdf->Cell(20, 5, mb_convert_encoding( "単価(円)", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(45, 5, mb_convert_encoding( "料金(円)", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->SetXY( 15, 190 );
// $pdf->Cell(6, 15, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(64, 5, mb_convert_encoding( ($_GET['discount'] ? $_GET['course_name'] : ""), "SJIS", "UTF-8" ), 1,0,"L");

// $pdf->Cell(65, 15, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
// //$pdf->Cell(20, 15, mb_convert_encoding( ($_GET['discount']  ? ($_GET['times'] ? number_format(round($_GET['discount']/ $_GET['times'])) : $_GET['discount']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
// $pdf->Cell(45, 15, mb_convert_encoding( ($_GET['discount'] ? number_format($_GET['discount']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
// $pdf->SetXY( 15, 205 );
// $pdf->Cell(6, 15, mb_convert_encoding( "2", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(64, 15, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(65, 15, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(45, 15, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->SetXY( 15, 220 );
// $pdf->Cell(6, 15, mb_convert_encoding( "3", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(64, 15, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(65, 15, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(45, 15, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

// // 割引対象コース（複数コース時）
// $pdf->SetXY( 15, 195 );
// $pdf->Cell(6, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 0,0,"C");
// $pdf->Cell(64, 5, mb_convert_encoding( ($_GET['discount'] ? $_GET['course_name2'] : ""), "SJIS", "UTF-8" ), 1,0,"L");
// $pdf->SetXY( 15, 200 );
// $pdf->Cell(6, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 0,0,"C");
// $pdf->Cell(64, 5, mb_convert_encoding( ($_GET['discount'] ? $_GET['course_name3'] : ""), "SJIS", "UTF-8" ), 1,0,"L");

// $pdf->SetXY( 120, 250);
// $pdf->SetFillColor(238, 233, 233);
// $pdf->Rect( 120 ,250 ,30 ,10, 'DF');
// $pdf->Cell(30, 10, mb_convert_encoding( "総支払合計", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(45, 10, mb_convert_encoding( number_format(($_GET['fixed_price']+$_GET['fixed_price2']+$_GET['fixed_price3'])-$_GET['discount'])." 円", "SJIS", "UTF-8" ) , 1,0,"R");

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "4/13" );

//5ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 16 , 20  , mb_convert_encoding( "■備考", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 22);
$pdf->MultiCell(180, 60, mb_convert_encoding( $_GET['memo'], "SJIS", "UTF-8" ), 1,"T");


$pdf->Text( 16 , 89  , mb_convert_encoding( "2.お支払いの方法・時期", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 90 );
$pdf->Cell(135, 5, mb_convert_encoding( "お支払い方法", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "金額", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "入金日", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 95 );
$pdf->Cell(6, 5, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "現金", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['payment_cash']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($_GET['payment_cash'] ? str_replace("-", "/",$_GET['hope_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 100 );
$pdf->Cell(6, 5, mb_convert_encoding( "2", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "カード", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['payment_card']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($_GET['payment_card'] ? str_replace("-", "/",$_GET['hope_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 105 );
$pdf->Cell(6, 5, mb_convert_encoding( "3", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "銀行振込", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['payment_transfer']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($_GET['payment_transfer'] ? str_replace("-", "/",$_GET['hope_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 110 );
$pdf->Cell(6, 5, mb_convert_encoding( "4", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "ローン", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['payment_loan']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($_GET['payment_loan'] ? str_replace("-", "/",$_GET['hope_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 115 );
if($_GET['payment_coupon']){
$pdf->Cell(6, 5, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "クーポン", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['payment_coupon']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($_GET['payment_coupon'] ? str_replace("-", "/",$_GET['hope_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 120 );
$pdf->Cell(6, 5, mb_convert_encoding( "6", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( $balance_name, "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( ($_GET['balance'] ? number_format($_GET['balance']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
}else{
$pdf->Cell(6, 5, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( $balance_name, "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( ($_GET['balance'] ? number_format($_GET['balance']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 120 );
$pdf->Cell(6, 5, mb_convert_encoding( "6", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
}

$pdf->SetXY( 15, 125 );
$pdf->Cell(6, 5, mb_convert_encoding( "7", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 130 );
$pdf->Cell(6, 5, mb_convert_encoding( "8", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 135 );
$pdf->Cell(6, 5, mb_convert_encoding( "9", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 140 );
$pdf->Cell(6, 5, mb_convert_encoding( "10", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");

$pdf->SetFillColor(238, 233, 233);
$pdf->Rect( 15 ,145 ,135 ,5, 'DF');

$pdf->SetXY( 15, 145 );
$pdf->Cell(135, 5, mb_convert_encoding( "合計", "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['payment_cash']+$_GET['payment_card']+$_GET['payment_transfer']+$_GET['payment_loan']+$_GET['payment_coupon']+$_GET['balance']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( "-", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 150 );
$pdf->Cell(180, 5, mb_convert_encoding( "前払い金の保金処置：なし", "SJIS", "UTF-8" ), 1,0,"L");

$pdf->Rect(95 ,160 ,100 ,15);
$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 177  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 17 , 190  , mb_convert_encoding( "上記内容を確かに受け取りました。", "SJIS", "UTF-8" ) );


// if($_GET['shop_id']==6 && $_GET['hope_date']<'2015-01-04'){

// $pdf->Image("./admin/img/pdf/ckr.png",155,200,24); //横幅のみ指定,24

// $pdf->SetFont( KOZMIN,'' , 8 );
// $pdf->Text( 103 , 208  , mb_convert_encoding( "(乙)会  社  名", "SJIS", "UTF-8" ) );
// $pdf->SetFont( KOZMIN,'B' , 10 );
// $pdf->Text( 122 , 208  , mb_convert_encoding( "株式会社CKR", "SJIS", "UTF-8" ) );

// $pdf->SetFont( KOZMIN,'' , 8 );
// $pdf->Text( 107 , 212  , mb_convert_encoding( "所  在  地", "SJIS", "UTF-8" ) );
// $pdf->Text( 122 , 212  , mb_convert_encoding( "〒150- 0012　東京都渋谷区広尾5-25-5", "SJIS", "UTF-8" ) );
// $pdf->Text( 122 , 215  , mb_convert_encoding( "広尾アネックスビル7F", "SJIS", "UTF-8" ) );

// $pdf->Text( 107 , 219  , mb_convert_encoding( "電話番号", "SJIS", "UTF-8" ) );
// $pdf->Text( 122 , 219  , mb_convert_encoding( "TEL: 03- 5422- 7501　FAX:03- 3447- 6086", "SJIS", "UTF-8" ) );

// $pdf->Text( 107 , 223  , mb_convert_encoding( "代  表  者", "SJIS", "UTF-8" ) );
// $pdf->Text( 122 , 223  , mb_convert_encoding( "代表取締役社長　大澤　美加", "SJIS", "UTF-8" ) );

// }else{

$pdf->Image("./admin/img/pdf/mens_stamp.png",155,200,24); //横幅のみ指定,24

$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Text( 103 , 208  , mb_convert_encoding( "(乙)会  社  名", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 122 , 208  , mb_convert_encoding( "株式会社　カレント", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Text( 107 , 212  , mb_convert_encoding( "所  在  地", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 212  , mb_convert_encoding( $company_zip_code, "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 215  , mb_convert_encoding( $company_address, "SJIS", "UTF-8" ) );

$pdf->Text( 107 , 219  , mb_convert_encoding( "電話番号", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 219  , mb_convert_encoding( "TEL: ".$company_tel_no."　FAX: ".$company_fax_no, "SJIS", "UTF-8" ) );

$pdf->Text( 107 , 223  , mb_convert_encoding( "代  表  者", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 223  , mb_convert_encoding( "代表取締役社長　".$mens_kireimo_ceo, "SJIS", "UTF-8" ) );

//}


$pdf->Text( 107 , 227  , mb_convert_encoding( "サロン名", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 122 , 227  , mb_convert_encoding( "MEN'S KIREIMO", "SJIS", "UTF-8" ) );

$pdf->Image("./admin/images/shared/logo.png",20,230,40); //横幅のみ指定,24

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Text( 106 , 235  , mb_convert_encoding( "店  舗  名： ".$_GET['shop_name']."                                                              ", "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 240  , mb_convert_encoding( "店舗住所： ".$_GET['shop_address'], "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 245  , mb_convert_encoding( "電話番号： ".$_GET['shop_tel']."                                               ", "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 250  , mb_convert_encoding( "担 当  者： ".$_GET['staff']."                                                         ", "SJIS-win", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "5/13" );

//6ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );

$pdf->Text( 73 , 10  , mb_convert_encoding( "エステティックサービス約款", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 19  , mb_convert_encoding( "第1条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 16.8 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(契約の成立)
お客様(以下「甲」といいます)は、本契約書の記載内容および約款の各条項を承諾の上、本日当サロン(以下「乙」いいます)に対して
、エステティックサービス(以下「役務」といいます)にお申し込みを行い、乙はこれを承諾しました。
2. 甲が未成年の場合は、親権者の同意が必要としますので、「親権者同意書」等の書面で親権者の同意を乙が確認した上で、本契約の
    成立となります。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 38  , mb_convert_encoding( "第2条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 35.2 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(役務内容)
乙は甲に対し、本契約書に記載するコースプランおよびその回数の役務を提供するものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 46  , mb_convert_encoding( "第3条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 44.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(役務の金額、支払方法、支払時期)
乙は、甲に提供する役務の対価、関連商品がある場合は、その代金その他甲が支払わなければならない金額を本契約に記します。
2. 甲は、役務の支払い方法として、前払金の現金一括払いまたは乙と提携するクレジット会社の立替払い等の中から甲の希望する方法
    を選択できるものとします。
3. 甲が前項の前払い一括払いを選択した場合、契約日にその全額を持ち合わせていない場合、甲は一時金(手付金)を納付するものとし
    ます。
4. 前項の場合、甲は契約日から起算して90日以内に前払金の残金のお支払いがない場合、乙は甲が前項の手付金を放棄したものとし
    て、本契約が解約処理となる事に異議を述べないものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 77  , mb_convert_encoding( "第4条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 72.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(役務の提供期間)
役務の提供期間は、本契約書に記載された期間とします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetDrawColor(255,0,0);
$pdf->Rect(13 ,82 ,186 ,80);
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->SetTextColor(255, 0, 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 86  , mb_convert_encoding( "第5条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 83.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(クーリング・オフ)
甲は、契約書面を受領した日から起算して8日間以内であれば、書面により契約を解除することができます。ただし、プラン組替
の場合は、本条は適用外となります。
2. 乙が甲に不実のことを告げ、または威迫等によりクーリング・オフが妨害された場合、甲は改めてクーリング・オフができる旨を記
   載した書面を受領し、乙より説明を受けた日から起算して8日間以内であれば、書面によるクーリング・オフをすることができます。
3. 前2項に基づく解除がなされた場合、関連商品販売契約についても、その契約を解除することができます。但し、関連商品を
    開封したり、その全部もしくは一部を消費した時は、当該商品に限りクーリング・オフすることはできません。関連商品の引き
    渡しが既に行われている場合は、当該関連商品の引き取りに要する費用は乙の負担とします。
4. クーリング・オフは、甲がクーリング・オフの書面を乙宛てに発信した時に、その効力が生じます。クレジットを利用した契約の
    場合、甲は乙に契約の解除を申し出た旨をクレジット会社にも別途書面による通知をするものとします。
5. 本条による契約解除については、違約金及び利用したサービスの対価は不要とし、乙は甲から受領した前受金及び関連商品
    販売に関し金銭を受領している場合には、当該金銭につき速やかに甲に返還するものとします。なお、当該金銭を返還する際
    の費用は乙の負担とします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 27, 126 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(160, 3.2, mb_convert_encoding( "
  	                                                         クーリング・オフ(契約解除)の文例
20○○年〇月○日、貴社〇〇店との間で締結したエステティックサービス契約について、約款第5条に基づき契約を解除し
ます。つきましては、私が貴社に支払った代金○○○円を下記銀行口座に振り込んでください。（また、私が受け取った商品
をお引き取りください）
○○銀行〇○支店　普通預金口座○○○○　口座名義人　○○○○
20○○年〇月〇日
契約者 (住所)
            (氏名)　　　　　　　　　    　印
".
($_GET['shop_id']==6 && $_GET['hope_date']<'2015-01-04' ? "株式会社CKR　代表者　大澤　美加" : "株式会社カレント　代表者　".$mens_kireimo_ceo)."殿

", "SJIS", "UTF-8" ) , 1, 'L', 0);


$pdf->SetTextColor(0, 0, 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 167  , mb_convert_encoding( "第6条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 164.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(中途解約)
甲は、クーリング・オフ期間を過ぎても、関連商品を含め契約の中途解約ができます。また、1コースにつき、1契約となるため、1コース毎の解約が可能です。
2. 中途解約に関して、既にお支払いいただいている1コース毎の金額の内、未消化役務分の10％（契約金額を契約回数で除した結果が割り切れない場合1円未満を四捨五入）を解約手数料金額としてお支払いいただきます。（上限2万円）
3. 返金に関しては、未消化役務の金額より、前項の解約手数料金額と銀行送金手数料を差し引いた金額を返金いたします。
    但し、各PREMIUMコースご契約の場合は、本契約書記載の契約単価に利用回数をかけた金額を消化額とし、支払総額から消化額を
    引いた金額より、前項の解約手数料金額と銀行送金手数料を差し引いた金額を返金いたします。
    また、返金額が振込手数料額以下の場合、返金は行わず、また、振込手数料の請求も行いません。
4. お支払いの方法としてローンをご契約のお客様につきましては、中途解約の場合、上記解約手数料金額に加えて、契約ローン会社からの
    ローンキャンセル手数料を別途頂戴致します。
5. ローンキャンセル手数料の算出方法につきましては、ローン会社の規定に従い算出させていただきます。
6. 関連商品の場合、解約手数料はかかりませんが、返送の費用および返金の振込手数料は、甲の負担とします。但し、乙に商品到着後の
    返金となります。
7. 但し、関連商品の場合、商品を開封したり、その全部もしくは一部を消費した時は、当該商品に限り中途解約することができません。
    また、未使用であっても、著しく商品価値が損なわれている場合は、返金対象外となる場合があります。
8. 役務の提供期間が過ぎた契約については、解約ができませんのでご注意ください
9. クレジット等をご使用の場合の精算は、各クレジット会社の所定の方法によるものとします。また、甲は乙がクレジット会社の請求により
    精算上必要な範囲において、甲の利用回数をクレジット会社に通知することを承諾するものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 232  , mb_convert_encoding( "第7条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 229.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(施術上の注意)
乙は、甲に役務提供するにあたり、事前に甲の体質（治療中の皮膚疾患・アレルギー・敏感肌・薬の服用の有無など）および体調を聴取
    し、確認するものとします。甲の体調・体質により、甲への役務提供をお断りする場合があります。
2. 役務提供期間中、甲が体調を崩したり、施術部位に異常が生じた場合、甲はその旨を乙に伝えるものとします。この場合、乙は直ちに
    役務を中止します。その原因が乙の施術に起因する疑いがある場合は、一旦乙の負担で、甲に医師の診断を受けて頂く等の適切な処置
    を取ることとし、甲乙協議の上解決するものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 253  , mb_convert_encoding( "第8条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 250.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(抗弁権の接続)
甲は、信販を利用して支払う場合、割賦販売法により、乙との間で生じている事由をもって、信販会社からの請求を拒否出来ます
    （これを抗弁権の接続といいます。）。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 268  , mb_convert_encoding( "第9条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 264.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(別途協議)
本契約書に定める事項に疑義が生じた場合は、甲乙協議の上解決するものとします。 
    2. 本契約書に定めのない事項については、民法その他の法令によるものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "6/13" );

//7ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 75 , 34  , mb_convert_encoding( "MEN'S KIREIMOのご案内", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 58  , mb_convert_encoding( "1. 副反応について", "SJIS", "UTF-8" ) );
$pdf->SetXY( 17, 60 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "・施術後お肌に副反応(赤み、かゆみ、ヒリつき等)が出る可能性がございます。
	清潔な濡れタオルなどで冷やしていただきますようお願い致します。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 92  , mb_convert_encoding( "2. パックプラン(複数回)について", "SJIS", "UTF-8" ) );
$pdf->SetXY( 17, 94 );
$pdf->SetFillColor(256, 256, 0);

$pdf->Rect( 18 ,113 ,130 ,5, 'F');
$pdf->Rect( 18 ,119 ,156 ,5, 'F');

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "・ご予約時間に遅刻された場合は出来る限りお手入れをさせて頂きますが、出来なかった箇所については
	消化扱いとなりますのでご了承下さい。
・シェービングのサービスは基本的に行っておりません。
  ご予約の1～2日前にお客様自身でシェービングをして頂くようお願い致します。
・解約をご希望の場合はMEN'S KIREIMOコールセンター（0120-444-276）へお問い合わせ下さい。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFillColor(255, 105, 180);
$pdf->Rect( 18 ,124 ,110 ,5, 'F');
$pdf->Rect( 18 ,131 ,138 ,5, 'F');
$pdf->Rect( 18 ,137 ,178 ,5, 'F');
$pdf->Rect( 18 ,143 ,99 ,5, 'F');

$pdf->SetXY( 17, 124 );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "・残金のお支払いは契約日から30日以内にご入金をお願い致します。(初回の施術はお支払い後となります。)
※30日以内に残りの代金をお支払頂けない場合は、お電話にてご連絡させて頂きます。
※契約日から90日以内に残りの代金をお支払頂けない場合は、サービスをご提供できないことと手付金を放棄し
   たものとして契約を解除させて頂きますのでご注意下さい。
・ご購入頂いたコース毎に施術を行います。同コースを部位毎に分けて別日で施術を行うことは出来かねます
   ので、ご了承ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetXY( 17, 177 );
$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 175  , mb_convert_encoding( "3. パックプラン(1回)  ・カスタマイズ(1回)について", "SJIS", "UTF-8" ) );

$pdf->SetFillColor(256, 256, 0);
$pdf->Rect( 18 ,194 ,130 ,5, 'F');
$pdf->Rect( 18 ,200 ,176 ,5, 'F');
$pdf->Rect( 18 ,206 ,82 ,5, 'F');

$pdf->SetXY( 17, 176 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "・ご予約時間に遅刻された場合は出来る限りお手入れをさせて頂きますが、出来なかった箇所については
	消化扱いとなりますのでご了承下さい。
・シェービングのサービスは基本的に行っておりません。
	ご予約の1～2日前にお客様自身でシェービングをして頂くようお願い致します。
・1回コースの役務提供期間は購入日から30日（購入日含まない）になります。
・中途解約、クーリング・オフの対象外となります。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 212 );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "・ご購入頂いたコース毎に施術を行います。同コースを部位毎に分けて別日で施術を行うことは出来かねます
	ので、ご了承ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 240 );
$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 238  , mb_convert_encoding( "4. その他", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "・店舗にお忘れ物をされた場合、店舗内で一定期間保管後、警察に届け出いたしますので、一定期間内に取りに
	来られない場合は、警察までお問い合わせください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetXY( 17, 239 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "7/13" );

//8ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 73 , 24  , mb_convert_encoding( "個人情報のお取扱いについて", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 35  , mb_convert_encoding( "1.個人情報保護方針", "SJIS", "UTF-8" ) );
$pdf->SetXY( 17, 38 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "株式会社".($_GET['shop_id']==6 && $_GET['hope_date']<'2015-01-04' ? "CKR" : "カレント")."（以下「当社」といいます）は、お客様に安全で快適なエステティックサービスを提供するにあたり「個人情報の保護に関する法律」他、関係諸法令並びに本方針と当社の各規程を遵守し、お客様の個人情報保護の徹底に努めます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 63  , mb_convert_encoding( "2.個人情報の定義", "SJIS", "UTF-8" ) );
$pdf->SetXY( 17, 66 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "「個人情報」とは、当社がお客様から提供を受けた氏名、住所、電話番号、メールアドレス、性別、生年月日等の特定の個人を識別することができる情報又は個人識別符号が含まれる情報をいいます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 85  , mb_convert_encoding( "3.個人情報の取得", "SJIS", "UTF-8" ) );
$pdf->SetXY( 17, 88 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "個人情報の取得の取得にあたっては、適法かつ公正な手段によって行い、不正な方法によって取得致しません。当社では、個人情報の取得にあたっては、その利用目的を予め公表し、お客様から同意の上取得いたします。
当社は、要配慮個人情報として法令で定められている情報を取得する場合においても、お客様の同意の上取得します。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 119  , mb_convert_encoding( "4.個人情報の利用目的", "SJIS", "UTF-8" ) );
$pdf->SetXY( 17, 122 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "当社は、お客様よりお預かりした個人情報を、以下の目的の範囲内で利用致します。
 (1)  ご契約・ご予約の管理のため
 (2)  ご予約、お申込みにおけるお客様確認のため
 (3)  ご契約、ご解約等に係るご連絡、ご案内を行うため
 (4)  施術の実施、サービスの提供等にかかるご連絡、ご案内を行うため
 (5)  新サービス、キャンペーン等をご案内するため
 (6)  アフターサービスの充実を図るため
 (7)  メールマガジン、ダイレクトメール等の発送のため
 (8)  サービス等、継続的な情報提供のため
 (9)  市場調査ならびに、データ分析やアンケート実施などによる商品、サービスの研究や開発のため
 (10) 当選した商品の発送のため
 (11) その他、お客様とのお取引・ご契約を適切かつ円滑に履行するため
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 202  , mb_convert_encoding( "5.個人情報の委託", "SJIS", "UTF-8" ) );
$pdf->SetXY( 17, 205 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "当社は、利用目的の達成に必要な範囲内において、取得した個人情報の取り扱いの全部または一部を委託する場合があります。その場合には、個人情報の委託に係わる基本契約等の必要な契約を締結し、委託先への必要かつ適切な監督を行います。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 230  , mb_convert_encoding( "6.個人情報の第三者への提供", "SJIS", "UTF-8" ) );
$pdf->SetXY( 17, 233 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "当社は、以下に定める場合を除き、あらかじめお客様の同意を得ることなく、お客様より取得した個人情報を当社以外の第三者に提供することはありません。
(1)  法令に基づく場合
(2)  人の生命、身体または財産の保護のために必要がある場合であって本人の同意を得ることが困難であるとき
(3)  公衆衛生の向上または児童の健全な育成の推進のために特に必要がある場合であって、本人の同意を得ることが困難な場合
", "SJIS", "UTF-8" ) , 0, 'L', 0);



$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "8/13" );


//9ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->SetXY( 17, 10 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "
(4)  国の機関もしくは地方公共団体またはその委託を受けた者が法令の定める事務を遂行することに対して協力する必要がある場合であって、本人の同意を得ることにより当該事務の遂行に支障を及ぼすおそれがあるとき
(5)  利用目的の達成に必要な範囲内で業務を委託する場合
(6)  弁護士、公認会計士、税理士等への業務の委任に伴って、当該委任業務の処理に必要な範囲内で、当該弁護士等に対し、個人情報を開示する場合
(7)  合併、事業譲渡等による事業承継に伴い、個人情報を引き継ぐ場合
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 61  , mb_convert_encoding( "7.個人情報提供の任意性", "SJIS", "UTF-8" ) );
$pdf->SetXY( 17, 64 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "お客様が当社に対して個人情報を提供することは任意です。ただし、個人情報を提供されない場合には、当社からの返信やサービスの提供ができない場合がありますので、あらかじめご了承ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 85  , mb_convert_encoding( "8.個人情報の開示請求等について", "SJIS", "UTF-8" ) );
$pdf->SetXY( 17, 88 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "当社は、お客様より、当社が保有するお客様の個人情報の開示、内容の訂正、追加又は削除、利用の停止等の申し出があった場合には、ご本人の確認をさせて頂いた上で、遅滞なく対応致します。但し、請求が法令による要件を満たさない場合及び当社の最終のご利用から相当期間を経過したお客様の情報に関しましては対応できない場合があります。

【問合せ先】
".
($_GET['shop_id']==6 && $_GET['hope_date']<'2015-01-04' ? "会社名：株式会社CKR
代表者：代表取締役　大澤　美加
本　社：東京都渋谷区広尾5-25-5　広尾アネックスビル7F " : "会社名：株式会社カレント
代表者：代表取締役　".$mens_kireimo_ceo."
本　社：" .$company_address)
."
お客様相談室：電話　".$_GET['shop_tel']."
※ 受付時間は12：00～20：00（年末年始を除く）とさせて頂いております。

私は、貴社の個人情報のお取扱いについて（以下、「本件規程」といいます。）を理解した上で、以下の事項に同意します。
(1)　貴社が明示する目的の範囲内で、貴社が私の個人情報を取得し利用すること
(2)　本件規程の範囲内で、私の個人情報が第三者へ開示される場合があること
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 20 , 205  , mb_convert_encoding( substr($_GET['hope_date'], 0,4)." 年 ".substr($_GET['hope_date'], 5,2)." 月 ".substr($_GET['hope_date'], 8,2)." 日", "SJIS", "UTF-8" ) );


$pdf->SetDrawColor(0,0,0);
$pdf->Rect(95 ,210 ,100 ,15);

$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 227  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "9/13" );


//10ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 70 , 24  , mb_convert_encoding( "除毛・減毛トリートメント同意書", "SJIS", "UTF-8" ) );

$pdf->SetXY( 12, 27 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "当サロンで行うトリートメントはＩＰＬを用いた機器を使用し施術を行います。
トリートメントを安心してお受けいただくため、下記内容についてご確認・ご承諾をお願いいたします。
ご不明な点はスタッフにご質問ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 12, 45 );
$pdf->SetFont( KOZMIN,'U' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "下記の内容は禁忌とされている状態、または箇所になります。原則として施術を行うことができませんので、ご了承下さい。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 12, 45 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(100, 6, mb_convert_encoding( "
・現在治療中または持病等をお持ちの方
・ガン、てんかん等の既往歴がある方
・感染症もしくは、感染症の疑いがある方
・光アレルギー、紫外線アレルギー、光線過敏症の方
・ケロイドになりやすい方
・過度な敏感肌の方
・飲酒後の方や、飲酒のご予定のある方
   (お手入れの前後12時間はお控えください)
・粘膜部位
・白髪部位
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 90, 45 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(130, 6, mb_convert_encoding( "
・白斑症もしくは、白斑症の疑いがある方
・皮膚トラブルで通院中の方や、皮膚疾患部位（傷、湿疹、腫れ物、アザ等）
・過度の日焼けをしている方、日焼けをされるご予定がある方
・ペースメーカーなどの医療用機器を使用されている方
・体調がすぐれない方
・ほくろ､アートメイクをされている部位､タトゥー及び､刺青をされている部位
", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetXY( 12, 131 );
$pdf->SetFont( KOZMIN,'U' , 10 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "施術後は、下記内容の副反応が起こる場合があります。", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFillColor(256, 256, 0);
$pdf->Rect( 16 ,137 ,76 ,4, 'F');
$pdf->Rect( 16 ,147 ,96 ,4, 'F');
$pdf->Rect( 16 ,162 ,127 ,4, 'F');

$pdf->SetXY( 12, 132 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(190, 5, mb_convert_encoding( "
・施術後一時的に毛穴の赤みが起こる場合があります。患部を清潔な冷タオルで冷やし、掻いたり、こすらないようご注意ください。

　 なお、お客様の肌の状態が施術に起因するものか判断が困難な場合、当社にて責任を負いかねます。
・施術部位またはその近くの皮膚が過敏になる場合があります。皮膚に傷をつけたり、こすったりしないようご注意ください。
・過度な日焼け、乾燥は火傷の可能性が高く施術をお断りすることがございます。十分に保湿を行って頂きますようお願い致します。
　 火傷によるかさぶたが出来た際は全治まで2週間程度かかる場合もございます。
・施術後1ヶ月以内に過度な日光を浴びた場合、施術部位に色素沈着を残すことがあります。日焼け対策をお願い致します。
", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetXY( 12, 171 );
$pdf->SetFont( KOZMIN,'U' , 10 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "下記内容は、トリートメント期間中の注意事項になります。", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFillColor(256, 256, 0);
$pdf->Rect( 16 ,177 ,99 ,4, 'F');
$pdf->Rect( 16 ,182 ,122 ,4, 'F');
$pdf->Rect( 16 ,202 ,145 ,4, 'F');
$pdf->Rect( 16 ,222 ,52 ,4, 'F');
$pdf->Rect( 16 ,232 ,163 ,4, 'F');

$pdf->SetXY( 12, 172 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(182, 5, mb_convert_encoding( "
・トリートメント前日にトリートメント部位の剃毛を行ってください。
・施術の効果は個人差があり、目に見えてのご実感は平均して3,4回目以降になります。（毛周期や満足度により異なります。）
・個人の体調やホルモンバランスにより、脱毛後、毛が再生する可能性もございます。
・トリートメント期間中は日焼けをしないでください。
   （期間中、過度な日焼けはトラブルの原因になり、施術不可になります。）
・トリートメント当日は、サウナ・スポーツ・飲酒などの体温上昇、発汗を促す行為は避けてください。
   (当日の湯船での入浴は避けて頂き、シャワーのみにして下さい。またトリートメント部位はナイロンタオルの使用
   も避けて下さい。)
・日のあたる箇所には、日焼け止めを塗ってください。(ＳＰＦ15程度)
・日ごろから保湿ケアをしてください。(かゆみの防止、肌がやわらかくなり埋もれ毛も出やすくなり、脱毛効果が上がります)
・トリートメント期間中は、規則正しい生活を心がけ下さい。
・トリートメント期間中の自己処理は、毛抜きや、ワックス、脱色などでの処理は行わず、剃るのみにしてください。
    (効果を高めるため、できるだけ刺激にならないよう剃毛の回数を減らすことをおすすめしています。)

上記内容について、ご理解・ご承諾いただきましたら、誠に恐れいりますが、ご署名お願い致します。

", "SJIS", "UTF-8" ) , 0, 'L', 0);




$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 20 , 265  , mb_convert_encoding( substr($_GET['hope_date'], 0,4)." 年 ".substr($_GET['hope_date'], 5,2)." 月 ".substr($_GET['hope_date'], 8,2)." 日", "SJIS", "UTF-8" ) );




$pdf->SetDrawColor(0,0,0);
$pdf->Rect(95 ,260 ,100 ,15);

$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 277  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "10/13" );

//12ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 42 , 15  , mb_convert_encoding( "エステティックサービス契約　ご契約内容チェックシート", "SJIS", "UTF-8" ) );

$pdf->SetXY( 17, 20 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "この｢ご契約内容チェックシート｣は、ご契約が、ご契約者様のご希望に沿った内容になっていること、お引き受けするご契約
の内容が適切であることをご契約者様に確認させていただくためのものです。
ご契約のプランの種類にしたがい、以下の該当箇所につきまして、ご確認の上、チェックを入れてください。

", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 41 );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 17 , 41  , mb_convert_encoding( "< パックプラン(複数回)をお申し込みのご契約者様 >", "SJIS", "UTF-8" ) );

$pdf->SetXY( 17, 35 );
$pdf->SetFont( KOZMIN,'' , 9 );

$pdf->SetFillColor(256, 256, 0);
$pdf->Rect( 23 ,51 ,80 ,4, 'F');
$pdf->Rect( 23 ,57 ,152 ,4, 'F');
$pdf->Rect( 23 ,64 ,118 ,4, 'F');

$pdf->MultiCell(190, 7, mb_convert_encoding( "
□　1コースにつき1契約となり、1コース毎に解約が可能です。
□　クーリング・オフ期間は、ご契約日から8日以内です。
□　中途解約(クーリング・オフ期間外、契約日から9日目以後の解約)は1コース毎に解約手数料を頂戴します。
      残りの施術回数分の金額の10％(上限2万円)を解約手数料とさせて頂いております。
□　キャンペーンはお1人様1回までのご利用とさせて頂きます。プラン変更の際のキャンペーンの適用は致し兼ねます。
□　弊社でシェービングをお手伝いする場合、パーツごとに異なりますがシェービング代最大5,000円(税込)を別途頂戴いたします。
□　役務の提供期間は契約期間となります。契約期間を過ぎた場合、施術は出来かねますのでご了承下さい。
□　ご購入頂いたコース毎に施術を行います。同コースを部位毎に分けて別日で施術を行うことは出来かねます
   ので、ご了承ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 117 );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 17 , 117  , mb_convert_encoding( "< パックプラン(1回)  ・カスタマイズ(1回)をお申込みのご契約者様 >", "SJIS", "UTF-8" ) );

$pdf->SetFillColor(256, 256, 0);
$pdf->Rect( 23 ,120 ,70 ,4, 'F');
$pdf->Rect( 23 ,126 ,181 ,4, 'F');

$pdf->SetXY( 17, 111 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(190, 7, mb_convert_encoding( "
□　クーリング・オフ、中途解約対象外のプランです。ご契約後の返金は致し兼ねます。
□　弊社でシェービングをお手伝いする場合、パーツごとに異なりますがシェービング代最大5,000円(税込)を別途頂戴いたします。
□　役務の提供期間は契約期間となります。契約期間を過ぎた場合、施術は出来かねますのでご了承下さい。
□　ご購入頂いたコース毎に施術を行います。同コースを部位毎に分けて別日で施術を行うことは出来かねます
      ので、ご了承ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetXY( 17, 158 );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 17 , 158  , mb_convert_encoding( "< ローンをお申込みのご契約者様 >", "SJIS", "UTF-8" ) );

$pdf->SetXY( 17, 152 );
$pdf->SetFont( KOZMIN,'' , 9 );

$pdf->MultiCell(190, 7, mb_convert_encoding( "
□　ローンをお申込みの場合、別途分割手数料を頂戴します。
□　ローンをお申込みの場合、支払い方法、回数の変更は致しかねます。
□　ご指定頂いた日時にお申込のローン会社から契約内容、ご本人確認のお電話がございますのでご対応お願い致します。
□　ローンを中途解約される場合、ローンキャンセル手数料はお客様のご負担となります。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 18 , 244  , mb_convert_encoding( substr($_GET['hope_date'], 0,4)." 年 ".substr($_GET['hope_date'], 5,2)." 月 ".substr($_GET['hope_date'], 8,2)." 日", "SJIS", "UTF-8" ) );
$pdf->Text( 18 , 249  , mb_convert_encoding( "上記内容を確認しました。", "SJIS", "UTF-8" ) );

$pdf->SetDrawColor(0,0,0);
$pdf->Rect(95 ,251 ,100 ,15);

$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 268  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "11/13" );

//12ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 64 , 15  , mb_convert_encoding( "PREMIUMコースに関する同意書", "SJIS", "UTF-8" ) );

$pdf->SetXY( 17, 20 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "本書は、PREMIUMコース（以下「本コース」という）に関する諸注意事項等を明記したものになります。 
本書は、概要書面およびエステティックサービス利用約款に付随し、一体となって契約内容となります。以下を確認のうえ、 本コースに同意ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 35 );
$pdf->SetFont( KOZMIN,'' , 9 );

$pdf->MultiCell(198, 6, mb_convert_encoding( "
□ 本コースの契約期間は、「エステティックサービス契約書」記載の契約期間とし、期間満了をもって終了となります。
    本コースは、契約期間終了後も期間・回数とも無制限で、無償にて（但し、当社でシェービングをお手伝いする場合、
    パーツごとに異なりますがシェービング代最大5,000円(税込)を別途頂戴いたします。）役務提供を致します。
    但し、当該役務提供を受けることができる対象者は、現金一括払い、クレジットカード一括払いもしくは当社と提携する
    ローン会社の立替払いのいずれかの支払が完了しており、かつ、概要書面およびエステティックサービス契約書に定める契
    約期間中に指定する回数の本サービスの提供を受けたお客様のみになります。
□ 本コースは、現金一括払い、クレジットカード一括払いもしくは当社と提携するローン会社の立替払いのいずれかに なります。
□ 契約期間満了後の役務提供は、契約クレジット、ローン会社の対象期間とはなりませんので、仮に役務提供が受けられ
    なくても、クレジット契約、ローン契約に基づく支払い停止の抗弁や既払金の返金原因とはなりません。
    （お支払いの方法としてクレジット、ローンをご契約のお客様のみ対象となります。）
□ 本コースは契約期間中の中途解約は可能になります。なお、中途解約における解約手数料等は以下のとおりとなります。
    残	金　 =　支払総額金 - （1回あたりの料金 × 利用回数）
    解約手数料金額 =　残金×10％（最大￥20,000）
    精	算	金   =　残金 - 解約手数料金額
□ 本コースは、契約期間が過ぎた場合、契約書に定める役務の指定回数が未消化であっても、返金の対象外となります。
□ 契約期間内までは最短14日間に1回予約、契約期間終了後の来店の場合は30日に1回電話予約とさせていただきます。
□ 繁忙期等については、予約が立て込み予約がとりにくくなる場合がございますので、予めご了承ください。
□ 契約期間終了後以降は、お申込みプラン内でパーツでの施術が可能です。 契約期間内まではパーツ施術の場合、全身
    1回分消化となりますので、全身の施術でのご来店をお勧めいたします。
□ 本書に記載なき事項は、エステティックサービス利用約款に準拠いたします。

<ローンをお申込みのご契約者様>

□ ローンはローン会社の審査により、契約ができない場合がございます。予めご了承ください。
□ ローンをお申込みの場合、別途分割手数料を頂戴します。
□ ローンをお申込みの場合、支払い方法、回数の変更は致しかねます。
□ ご指定頂いた日時にお申込みのローン会社から契約内容、ご本人確認の電話がございますのでご対応お願いいたします。
□ ローンを中途解約される場合、ローンキャンセル手数料はお客様のご負担となります。

以上、同意のうえ、私はPREMIUMコースに申込みいたします。

", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 18 , 244  , mb_convert_encoding( substr($_GET['hope_date'], 0,4)." 年 ".substr($_GET['hope_date'], 5,2)." 月 ".substr($_GET['hope_date'], 8,2)." 日", "SJIS", "UTF-8" ) );

$pdf->SetDrawColor(0,0,0);
$pdf->Rect(95 ,251 ,100 ,15);

$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 268  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "12/13" );

//13ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 64 , 15  , mb_convert_encoding( "２年間通い放題プランに関する同意書", "SJIS", "UTF-8" ) );

$pdf->SetXY( 17, 20 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "本書は、２年間通い放題プラン（以下「本プラン」という）に関する諸注意事項等を明記したものになります。 
本書は、概要書面およびエステティックサービス利用約款に付随し、一体となって契約内容となります。以下を確認のうえ、 本プランに同意ください。 
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 35 );
$pdf->SetFont( KOZMIN,'' , 9 );

$pdf->MultiCell(198, 6, mb_convert_encoding( "
□ 本プランの契約期間は、1年間とし、期間満了をもって終了となります。
□ 本プランには、契約期間満了後に１年間の無償役務提供期間が付与されます。
□ 本プランは、現金一括払い、クレジットカード一括払いもしくは当社と提携するローン会社の立替払いのいずれかになります。 
□ 本プランは契約期間中の中途解約が可能です。なお、中途解約における解約手数料等は以下の通りとなります。 
    残金　=　支払総額 - （1回あたりの料金 × 利用回数） 
    解約手数料 =　残金×10％（最大￥20,000） 
    精算金額 =　残金 - 解約手数料
□ 本プランは、契約期間が過ぎた場合は契約書に定める役務の指定回数が未消化であっても、返金の対象外となります。 
□ 契約期間内及び無償役務提供期間に関しては最短30日に1回の予約とさせて頂きます。 
□ 繁忙期等については、予約が立て込み予約が取りにくくなる場合がございますので、予めご了承ください。 
□ 本書に記載なき事項は、エステティックサービス利用約款に準拠いたします。 
□ ローンはローン会社の審査により、契約ができない場合がございます。予めご了承ください。
□ ローンをお申込みの場合、別途分割手数料を頂戴します。 
□ ローンをお申込みの場合、支払い方法、回数の変更は致しかねます。 
□ ご指定頂いた日時にお申込みのローン会社から契約内容、ご本人確認の電話がございますのでご対応お願いいたします。 
□ ローンを中途解約される場合、ローンキャンセル手数料はお客様のご負担となります。 

以上、同意のうえ、私は２年間通い放題プランに申込みいたします。

", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 18 , 166  , mb_convert_encoding( substr($_GET['hope_date'], 0,4)." 年 ".substr($_GET['hope_date'], 5,2)." 月 ".substr($_GET['hope_date'], 8,2)." 日", "SJIS", "UTF-8" ) );

$pdf->SetDrawColor(0,0,0);
$pdf->Rect(95 ,175 ,100 ,15);

$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 192  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "13/13" );


//$pdf->Output();
$pdf->Output($_GET['name'].$_GET['no'],"I");

?>
