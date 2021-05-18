<?php
require_once(dirname(__FILE__) . '/../../php-lib/fpdf/mbfpdf.php');
require_once(dirname(__FILE__) . '/../../admin/library/pdf/pdf_out.php');

header('Content-type: application/pdf');
header('Content-disposition: attachment; filename="'.$customer_name.'.pdf"');

$title = "";


$pdf=new MBFPDF();
//$pdf->FPDF(Portrait ,mm,A4);//Landscape
$pdf->AddMBFont( GOTHIC , 'SJIS' );
$pdf->AddMBFont( PGOTHIC, 'SJIS' );
$pdf->AddMBFont( MINCHO , 'SJIS' );
$pdf->AddMBFont( PMINCHO, 'SJIS' );
$pdf->AddMBFont( KOZMIN , 'SJIS' );
$pdf->Open();


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
$pdf->Text( 165 , 36  , mb_convert_encoding( "No. ".substr($contract['contract_date'], 2,2).substr($contract['contract_date'], 5,2)."- ". $customer['no']."  ", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 18 );
$pdf->Text( 75 , 40  , mb_convert_encoding( "概 要 書 面", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 14 );
$pdf->Text( 107 , 40  , mb_convert_encoding( "(事前説明書)", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 16 , 48  , mb_convert_encoding( substr($contract['contract_date'], 0,4)." 年 ".substr($contract['contract_date'], 5,2)." 月 ".substr($contract['contract_date'], 8,2)." 日", "SJIS", "UTF-8" ) );

// if($shop['id']==6 && $contract['contract_date']<'2015-01-04'){
// $pdf->Image("../../img/ckr.png",155,38,24); //横幅のみ指定,24

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
$pdf->Image("../../img/stamp.png",155,38,24); //横幅のみ指定,24

$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Text( 107 , 48  , mb_convert_encoding( "会  社  名", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 122 , 48  , mb_convert_encoding( "株式会社　ヴィエリス", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Text( 107 , 52  , mb_convert_encoding( "所  在  地", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 52  , mb_convert_encoding( $company_zip_code, "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 55  , mb_convert_encoding( $company_address, "SJIS", "UTF-8" ) );

$pdf->Text( 107 , 59  , mb_convert_encoding( "電話番号", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 59  , mb_convert_encoding( "TEL: ".$company_tel_no."　FAX: ".$company_fax_no, "SJIS", "UTF-8" ) );

$pdf->Text( 107 , 63  , mb_convert_encoding( "代  表  者", "SJIS", "UTF-8" ) );
//$pdf->Text( 122 , 63  , mb_convert_encoding( "代表取締役社長　吉福　優", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 63  , mb_convert_encoding( "代表取締役社長　".$kireimo_ceo, "SJIS", "UTF-8" ) );
// }

$pdf->Text( 107 , 67  , mb_convert_encoding( "サロン名", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 122 , 67  , mb_convert_encoding( "KIREIMO", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Text( 16 , 67  , mb_convert_encoding( "お名前　".$customer_name."　　　　　様 ", "SJIS-win", "UTF-8" ) );
$pdf->Text( 106 , 74  , mb_convert_encoding( "店  舗  名： ".$shop['name']."                                                              ", "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 78  , mb_convert_encoding( "店舗住所： ".$shop['address'], "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 82  , mb_convert_encoding( "電話番号： ".$shop_tel."                                               ", "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 86  , mb_convert_encoding( "作  成  者： ".$staff['name']."                                                         ", "SJIS-win", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Text( 16 , 93  , mb_convert_encoding( "ご利用を希望されるサービスの内容をご確認ください。", "SJIS", "UTF-8" ) );

$pdf->Text( 16 , 98  , mb_convert_encoding( "1.ご利用希望サービス", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 16 , 103  , mb_convert_encoding( "■コース", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 105 );
$pdf->Cell(70, 10, mb_convert_encoding( "脱毛内容明細（コース名）", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(10, 10, mb_convert_encoding( "回数", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetFont( KOZMIN,'' , 7 );
$pdf->Cell(15, 5, mb_convert_encoding( "1回のお手入", "SJIS", "UTF-8" ), "LTR",0,"L");
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Cell(40, 5, mb_convert_encoding( "定価", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(45, 5, mb_convert_encoding( "割引後金額", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 95, 110 );
$pdf->SetFont( KOZMIN,'' , 7 );
$pdf->Cell(15, 5, mb_convert_encoding( "れ時間(分)", "SJIS", "UTF-8" ), "LRB",0,"C");
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Cell(20, 5, mb_convert_encoding( "単価(円)", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "料金(円)", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "単価(円)", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "料金(円)", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 115 );
$pdf->Cell(6, 10, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 10, mb_convert_encoding( $course['name'], "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(10, 10, mb_convert_encoding( $times, "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(15, 10, mb_convert_encoding( $length, "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( ($times ? number_format($per_fixed_price) : 0), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($contract['fixed_price']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(20, 5, mb_convert_encoding( ($times ? number_format($per_price) : 0), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( number_format($contract['fixed_price'] - $contract['discount']), "SJIS", "UTF-8" ), 1,0,"R");

$pdf->SetXY( 110, 120 );
$pdf->Cell(20, 5, mb_convert_encoding( "契約期間", "SJIS", "UTF-8" ), 1,0,"C");
//$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( $contract_period, "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 125 );
$pdf->Cell(6, 10, mb_convert_encoding( "2", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 10, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(10, 10, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(15, 10, mb_convert_encoding( "", "SJIS", "UTF-8" ), "1",0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 110, 130 );
$pdf->Cell(20, 5, mb_convert_encoding( "契約期間", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 135 );
$pdf->Cell(6, 10, mb_convert_encoding( "3", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 10, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(10, 10, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(15, 10, mb_convert_encoding( "", "SJIS", "UTF-8" ), "1",0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 110, 140 );
$pdf->Cell(20, 5, mb_convert_encoding( "契約期間", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetFont( KOZMIN,'' , 7 );
$pdf->Text( 17 , 149  , mb_convert_encoding( "※1回当たりのお手入れ時間は、目安となります。※コース金額につきましては、概要書面発行日当日にご契約いただいた場合の金額となります。", "SJIS", "UTF-8" ) );
// $pdf->Text( 17 , 153  , mb_convert_encoding( "※単価は18回を想定しての目安となります。", "SJIS", "UTF-8" ) );


$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 16 , 163  , mb_convert_encoding( "■特典明細", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 165 );
$pdf->Cell(135, 5, mb_convert_encoding( "特典明細", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "回数", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "特典価格(円)", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 170 );
$pdf->Cell(6, 5, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( $option_name, "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( $option_times, "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( $option_price, "SJIS", "UTF-8" ), 1,0,"R");

$pdf->SetXY( 15, 175 );
$pdf->Cell(6, 5, mb_convert_encoding( "2", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");

$pdf->SetXY( 15, 180 );
$pdf->Cell(6, 5, mb_convert_encoding( "3", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");

$pdf->SetXY( 15, 185 );
$pdf->Cell(6, 5, mb_convert_encoding( "4", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");

$pdf->SetXY( 15, 190 );
$pdf->Cell(6, 5, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 16 , 203  , mb_convert_encoding( "■割引", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 205 );
$pdf->Cell(70, 10, mb_convert_encoding( "割引対象コース", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 10, mb_convert_encoding( "割引明細", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(45, 5, mb_convert_encoding( "割引金額", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 150, 210 );
$pdf->Cell(20, 5, mb_convert_encoding( "単価(円)", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "料金(円)", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 215 );
$pdf->Cell(6, 5, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( ($contract['discount'] ? $course['name'] : ""), "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( ($contract['discount'] ? ($times ? number_format($per_discount) : number_format($contract['discount'])) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($contract['discount'] ? number_format($contract['discount']) : ""), "SJIS", "UTF-8" ), 1,0,"R");

$pdf->SetXY( 15, 220 );
$pdf->Cell(6, 5, mb_convert_encoding( "2", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 225 );
$pdf->Cell(6, 5, mb_convert_encoding( "3", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 230 );
$pdf->Cell(6, 5, mb_convert_encoding( "4", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 235 );
$pdf->Cell(6, 5, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 240 );
$pdf->Cell(6, 5, mb_convert_encoding( "6", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 245 );
$pdf->Cell(6, 5, mb_convert_encoding( "7", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 250 );
$pdf->Cell(6, 5, mb_convert_encoding( "8", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 255 );
$pdf->Cell(6, 5, mb_convert_encoding( "9", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 260 );
$pdf->Cell(6, 5, mb_convert_encoding( "10", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetFillColor(238, 233, 233);
$pdf->Rect( 120 ,266 ,30 ,10, 'DF');
$pdf->SetXY( 120, 266);
$pdf->Cell(30, 10, mb_convert_encoding( "総支払合計", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(45, 10, mb_convert_encoding( number_format($contract['price'])."円", "SJIS", "UTF-8" ), 1,0,"R");


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "1/15" );


//2ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 16 , 20  , mb_convert_encoding( "■備考", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 22);

// 新月額のみ、下記の文言を備考に入れる
if($course['type'] == 1){
	$pdf->MultiCell(180, 7, mb_convert_encoding( "・月額プランは契約終了月の末日の2ヶ月前までに当社に申し出がない場合、契約期間は更に2ヶ月間更新し、
	それ以降も同様です。




	", "SJIS", "UTF-8" ), 1,"T");
} else {
	$pdf->MultiCell(180, 60, mb_convert_encoding( $contract['memo'], "SJIS", "UTF-8" ), 1,"T");
}

$pdf->Text( 16 , 89  , mb_convert_encoding( "2.お支払いの方法・時期", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 90 );
$pdf->Cell(135, 5, mb_convert_encoding( "お支払い方法", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "金額", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "入金日", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 95 );
$pdf->Cell(6, 5, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "現金", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($contract['payment_cash']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($contract['payment_cash'] ? str_replace("-", "/",$contract['contract_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 100 );
$pdf->Cell(6, 5, mb_convert_encoding( "2", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "カード", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($contract['payment_card']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($contract['payment_card'] ? str_replace("-", "/",$contract['contract_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 105 );
$pdf->Cell(6, 5, mb_convert_encoding( "3", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "銀行振込", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($contract['payment_transfer']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($contract['payment_transfer'] ? str_replace("-", "/",$contract['contract_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 110 );
$pdf->Cell(6, 5, mb_convert_encoding( "4", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "ローン", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($contract['payment_loan']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($contract['payment_loan'] ? str_replace("-", "/",$contract['contract_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 115 );
if($contract['payment_coupon']){
	$pdf->Cell(6, 5, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
	$pdf->Cell(129, 5, mb_convert_encoding( "クーポン", "SJIS", "UTF-8" ), 1,0,"L");
	$pdf->Cell(20, 5, mb_convert_encoding( number_format($contract['payment_coupon']), "SJIS", "UTF-8" ), 1,0,"R");
	$pdf->Cell(25, 5, mb_convert_encoding( ($contract['payment_coupon'] ? str_replace("-", "/",$contract['contract_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");

	$pdf->SetXY( 15, 120 );
	$pdf->Cell(6, 5, mb_convert_encoding( "6", "SJIS", "UTF-8" ), 1,0,"C");
	$pdf->Cell(129, 5, mb_convert_encoding( ($contract['balance'] ? "残金" : ""), "SJIS", "UTF-8" ), 1,0,"L");
	$pdf->Cell(20, 5, mb_convert_encoding( ($contract['balance'] ? number_format($contract['balance']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
	$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");

}else{
	$pdf->Cell(6, 5, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
	$pdf->Cell(129, 5, mb_convert_encoding( ($contract['balance'] ? "残金" : ""), "SJIS", "UTF-8" ), 1,0,"L");
	$pdf->Cell(20, 5, mb_convert_encoding( ($contract['balance'] ? number_format($contract['balance']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
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
$pdf->Cell(20, 5, mb_convert_encoding( number_format($contract['payment_cash'] + $contract['payment_card'] + $contract['payment_transfer'] + $contract['payment_loan'] + $contract['payment_coupon'] + $contract['balance']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( "-", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 150 );
$pdf->Cell(180, 5, mb_convert_encoding( "前払い金の保全措置：なし", "SJIS", "UTF-8" ), 1,0,"L");

$pdf->SetFont( KOZMIN,'' , 7 );
$pdf->Text( 17 , 158  , mb_convert_encoding( "※クレジットご利用の場合、抗弁権の接続ができます。詳細については契約書を良くお読み下さい。", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 162  , mb_convert_encoding( "　前受金の保全措置はありません。", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 17 , 170  , mb_convert_encoding( "3.特約事項：なし", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 180  , mb_convert_encoding( "4.契約の解除に関する事項：クーリング・オフ並びに中途解約につきましては、概要書面の該当欄をご確認ください。", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 17 , 200  , mb_convert_encoding( "お客様記入欄", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Text( 20 , 205  , mb_convert_encoding( "私はこの書面によりサービス内容の説明を受け、概要書面を確かに受け取りました。", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 20 , 220  , mb_convert_encoding( substr($contract['contract_date'], 0,4)." 年 ".substr($contract['contract_date'], 5,2)." 月 ".substr($contract['contract_date'], 8,2)." 日", "SJIS", "UTF-8" ) );
$pdf->Rect(95 ,240 ,100 ,15);

$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 257  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "2/15" );


//3ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'' , 12 );
$pdf->SetTextColor(255, 0, 0);
$pdf->Text( 17 , 7  , mb_convert_encoding( "<クーリング・オフについて>", "SJIS", "UTF-8" ) );

$pdf->SetDrawColor(255,0,0);
$pdf->Rect(15 ,9 ,180 ,49);

$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Text( 17 , 12  , mb_convert_encoding( "■お客様は、締結した契約書面を受領した日から起算して8日以内であれば、書面により、関連商品を含めその契約を解除（クーリング・", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 17  , mb_convert_encoding( "オフ）できます。また、お客様が、クーリング・オフに関し当社から不実のことを告げられ誤認し又は威迫により困惑し、クーリング・オ", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 22  , mb_convert_encoding( "フを行わなかった場合には、当該期間経過後も書面によりその契約をクーリング・オフすることができます。但し、関連商品において、そ", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 27  , mb_convert_encoding( "の全部若しくは一部を開封したり使用したりしたときは、その対象ではございませんが、当社がお客様に商品を使用させ、また消費させた", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 32  , mb_convert_encoding( "場合はこの限りではありません。", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 37  , mb_convert_encoding( "■クーリング・オフは、書面を当社宛に発信したときにその効力が生じます。", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 42  , mb_convert_encoding( "■クーリング・オフに伴う損害賠償、違約金、本契約役務ご利用代金の支払い請求はいたしません。", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 47  , mb_convert_encoding( "■（クーリング・オフ対象）関連商品の引渡しが既にされているとき、その返還に要する費用は、当社が負担します。", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 52  , mb_convert_encoding( "当社が既に受領した代金は（クーリング・オフ対象外の関連商品代金は除く）速やかにエステティック契約者が指定した口座へ振込みで", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 57  , mb_convert_encoding( "返還致します。（振込み手数料は、当社が負担致します。）", "SJIS", "UTF-8" ) );

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 67  , mb_convert_encoding( "< 中 途 解 約 >", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 68 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 4, mb_convert_encoding( "クーリング・オフ期間を過ぎた場合でも、当社所定の手続きにより、契約を解約することができます。	但し関連商品のみの
解約はできません。
解約時の返金額等の算出方法としては、プラン1回あたりの料金である補正単価（契約金額を契約回数で除した結果が割り切れ
ない場合1円未満を四捨五入）に利用回数をかけた金額を消化額とし、支払総額から消化額を引いた金額を残金とします。解約
手数料金額として残金の10%（最大￥20,000）を差し引き、精算金を算出いたします。
以下に、残金・解約手数料・精算金の算出方法を記載いたします。
    残　　　　　金　=　支払総額 - ( 1回あたりの料金 × 利用回数 )
    解約手数料金額  = ｛ 支払総額  -  ( 1回あたりの料金   ×   利用回数 )  ｝×10% （最大￥20,000）
    清算金  =  残金  -  解約手数料金額
お支払いの方法としてローンをご契約のお客様につきましては、中途解約の場合、上記解約手数料金額に加えて、ローンキャン
セル手数料がお客様のご負担となります。なお、この場合の精算金は以下になります。
    精　　算　　金　=　残金 - 解約手数料金額 - ローンキャンセル手数料
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetTextColor(255, 0, 0);
$pdf->SetXY( 19, 130 );
$pdf->SetFont( KOZMIN,'' , 8.5 );
$pdf->MultiCell(180, 4, mb_convert_encoding( "※ローンキャンセル手数料の算出方法につきましては、ローン会社の規定に従い算出させていただきます。
※複数箇所でコース1回分とみなす契約（全身パック、月額プラン、キャンペーン時におけるセットのコース）につきましてはコースに含まれる箇所を1箇所でもお手入れした場合、コース1回分を消化したとみなし、解約時の返金対象とはなりません。
（例）全身コース6回契約時、ヒザ下1回のみお手入れ後、解約を希望した場合の返金対象は、全身5回分となります。
※現金の受け渡しによる返金は行っておりません。金融機関への振込とさせて頂きます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY( 15, 152 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "以下のいずれかに該当する場合は、当社より契約の解除をさせていただく場合がございます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 19, 157 );
$pdf->SetFont( KOZMIN,'' , 8.5 );
$pdf->MultiCell(174, 4, mb_convert_encoding( "※本サービス料金の、3ヶ月連続でお支払いが確認できない場合。（月額プラン）
※本サービス料金の全額が、契約日より起算して90日以内にお支払いいただけない場合。（パックプラン）
※お客様の体質等に起因して、お手入れの継続が困難だと当社が判断した時。
※お客様との信頼関係の維持が困難と判断した時。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 180  , mb_convert_encoding( "< システム補足 >", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 181 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(190, 4, mb_convert_encoding( "●未成年のお客様については、親権者の同意が必要となります。親権者同意書には、親権者の続柄・ご連絡先の電話番号のご記入
　と署名・捺印が必要になりますので、必ず親権者本人にて署名・捺印ください。また、追って当社より親権者の方へ確認の連絡
　をさせて頂く場合がございますので、予めご了承ください。
●親権者同意書の提出が、初回施術日までにない場合は、契約無効とさせていただきます。
●ご契約に関しては、契約時にお支払い代金の一部(1回当たりの料金以上の金額)を手付金としてお支払いいただきます。なお、契
　約日から90日以内に残りの代金をお支払いいただけない場合は、本サービスの提供を受ける意思がないものとして本契約を解除
　させていただきます。また、支払済みの手付金の返還も放棄したものとみなさせていただきますので、ご注意下さい。（パックプ
　ランの場合）
　なお、契約日から30日以内に残りの代金をお支払いいただいていない場合は、契約解除手続きについてのお知らせをお送りするこ
　とがございます。
●契約期間内に回数消化できない場合、契約終了日から2年間を保証延長期間とし、残回数分の施術を受けることが可能です。なお、
　延長をご希望される方は、当社所定の手続きが必要になります。
　万が一、当該保証延長期間中に解約をご希望される場合は、上記、中途解約の条項を参照ください。（パックプランの場合）
●各種特典のご利用については、ご契約いただいた回数の最後に適用可能です。有料の特典については未使用の場合のみ、特典分
　として受領した金額が返金対象となります。また無料の特典については返金対象外です。
●施術の効果には個人差がございます。本サービスは特定の効果を保証するものではございません。
　また、お支払いいただく代金は施術に対するものであり、特定の効果に対するものではございません。
●本サービスご利用中における損害や怪我、その他の事故について、当社に故意または過失がない場合、その損害に対する一切の
　責任を負いません。
", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "3/15" );


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
$pdf->Cell(60, 5, mb_convert_encoding( $customer['no'], "SJIS", "UTF-8" ), 1);

$pdf->Cell(30, 5, mb_convert_encoding( "契約日", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(60, 5, mb_convert_encoding( str_replace("-", "/",$contract['contract_date']), "SJIS", "UTF-8" ), 1);

$pdf->SetXY( 15, 45 );
$pdf->MultiCell(6, 6.25, mb_convert_encoding( "ご契約者", "SJIS", "UTF-8" ), 1, 'CC', 0);

$pdf->SetXY( 21, 45 );
$pdf->Cell(24, 5, mb_convert_encoding( "フリガナ", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(150, 5, mb_convert_encoding( $customer['name_kana'], "SJIS", "UTF-8" ), 1);

$pdf->SetXY( 21, 50 );
$pdf->Cell(24, 5, mb_convert_encoding( "お名前", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(150, 5, mb_convert_encoding( $customer_name, "SJIS-win", "UTF-8" ), 1);

$pdf->SetXY( 21, 55 );
$pdf->Cell(24, 5, mb_convert_encoding( "生年月日", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(150, 5, mb_convert_encoding( $customer['birthday'], "SJIS", "UTF-8" ), 1);

$pdf->SetXY( 21, 60 );
$pdf->Cell(24, 5, mb_convert_encoding( "ご住所", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(150, 5, mb_convert_encoding( $customer['address'], "SJIS", "UTF-8" ), 1);

$pdf->SetXY( 21, 65 );
$pdf->Cell(24, 5, mb_convert_encoding( "ご連絡先", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(150, 5, mb_convert_encoding( "携帯：". str_replace("-", "- ",$customer['tel']), "SJIS", "UTF-8" ), 1);

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Text( 16 , 78  , mb_convert_encoding( "1.ご利用希望サービス", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 16 , 83  , mb_convert_encoding( "■コース", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 85 );
$pdf->Cell(70, 10, mb_convert_encoding( "脱毛内容明細（コース名）", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(10, 10, mb_convert_encoding( "回数", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetFont( KOZMIN,'' , 7 );
$pdf->Cell(15, 5, mb_convert_encoding( "1回のお手入", "SJIS", "UTF-8" ), "LTR",0,"L");
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Cell(40, 5, mb_convert_encoding( "定価", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(45, 5, mb_convert_encoding( "割引後金額", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 95, 90 );
$pdf->SetFont( KOZMIN,'' , 7 );
$pdf->Cell(15, 5, mb_convert_encoding( "れ時間(分)", "SJIS", "UTF-8" ), "LRB",0,"C");
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Cell(20, 5, mb_convert_encoding( "単価(円)", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "料金(円)", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "単価(円)", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "料金(円)", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 95 );
$pdf->Cell(6, 10, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 10, mb_convert_encoding( $course['name'], "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(10, 10, mb_convert_encoding( $times, "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(15, 10, mb_convert_encoding( $length, "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( ($times ? number_format($per_fixed_price) : 0), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($contract['fixed_price']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(20, 5, mb_convert_encoding( ($times ? number_format($per_price) : 0), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( number_format($contract['fixed_price'] - $contract['discount']), "SJIS", "UTF-8" ), 1,0,"R");

$pdf->SetXY( 110, 100 );
$pdf->Cell(20, 5, mb_convert_encoding( "契約期間", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( $contract_period, "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 105 );
$pdf->Cell(6, 10, mb_convert_encoding( "2", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 10, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(10, 10, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(15, 10, mb_convert_encoding( "", "SJIS", "UTF-8" ), "1",0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 110, 110 );
$pdf->Cell(20, 5, mb_convert_encoding( "契約期間", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 115 );
$pdf->Cell(6, 10, mb_convert_encoding( "3", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 10, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(10, 10, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(15, 10, mb_convert_encoding( "", "SJIS", "UTF-8" ), "1",0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 110, 120 );
$pdf->Cell(20, 5, mb_convert_encoding( "契約期間", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetFont( KOZMIN,'' , 7 );
$pdf->Text( 17 , 129  , mb_convert_encoding( "※1回当たりのお手入れ時間は、目安となります。※コース金額につきましては、概要書面発行日当日にご契約いただいた場合の金額となります。", "SJIS", "UTF-8" ) );
// $pdf->Text( 17 , 128  , mb_convert_encoding( "※単価は18回を想定しての目安となります。", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 16 , 143  , mb_convert_encoding( "■特典明細", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 145 );
$pdf->Cell(135, 5, mb_convert_encoding( "特典明細", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "回数", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "特典価格(円)", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 150 );
$pdf->Cell(6, 5, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( $option_name, "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( $option_times, "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( $option_price, "SJIS", "UTF-8" ), 1,0,"R");

$pdf->SetXY( 15, 155 );
$pdf->Cell(6, 5, mb_convert_encoding( "2", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");

$pdf->SetXY( 15, 160 );
$pdf->Cell(6, 5, mb_convert_encoding( "3", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");

$pdf->SetXY( 15, 165 );
$pdf->Cell(6, 5, mb_convert_encoding( "4", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");

$pdf->SetXY( 15, 170 );
$pdf->Cell(6, 5, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 16 , 183  , mb_convert_encoding( "■割引", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 185 );
$pdf->Cell(70, 10, mb_convert_encoding( "割引対象コース", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 10, mb_convert_encoding( "割引明細", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(45, 5, mb_convert_encoding( "割引金額", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 150, 190 );
$pdf->Cell(20, 5, mb_convert_encoding( "単価(円)", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "料金(円)", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 195 );
$pdf->Cell(6, 5, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( ($contract['discount'] ? $course['name'] : ""), "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( ($contract['discount'] ? ($times ? number_format($per_discount) : number_format($contract['discount'])) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($contract['discount'] ? number_format($contract['discount']) : ""), "SJIS", "UTF-8" ), 1,0,"R");

$pdf->SetXY( 15, 200 );
$pdf->Cell(6, 5, mb_convert_encoding( "2", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 205 );
$pdf->Cell(6, 5, mb_convert_encoding( "3", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 210 );
$pdf->Cell(6, 5, mb_convert_encoding( "4", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 215 );
$pdf->Cell(6, 5, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 220 );
$pdf->Cell(6, 5, mb_convert_encoding( "6", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 225 );
$pdf->Cell(6, 5, mb_convert_encoding( "7", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 230 );
$pdf->Cell(6, 5, mb_convert_encoding( "8", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 235 );
$pdf->Cell(6, 5, mb_convert_encoding( "9", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 240 );
$pdf->Cell(6, 5, mb_convert_encoding( "10", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 120, 250 );
$pdf->SetFillColor(238, 233, 233);
$pdf->Rect( 120 ,250 ,30 ,10, 'DF');
$pdf->Cell(30, 10, mb_convert_encoding( "総支払合計", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(45, 10, mb_convert_encoding( number_format($contract['price'])." 円", "SJIS", "UTF-8" ) , 1,0,"R");


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "4/15" );


//5ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 16 , 20  , mb_convert_encoding( "■備考", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 22);

// 新月額のみ、下記の文言を備考に入れる
if($course['type'] == 1){
	$pdf->MultiCell(180, 7, mb_convert_encoding( "・月額プランは契約終了月の末日の2ヶ月前までに当社に申し出がない場合、契約期間は更に2ヶ月間更新し、
	それ以降も同様です。




	", "SJIS", "UTF-8" ), 1,"T");
} else {
	$pdf->MultiCell(180, 60, mb_convert_encoding( $contract['memo'], "SJIS", "UTF-8" ), 1,"T");
}

$pdf->Text( 16 , 89  , mb_convert_encoding( "2.お支払いの方法・時期", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 90 );
$pdf->Cell(135, 5, mb_convert_encoding( "お支払い方法", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "金額", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "入金日", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 95 );
$pdf->Cell(6, 5, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "現金", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($contract['payment_cash']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($contract['payment_cash'] ? str_replace("-", "/",$contract['contract_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 100 );
$pdf->Cell(6, 5, mb_convert_encoding( "2", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "カード", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($contract['payment_card']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($contract['payment_card'] ? str_replace("-", "/",$contract['contract_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 105 );
$pdf->Cell(6, 5, mb_convert_encoding( "3", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "銀行振込", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($contract['payment_transfer']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($contract['payment_transfer'] ? str_replace("-", "/",$contract['contract_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 110 );
$pdf->Cell(6, 5, mb_convert_encoding( "4", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "ローン", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($contract['payment_loan']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($contract['payment_loan'] ? str_replace("-", "/",$contract['contract_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 115 );
if($contract['payment_coupon']){
	$pdf->Cell(6, 5, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
	$pdf->Cell(129, 5, mb_convert_encoding( "クーポン", "SJIS", "UTF-8" ), 1,0,"L");
	$pdf->Cell(20, 5, mb_convert_encoding( number_format($contract['payment_coupon']), "SJIS", "UTF-8" ), 1,0,"R");
	$pdf->Cell(25, 5, mb_convert_encoding( ($contract['payment_coupon'] ? str_replace("-", "/",$contract['contract_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");

	$pdf->SetXY( 15, 120 );
	$pdf->Cell(6, 5, mb_convert_encoding( "6", "SJIS", "UTF-8" ), 1,0,"C");
	$pdf->Cell(129, 5, mb_convert_encoding( ($contract['balance'] ? "残金" : ""), "SJIS", "UTF-8" ), 1,0,"L");
	$pdf->Cell(20, 5, mb_convert_encoding( ($contract['balance'] ? number_format($contract['balance']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
	$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");

}else{
	$pdf->Cell(6, 5, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
	$pdf->Cell(129, 5, mb_convert_encoding( ($contract['balance'] ? "残金" : ""), "SJIS", "UTF-8" ), 1,0,"L");
	$pdf->Cell(20, 5, mb_convert_encoding( ($contract['balance'] ? number_format($contract['balance']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
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
$pdf->Cell(20, 5, mb_convert_encoding( number_format($contract['payment_cash'] + $contract['payment_card'] + $contract['payment_transfer'] + $contract['payment_loan'] + $contract['payment_coupon'] + $contract['balance']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( "-", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 150 );
$pdf->Cell(180, 5, mb_convert_encoding( "前払い金の保全措置：なし", "SJIS", "UTF-8" ), 1,0,"L");

$pdf->Rect(95 ,160 ,100 ,15);
$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 177  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 17 , 190  , mb_convert_encoding( "上記内容を確かに受け取りました。", "SJIS", "UTF-8" ) );

// if($shop['id']==6 && $contract['contract_date']<'2015-01-04'){
// $pdf->Image("../../img/ckr.png",155,200,24); //横幅のみ指定,24

// $pdf->SetFont( KOZMIN,'' , 8 );
// $pdf->Text( 107 , 208  , mb_convert_encoding( "会  社  名", "SJIS", "UTF-8" ) );
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
$pdf->Image("../../img/stamp.png",155,200,24); //横幅のみ指定,24

$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Text( 107 , 208  , mb_convert_encoding( "会  社  名", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 122 , 208  , mb_convert_encoding( "株式会社　ヴィエリス", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Text( 107 , 212  , mb_convert_encoding( "所  在  地", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 212  , mb_convert_encoding( $company_zip_code, "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 215  , mb_convert_encoding( $company_address, "SJIS", "UTF-8" ) );

$pdf->Text( 107 , 219  , mb_convert_encoding( "電話番号", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 219  , mb_convert_encoding( "TEL: ".$company_tel_no."　FAX: ".$company_fax_no, "SJIS", "UTF-8" ) );

$pdf->Text( 107 , 223  , mb_convert_encoding( "代  表  者", "SJIS", "UTF-8" ) );
//$pdf->Text( 122 , 223  , mb_convert_encoding( "代表取締役社長　吉福　優", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 223  , mb_convert_encoding( "代表取締役社長　".$kireimo_ceo, "SJIS", "UTF-8" ) );
// }

$pdf->Text( 107 , 227  , mb_convert_encoding( "サロン名", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 122 , 227  , mb_convert_encoding( "KIREIMO", "SJIS", "UTF-8" ) );

$pdf->Image("img/logo.png",20,230,40); //横幅のみ指定,24

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Text( 106 , 235  , mb_convert_encoding( "店  舗  名： ".$shop['name']."                                                              ", "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 240  , mb_convert_encoding( "店舗住所： ".$shop['address'], "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 245  , mb_convert_encoding( "電話番号： ".$shop_tel."                                               ", "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 250  , mb_convert_encoding( "担 当  者： ".$staff['name']."                                                         ", "SJIS-win", "UTF-8" ) );


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "5/15" );


//6ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 73 , 10  , mb_convert_encoding( "エステティックサービス約款", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 15  , mb_convert_encoding( "第1条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 12.8 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(本約款の適用範囲）
株式会社ヴィエリス（以下「当社」といいます）は、エステティックサービス約款（以下「本約款」といいます）に基づき、エステ
ティックサービス（以下「本サービス」といいます）を提供するものとします。
2．当社が本約款以外に定める「概要書面（事前説明書）」、「エステティックサービス契約書」、「KIREIMOのご案内」、「エス
　テティックサービス契約　ご契約内容チェックシート」、「除毛・減毛トリートメント同意書」およびその他、当社が定めるもの
　（以下これらを総称して「個別約款」といいます）は、本約款の一部を構成するものとし、本約款と個別約款の定めが異なる場合、
　別段の定めがない限り、個別約款の定めが優先して適用されるものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 40  , mb_convert_encoding( "第2条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 37.2 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(契約の成立)
お客様が当社の定めるエステティックサービス契約書（以下「本契約書」といいます）の記載内容、本約款および個別約款に承諾の上、
本サービスにお申し込みをし、当社がこれを承諾したことによって、エステティックサービス契約（以下「本契約」といいます）が成
立いたします。
2. お客様が未成年の場合は、前項の手続きに合わせて当社所定の親権者同意書にて親権者の同意を当社が確認した上で、本契約の成立
　となります。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 61  , mb_convert_encoding( "第3条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 58.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(役務内容)
当社はお客様に対し、本契約書に記載する月額プランまたはパックプランおよびその回数の本サービスを提供するものとします。なお、
月額プラン、パックプランの詳細は以下のとおりとします。
（1）月額プランとは、2ヶ月間に1度（以下「当該期間」といいます）、本サービスの提供を受けることができるコースです。
（2）パックプランとは、本契約書に定める契約期間中に指定する回数の本サービスの提供を受けることができるコースです。
2. 本サービスの提供を受けるために予約が必要となりますので、当社所定の方法により予約手続きをしていただきます。なお、ご希望
　の予約日が月末、繁忙期と重なる場合、ご希望する日時の予約が取れず、前項第1号の当該期間内に本サービスを提供ができない場
　合がございますので、予めご了承ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 89  , mb_convert_encoding( "第4条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 86.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(本サービスの料金、支払時期、支払方法)
お客様に提供する本サービスの料金、および販売する関連商品等の料金は本契約書に明記します。
2. 本サービスの料金の支払方法は以下のとおりといたします。
（1）月額プランは、1回目の本サービス料金を本契約の契約日にお支払いいただきます。
2回目以降の支払いは、契約時に当社所定の継続手続きを行っていただき、ご契約の翌月より毎月クレジットカード決済もしくは金融機関
口座振替払いのいずれかになります。
（2）パックプランは、現金一括払い、クレジットカード一括払いもしくは当社と提携するローン会社の立替払いのいずれかになります。
3．前項に定める支払方法のうち、金融機関口座振替払いの方で金融機関の決済が取れなかった場合、その月末までにお支払いがない限
　り、すでに予約されている次回以降の予約が取り消しとなります。
4. お客様が第2項第2号において、現金一括払いを選択した場合、本契約の契約日に本サービス料金総額のうちの一部の手付金を納付す
　るものとし、残額は本契約の契約日より90日以内に支払うものとします。また、契約日から起算して90日以内にお支払いがない場合、
　お客様は本契約を解約する意思表示を示したものとし、当社はこれを受け、本契約を解約いたします。また、この場合、お客様が納
　付した手付金の返還を放棄したものとみなします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 133  , mb_convert_encoding( "第5条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 130.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(本サービスの提供期間および期間終了後の措置)
本サービスの提供期間は、本契約書に記載された契約期間とします。なお、月額プランは、契約期間終了日の2ヶ月前の末日までに、
当社へ契約終了の申し出がない場合、本契約は更に2ヶ月間更新し、以後も同様とします。
2．パックプランにおいて、契約期間内に本サービスの提供が全て受けられなかった場合の措置として、当社所定の方法により申請して
　いただいたお客様に限り、契約期間終了日より2年間（以下「保証延長期間」といいます）、残回数分の本サービス提供を受けること
　ができます。
　なお、保証延長期間中に解約される場合は、第7条の定めに従うものとします。但し、返金は対象外となります。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetDrawColor(255,0,0);
$pdf->Rect(13 ,155 ,188 ,84);
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->SetTextColor(255, 0, 0);
$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 159  , mb_convert_encoding( "第6条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 156.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(180, 3.2, mb_convert_encoding( "(クーリング・オフ)
お客様は、契約書面を受領した日から起算して8日間以内であれば、書面により本契約を解除することができます。ただし、プラン組替
の場合は、本条は適用外となります。
2. 当社がお客様に不実のことを告げ、または威迫等によりクーリング・オフが妨害された場合、当社は改めてクーリング・オフができる
 旨を記載した書面を受領し、当社より説明を受けた日から起算して8日間以内であれば、書面によるクーリング・オフをすること
 ができます。
3. 前二項に基づく解除がなされた場合、関連商品販売契約についても、その契約を解除することができます。但し、関連商品を開封した
　り、その全部もしくは一部を消費したりした場合、当該商品に限りクーリング・オフすることはできません。関連商品の引き渡しが既
 に行われている場合は、当該関連商品の引き取りに要する費用は当社の負担とします。
4. クーリング・オフは、お客様がクーリング・オフの書面を当社宛てに発信した時に、その効力が生じます。クレジットを利用した契
　約の場合、お客様は当社に契約の解除を申し出た旨をクレジット会社にも別途書面による通知をしていただく必要がございます。
5. 本条による契約解除については、違約金及び利用した本サービスの料金の支払いは不要とし、当社はお客様から現金一括払い・クレ
ジットカード決済・金融機関口座振替等により受領した前受金及び関連商品販売に関し金銭を受領している場合には、当該金銭につき
速やかにお客様の金融機関口座に振り込みにより返還するものとします。なお、当該金銭を返還する際の費用は当社の負担とします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 26, 202 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(162, 3.2, mb_convert_encoding( "
  	                                                         クーリング・オフ(契約解除)の文例
20○○年〇月○日、貴社〇〇店との間で締結したエステティックサービス契約について、約款第6条に基づき契約を解除し
ます。つきましては、私が貴社に支払った代金○○○円を下記銀行口座に振り込んでください。（また、私が受け取った商品
をお引き取りください）
○○銀行〇○支店　普通預金口座○○○○　口座名義人　○○○○
20○○年〇月〇日
契約者 (住所)
            (氏名)　　　　　　　　　    　印
".
	// ($shop['id']==6 && $contract['contract_date']<'2015-01-04' ? "株式会社CKR　代表者　大澤　美加" : "株式会社ヴィエリス　代表者　".$kireimo_ceo)."殿
	"株式会社ヴィエリス　代表者　".$kireimo_ceo."殿

", "SJIS", "UTF-8" ) , 1, 'L', 0);


$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "6/15" );


//7ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 10  , mb_convert_encoding( "第7条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 7.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(中途解約・返品)
本契約は、クーリング・オフ期間を過ぎても、関連商品を含め契約を以下に定める方法により中途解約をすることができます。
（1）月額プランの場合、契約期間終了日の2ヶ月前の末日までに、当社所定の方法により解約手続きを行うものとします。なお、金
融機関の都合により、解約の申し出時点でクレジット決済または銀行口座振替の中止ができない場合がございますので、その際は当該金
額を金融機関より受領後、すみやかに全額返金します。なお、返金は金融機関口座への振込とし、それにかかる手数料は、お客様負担と
なります。
（2）パックプランの場合、契約期間内に、当社所定の方法により、解約手続きを行うものとします。
2. パックプランのお客様が本契約を中途解約した場合、解約手数料として本サービスの未消化分額の10％（契約金額を契約回数で除した
　結果が割り切れない場合1円未満を四捨五入）をお支払いいただきます。但し、解約手数料の上限額は2万円とします。
3. 中途解約により当社より返金がある場合、本サービスの未消化分の金額より、前項により算出した解約手数料を差し引いた金額を返金
　いたします。なお、返金方法は、金融機関口座への振込払いになり、振込にかかる手数料は、お客様負担となります。なお、返金金額
　が振込手数料額以下の場合、返金は行わず、また振込手数料も請求しないものといたします。
4.お支払い方法がローン契約の場合、第2項により算出した解約手数料に加えて、ローンキャンセル手数料を別途頂戴いたします。なお、
　ローンキャンセル手数料の算出方法につきましては、ローン会社の規定に従い算出させていただきます。
5. 関連商品は、当該商品を開封したり、その全部もしくは一部を消費したりした場合は、返品できないものとします。但し、未使用の場
　合であっても、保存方法により著しく商品価値が損なわれている場合は、返品不可となります。なお、返品にあたっての返送費用およ
　びお客様へ返金がある場合、返金方法は金融機関口座への振込払いとし、それにかかる手数料は、お客様の負担とします。
6. お客様が本サービス料金の支払い方法がクレジットカード払いの場合、本条における精算方法は、各クレジット会社の所定の方法によ
　るものとします。また、お客様は当社がクレジット会社の請求により精算上必要な範囲において、お客様の利用回数をクレジット会社
　に通知することを承諾するものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 76  , mb_convert_encoding( "第8条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 73.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(契約解除）
当社は、お客様が以下のいずれかに該当した場合には、何らの催告なしに本契約を解除することができるものとします。
（1）本契約に違反し、当社より催告されたにも関わらず、是正されていないと判断された場合
（2）本契約における代金の支払いが遅滞した場合
（3）差押え、仮差押え、仮処分その他の強制執行または滞納処分の申し立てを受けた場合
（4）お客様の体質的に起因して、本サービスの提供の継続が困難だと判断した場合
（5）お客様の信用状態に重大な変化が生じた場合
2．前項に基づき当社が本契約を解除したことにより、お客様に生じた不利益、損害について、当社は一切の責任を負わないものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 104  , mb_convert_encoding( "第9条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 101.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(施術上の注意)
当社は、お客様に本サービスを提供するにあたり、事前にお客様の体質（治療中の皮膚疾患・アレルギー・敏感肌・薬の服用の有無など）
および体調を聴取し、確認するものとします。お客様の体調・体質により、お客様への本サービスの提供をお断りする場合があります。
2. 本サービス提供期間中、お客様が体調を崩したり、施術部位に異常が生じたりした場合、お客様はその旨を当社に伝えるものとしま
　す。この場合、当社は直ちに役務を中止します。その原因が当社の施術に起因する疑いがある場合は、一旦当社の負担で、お客様に
　医師の診断を受けて頂く等の適切な処置を取ることとし、当事者間の協議の上解決するものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 126  , mb_convert_encoding( "第10条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 123.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(抗弁権の接続)
お客様は、信販を利用して支払う場合、割賦販売法により、当社との間で生じている事由をもって、信販会社からの請求を拒否することが
出来ます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 139  , mb_convert_encoding( "第11条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 136.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(別途協議)
本約款に定める事項に疑義が生じた場合、もしくは本約款に定めのない事項が生じた場合は、本契約当事者間にて誠意をもってこれを協
議の上、解決するものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);
$pdf->SetFont( KOZMIN,'B' , 8 );

$pdf->Text( 15 , 151  , mb_convert_encoding( "第12条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 148.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(個人情報の取り扱いについて）
本約款に基づき取得した個人情報は、本サービスを提供するために利用し、お客様本人の承諾なく第三者に開示、提供を行わないことと
します。
2． 当社は、個人情報の保護に関する法律、関係各庁が定めるガイドラインならびに各種プライバシーに関する法令を遵守するものとし
　ます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 170  , mb_convert_encoding( "第13条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 167.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(約款の改訂）
当社は、お客様の承諾を得ることなく、本約款を変更することができるものとし、当社およびお客様は、変更後の本約款に拘束されるも
のとします。なお、変更後の約款に承諾できない場合は、第7条に基づき、解約手続きを行うものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 182  , mb_convert_encoding( "第14条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 179.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(管轄裁判所）
本約款に起因した紛争の解決については、東京地方裁判所を第一審の専属的管轄裁判所とします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 166 , 202  , mb_convert_encoding( "2017年7月3日　改訂", "SJIS", "UTF-8" ) );


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "7/15" );


//8ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 80 , 15  , mb_convert_encoding( "KIREIMOのご案内", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 22  , mb_convert_encoding( "1.月額プランについて", "SJIS", "UTF-8" ) );

$pdf->SetFillColor(256, 256, 0);
$pdf->Rect( 19 ,24 ,56 ,4, 'F');
$pdf->Rect( 19 ,39 ,180 ,4, 'F');
$pdf->Rect( 19 ,44 ,58 ,4, 'F');
$pdf->Rect( 93 ,59 ,102 ,4, 'F');
$pdf->Rect( 19 ,64 ,36 ,4, 'F');
//$pdf->SetFillColor(255, 105, 180);// 赤ライン
$pdf->Rect( 19 ,104 ,173 ,4, 'F');
$pdf->Rect( 19 ,110 ,57 ,4, 'F');
$pdf->Rect( 77 ,124 ,122 ,4, 'F');
$pdf->Rect( 19 ,129 ,45 ,4, 'F');

$pdf->SetXY( 17, 24 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "・月額プランは2ヶ月に1度お手入れをさせていただきます。なお、毎回施術後に次回の予約をしていただきます。
・初回の施術は、契約月の翌月より提供を開始いたしますので、契約月の翌月～翌々月末までの間のご都合の良い
	日をご予約下さい。
・ご予約時間に遅刻された場合は出来る限りお手入れをさせて頂きますが、出来なかった箇所については消化扱い
	となりますのでご了承下さいませ。
・予約日は、当社の定める2ヶ月の期間内に指定していただき、予約日の3日前の20時まで(マイページからは
	23時59分まで)にご連絡いただければ、当該期間内であれば、何度でも予約の変更が可能です。
・シェービングサービスは行っておりません。 お客様自身で手の届きにくい、背中、うなじ、Oライン、ヒップ
	のみ補助を行います。
	その他の箇所はご予約の1～2日前にお客様自身でシェービングをしていただくようお願いいたします。
	※剃り残しがあった部位は当日お手入れをお断りさせて頂きますのでご了承下さい。
【お支払いについて】
・月額プランは本契約日に1回目分のご料金(2ヶ月分)を現金またはクレジットカードでお支払い頂き、併せて引
	き落としの手続きをさせて頂きます。
・2回目以降の料金は毎月、クレジットカード決済もしくは銀行引き落としとさせて頂きます。
・万が一3ヶ月連続でお支払いが確認できない場合には、お客様の意思を確認することなく退会(解約)手続きを
	させていただく場合がございます。
【退会（解約）手続きについて】
・退会（解約）を希望される場合は、契約終了月の2ヶ月前の末日までにKIREIMOコールセンター(0120-444-680)
	へお電話でご連絡下さい。退会(解約)の手続きをご案内させていただきます。
	期限を過ぎてのご連絡の場合、希望期間に退会(解約)が出来ない可能性がございますのでご注意ください。
・月額プランを1度退会(解約)された場合、1回に限り再契約が可能です。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFillColor(256, 256, 0);
$pdf->Rect( 19 ,161 ,180 ,4, 'F');
$pdf->Rect( 19 ,167 ,58 ,4, 'F');
$pdf->Rect( 19 ,173 ,180 ,4, 'F');
$pdf->Rect( 19 ,179 ,62 ,4, 'F');
$pdf->Rect( 114,185 ,84 ,4, 'F');
$pdf->Rect( 19 ,191 ,126 ,4, 'F');
$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 152  , mb_convert_encoding( "2. パックプラン・SP（スペシャル）プランについて", "SJIS", "UTF-8" ) );
$pdf->SetXY( 17, 154 );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "・1度のご来店で全身の施術を行っておりますので、次回は60～90日以上期間を空けて施術を行います。
・ご予約時間に遅刻された場合は出来る限りお手入れをさせて頂きますが、出来なかった箇所については消化扱い
	となりますのでご了承下さいませ。
・予約日の前日20時以降（マイページからは前日24時以降）のキャンセル、無断キャンセルの場合はいかなる場合でも1回分を消化させていただきます。
・シェービングのサービスは基本的に行っておりませんが、お客様自身で手の届きにくい 背中、うなじ、Oライン
	、ヒップについてはこちらでシェービングのお手伝いをさせていただきます。
	その他の箇所はご予約の1～2日前にお客様自身でシェービングを行っていただくようお願いいたします。 
	※剃り残しのある箇所は当日お手入れをお断りさせていただきますのでご了承下さい。 
", "SJIS", "UTF-8" ) , 0, 'L', 0);
$pdf->SetFillColor(256, 256, 0);
$pdf->Rect( 19 ,218 ,112 ,4, 'F');
//$pdf->SetFillColor(255, 105, 180);// 赤ライン
$pdf->Rect( 19 ,228 ,157 ,4, 'F');
$pdf->Rect( 19 ,233 ,184 ,4, 'F');
$pdf->Rect( 19 ,239 ,78 ,4, 'F');
$pdf->SetXY( 17, 208 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "
【お支払いについて】
・契約日に契約代金のうち、一部を手付金としてお支払いいただきます。残金のお支払いは契約日から30日以内にご入金をお願いいたします。(初回の施術はお支払い後となります。)
	※30日以内に残金をお支払頂けない場合は、お電話にてご連絡させていただく場合がございます。
	※契約日から90日以内に残金をお支払頂けない場合は、契約の意思がなく、また、手付金を放棄したものとして
		契約を解除させて頂きますのでご注意下さい。
・代金の支払い方法は、現金一括払い、クレジットカード一括払いとなります。また、当社指定のローン会社とロー
	ン契約をしていただくことにより、お支払いいただくことも可能です。
【解約について】
・解約をご希望の場合は、契約期間内に、必ずKIREIMOコールセンター（0120-444-680）へお電話でご連絡ください。解約方法をご案内させていただきます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetXY( 17, 243 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "8/15" );


//9ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
// マーカ(2.パックプランについて 以降)
$pdf->SetFillColor(256, 256, 0);
$pdf->Rect( 19 ,27 ,166 ,4, 'F');
//$pdf->SetFillColor(255, 105, 180);// 赤ライン
$pdf->Rect( 110 ,32 ,76 ,4, 'F');
$pdf->Rect( 19 ,37 ,10 ,4, 'F');
$pdf->SetFillColor(256, 256, 0);
$pdf->Rect( 101 ,47 ,26 ,4, 'F');
$pdf->Rect( 19 ,66 ,177 ,4, 'F');
$pdf->Rect( 19 ,72 ,12 ,4, 'F');
$pdf->Rect( 19 ,90 ,131 ,4, 'F');
$pdf->Rect( 19 ,96 ,96 ,4, 'F');
//$pdf->SetFillColor(255, 105, 180);// 赤ライン
$pdf->Rect( 19 ,102 ,158 ,4, 'F');
//$pdf->SetFillColor(256, 256, 0);
//$pdf->Rect( 19 ,140 ,112 ,4, 'F');
$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 25  , mb_convert_encoding( "3.会員ページについて", "SJIS", "UTF-8" ) );

$pdf->SetXY( 17, 27 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "・ご契約のお客様にはWEBからご予約を頂ける会員様専用ページ（マイページ）をご用意しております。
	WEBからは24時間でご予約が可能でございます。また、予約日前日の24時以降の予約キャンセルは1回
	消化とさせていただきます。
・ご予約の確認の為、メールをお送りいたしますのでinfo@kireimo.jpからのメールを受け取れるようアドレスの
	登録をお願いいたします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 64  , mb_convert_encoding( "4.施術に関して (補足)", "SJIS", "UTF-8" ) );

$pdf->SetXY( 17, 65 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "・親権者様の同意書が無い未成年のお客様は、お手入れをお断りさせていただいておりますので、ご了承下さい
	ませ。
・ご予約当日、台風・大雪・地震など天変地異や著しい公共交通機関の遅延など特別な事情がある場合のキャン
	セルについては、対応を考慮させていただきます。
※公共交通機関の遅延による遅刻の場合には、遅延証を忘れずにお持ちください。
	お忘れの場合には通常の遅刻扱いとさせていただきます。
・生理中のお手入れにつきましては、衛生上デリケート部位、ヒップの施術はお断りしております。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 125  , mb_convert_encoding( "5.副反応について", "SJIS", "UTF-8" ) );

$pdf->SetXY( 17, 127 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "・施術後お肌に副反応（赤み、かゆみ、ヒリつき等）が出る可能性がございます。
	清潔な濡れタオルなどで冷やしていただきますようお願いいたします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 155  , mb_convert_encoding( "6.その他", "SJIS", "UTF-8" ) );

$pdf->SetXY( 17, 157 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "・お客様の私物はトリートメントルームのロッカーに入れ、ご自身で鍵の管理を行って下さい。
・紛失等の事故がおきましても、当社では責任を負いかねます。
・店舗にお忘れ物をされた場合、店舗内で一定期間保管後、警察に届け出いたしますので、一定期間内に取りに
	来られない場合は、警察までお問い合わせください。
その他ご不明点やご質問の際はKIREIMOコールセンター（0120-444-680）までご連絡下さいませ。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 180 );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 15 , 205  , mb_convert_encoding( "【月額プランの契約期間について】", "SJIS", "UTF-8" ) );
$pdf->SetDrawColor(0,0,0);
$pdf->Rect(15 ,222 ,0 ,5); // 左から数えて縦線1
$pdf->Rect(40 ,218 ,70 ,7);// 左から数えて上の囲み1
$pdf->Rect(40 ,222 ,0 ,5); // 左から数えて縦線2
$pdf->Rect(110 ,218 ,70 ,7);// 左から数えて上の囲み2
$pdf->Rect(110 ,222 ,0 ,5); // 左から数えて縦線3
$pdf->Rect(180 ,218 ,20 ,0);// 左から数えて上の囲み3
$pdf->Rect(180 ,222 ,0 ,5); // 左から数えて縦線4
$pdf->Rect(15 ,225 ,185 ,0); //基準となる横線
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 70 , 217  , mb_convert_encoding( "2ヶ月", "SJIS", "UTF-8" ) );
$pdf->Text( 140 , 217  , mb_convert_encoding( "2ヶ月", "SJIS", "UTF-8" ) );
$pdf->Text( 130 , 223  , mb_convert_encoding( "※契約期間更新", "SJIS", "UTF-8" ) );
$pdf->Text( 13 , 231  , mb_convert_encoding( "契約日", "SJIS", "UTF-8" ) );
$pdf->Text( 33 , 217  , mb_convert_encoding( "翌月1日", "SJIS", "UTF-8" ) );
$pdf->Text( 103 , 231  , mb_convert_encoding( "1.契約終了日", "SJIS", "UTF-8" ) );
$pdf->Text( 173 , 231  , mb_convert_encoding( "2.契約終了日", "SJIS", "UTF-8" ) );
$pdf->Text( 15 , 245  , mb_convert_encoding( "「2.契約終了日」で更新する場合、「1.契約終了日」までにお申し出ください。", "SJIS", "UTF-8" ) );


$pdf->SetXY( 17, 239 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "9/15" );


//10ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 73 , 24  , mb_convert_encoding( "個人情報のお取扱いについて", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 35  , mb_convert_encoding( "1.個人情報保護方針", "SJIS", "UTF-8" ) );
$pdf->SetXY( 17, 38 );
$pdf->SetFont( KOZMIN,'' , 10 );
// $pdf->MultiCell(180, 6, mb_convert_encoding( "株式会社".($shop['id']==6 && $contract['contract_date']<'2015-01-04' ? "CKR" : "ヴィエリス")."（以下「当社」といいます）は、お客様に安全で快適なエステティックサービスを提供するにあたり「個人情報の保護に関する法律」他、関係諸法令並びに本方針と当社の各規程を遵守し、お客様の個人情報保護の徹底に努めます。
$pdf->MultiCell(180, 6, mb_convert_encoding( "株式会社ヴィエリス（以下「当社」といいます）は、お客様に安全で快適なエステティックサービスを提供するにあたり「個人情報の保護に関する法律」他、関係諸法令並びに本方針と当社の各規程を遵守し、お客様の個人情報保護の徹底に努めます。
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
$pdf->Text( 100 , 285  , "10/15" );


//11ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
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
// ($shop['id']==6 && $contract['contract_date']<'2015-01-04' ? "会社名：株式会社CKR
// 代表者：代表取締役　大澤　美加
// 本　社：東京都渋谷区広尾5-25-5　広尾アネックスビル7F " : "会社名：株式会社ヴィエリス
// 代表者：代表取締役　".$kireimo_ceo."
// 本　社：".$company_address)
"会社名：株式会社ヴィエリス
代表者：代表取締役　".$kireimo_ceo."
本　社：". $company_address
."
KIREIMOコールセンター：電話　".$shop_tel."
※ 受付時間は11：00～20：00（年末年始を除く）とさせて頂いております。

私は、貴社の個人情報のお取扱いについて（以下、「本件規程」といいます。）を理解した上で、以下の事項に同意します。
(1)　貴社が明示する目的の範囲内で、貴社が私の個人情報を取得し利用すること
(2)　本件規程の範囲内で、私の個人情報が第三者へ開示される場合があること
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 20 , 205  , mb_convert_encoding( substr($contract['contract_date'], 0,4)." 年 ".substr($contract['contract_date'], 5,2)." 月 ".substr($contract['contract_date'], 8,2)." 日", "SJIS", "UTF-8" ) );


$pdf->SetDrawColor(0,0,0);
$pdf->Rect(95 ,210 ,100 ,15);

$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 227  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "11/15" );


//12ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 70 , 10  , mb_convert_encoding( "除毛・減毛トリートメント同意書", "SJIS", "UTF-8" ) );

$pdf->SetXY( 12, 13 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "当サロンで行うトリートメントはＩＰＬを用いた機器を使用し施術を行います。
トリートメントを安心してお受けいただくため、下記内容についてご確認・ご承諾をお願いいたします。
ご不明な点はスタッフにご質問ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 12, 31 );
$pdf->SetFont( KOZMIN,'U' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "下記の内容は禁忌とされている状態、または箇所になります。原則として施術を行うことができませんので、ご了承下さい。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 12, 31 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(100, 6, mb_convert_encoding( "
・現在治療中または持病等をお持ちの方
・ガン、てんかん等の既往歴がある方
・妊娠中、授乳中、または妊娠の可能性がある方
・感染症もしくは、感染症の疑いがある方
・光アレルギー、紫外線アレルギー、光線過敏症の方
・ケロイドになりやすい方
・過度な敏感肌の方
・飲酒後の方や、飲酒のご予定のある方
   (お手入れの前後12時間はお控えください)
・粘膜部位
・白髪部位
", "SJIS", "UTF-8" ) , 0, 'L', 0);
$pdf->SetXY( 90, 31 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(130, 6, mb_convert_encoding( "
・白斑症もしくは、白斑症の疑いがある方
・皮膚トラブルで通院中の方や、皮膚疾患部位（傷、湿疹、腫れ物、アザ等）
・過度の日焼けをしている方、日焼けをされるご予定がある方
・ペースメーカーなどの医療用機器を使用されている方
・体調がすぐれない方（生理中含む）
・ほくろ､アートメイクをされている部位､タトゥー及び､刺青をされている部位
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 12, 116 );
$pdf->SetFont( KOZMIN,'U' , 10 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "施術後は、下記内容の副反応が起こる場合があります。", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFillColor(256, 256, 0);
$pdf->Rect( 16 ,123 ,76 ,4, 'F');
$pdf->Rect( 16 ,133 ,96 ,4, 'F');
$pdf->Rect( 16 ,148 ,115 ,4, 'F');
$pdf->SetXY( 12, 118 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(190, 5, mb_convert_encoding( "
・施術後一時的に毛穴の赤みが起こる場合があります。患部を清潔な冷タオルで冷やし、掻いたり、こすらないようご注意ください。
　 なお、お客様の肌の状態が施術に起因するものか判断が困難な場合、当社にて責任を負いかねます。
・施術部位またはその近くの皮膚が過敏になる場合があります。皮膚に傷をつけたり、こすったりしないようご注意ください。
・過度な日焼け、乾燥は火傷の可能性が高く施術をお断りすることがございます。十分に保湿を行って頂きますようお願い致します。
　 火傷によるかさぶたが出来た際は全治まで2週間程度かかる場合もございます。
・施術後1ヶ月以内に過度な日光を浴びた場合、施術部位に色素沈着を残すことがあります。日焼け対策をお願い致します。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 12, 159 );
$pdf->SetFont( KOZMIN,'U' , 10 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "下記内容は、トリートメント期間中の注意事項になります。", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFillColor(256, 256, 0);
$pdf->Rect( 16 ,166 ,110 ,4, 'F');
$pdf->Rect( 16 ,171 ,122 ,4, 'F');
$pdf->Rect( 16 ,191 ,145 ,4, 'F');
$pdf->Rect( 16 ,211 ,52 ,4, 'F');
$pdf->Rect( 16 ,221 ,163 ,4, 'F');
$pdf->SetXY( 12, 160 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(182, 5, mb_convert_encoding( "
・トリートメントの1～2日前にトリートメント部位の剃毛を行ってください。
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
・トリートメント期間中、お薬の服用や通院が必要となった場合、必ずご申告ください。医師の同意が無ければトリートメントが出来ない場合がございます。
・万が一、施術部位に異常が生じ、その原因がトリートメントに起因する可能性が考えられる場合、トリートメント日を含む3日以内にご連絡ください。
    ご連絡がない場合、トリートメントに起因するものか判断が困難なため責任を負いかねる場合がございます。
上記内容について、ご理解・ご承諾いただきましたら、誠に恐れいりますが、ご署名お願い致します。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 20 , 270  , mb_convert_encoding( substr($contract['contract_date'], 0,4)." 年 ".substr($contract['contract_date'], 5,2)." 月 ".substr($contract['contract_date'], 8,2)." 日", "SJIS", "UTF-8" ) );

$pdf->SetDrawColor(0,0,0);
$pdf->Rect(95 ,268 ,100 ,15);

$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 285  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 290  , "12/15" );


//13ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 42 , 15  , mb_convert_encoding( "エステティックサービス契約　ご契約内容チェックシート", "SJIS", "UTF-8" ) );

$pdf->SetXY( 17, 20 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "この｢ご契約内容チェックシート｣は、ご契約が、ご契約者様のご希望に沿った内容になっていること、お引き受けするご契約
の内容が適切であることをご契約者様に確認させていただくためのものです。
ご契約のプランの種類にしたがい、以下の該当箇所につきまして、ご確認の上、チェックを入れてください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 36 );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 17 , 42  , mb_convert_encoding( "<月額プランをお申込みのご契約者様>", "SJIS", "UTF-8" ) );

$pdf->SetFillColor(256, 256, 0);
$pdf->Rect( 43 ,44 ,16 ,4, 'F');
$pdf->Rect( 69 ,65 ,129 ,4, 'F');
$pdf->Rect( 19 ,72 ,29 ,4, 'F');
$pdf->Rect( 61, 114 ,40 ,4, 'F');
$pdf->Rect( 110,128 ,80 ,4, 'F');
$pdf->Rect( 36 ,150 ,119 ,4, 'F');
$pdf->Rect( 61 ,170 ,40 ,4, 'F');
$pdf->Rect( 75 ,177 ,42 ,4, 'F');
$pdf->Rect( 110,191 ,80 ,4, 'F');
$pdf->SetXY( 17, 36 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 7, mb_convert_encoding( "
□　本プランは、2ヶ月に1回店舗に通って施術をして頂くプランです。
□　契約後1回目の施術は契約月の翌月からの提供となりますので、契約月の翌月～翌々月末までのご都合の良い日をご予約のうえ、ご来店ください。それ以降は2ヶ月に1回の施術を受けるものとさせて頂きます。
□　本プランにおいて当社が定めた2ヶ月の期間内にご来店されなかった場合には、当該期間分の施術は行われたものとして扱
	わせて頂きます。
□　施術後に毎回次回の予約を取得して頂きます。なお、予約日は当社の定める2ヶ月の期間内に指定していただき、予約日の
	3日前の20時まで（マイページからは23時59分まで）にKIREIMOコールセンター(0120-444-680)にご連絡頂ければ、当該
	期間内であれば何度でも変更は可能です。
□　施術2回目以降のお支払い方法は、原則銀行引落し又はクレジット決済のいずれかのみとなります。
	※施術開始月により、上記方法と異なる場合がございます。詳しくは店舗スタッフよりご説明させていただきます。
□　クーリング・オフ期間は、ご契約日から8日以内です。
□　解約をご希望される場合は、契約期間終了月の2ヶ月前の末日までにご連絡ください。
□　キャンペーンはお1人様1回までのご利用とさせて頂きます。プラン変更の際のキャンペーンの適用は致し兼ねます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 17 , 140  , mb_convert_encoding( "<全身脱毛パックプランをお申し込みのご契約者様>", "SJIS", "UTF-8" ) );

$pdf->SetXY( 17, 134 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 7, mb_convert_encoding( "
□　本プランは、契約書に定めた回数を契約期間内に通っていただき施術をしていただくプランです。
□　ご予約の当日のキャンセルに関しては、当該1回分の施術が行われたものとして扱われます。
	前日20時（マイページからは23時59分）までにKIREIMOコールセンター(0120-444-680)へご連絡頂ければ、
	予約変更が可能です。
□　クーリング・オフ期間は、ご契約日から8日以内です。
□　中途解約(クーリング・オフ期間外・契約日から9日目以後の解約)は解約手数料を頂戴します。
　　残りの施術回数分の金額の10％(上限2万円)を解約手数料とさせて頂いております。
□　キャンペーンはお1人様1回までのご利用とさせて頂きます。プラン変更の際のキャンペーンの適用は致し兼ねます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 17 , 207  , mb_convert_encoding( "<ローンをお申込みのご契約者様>", "SJIS", "UTF-8" ) );

//$pdf->SetFillColor(256, 256, 0);
//$pdf->Rect( 23 ,210 ,52 ,4, 'F');
//$pdf->Rect( 23 ,217 ,174 ,4, 'F');
$pdf->SetXY( 17, 201 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 7, mb_convert_encoding( "
□　ローンはローン会社の審査により、契約ができない場合がございます。予めご了承ください。
□　ローンをお申込みの場合、別途分割手数料を頂戴します。
□　ローンをお申込みの場合、支払い方法、回数の変更は致しかねます。
□　ご指定頂いた日時にお申込のローン会社から契約内容、ご本人確認のお電話がございますのでご対応お願い致します。
□　ローンを中途解約される場合、ローンキャンセル手数料はお客様のご負担となります。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 18 , 268  , mb_convert_encoding( substr($contract['contract_date'], 0,4)." 年 ".substr($contract['contract_date'], 5,2)." 月 ".substr($contract['contract_date'], 8,2)." 日", "SJIS", "UTF-8" ) );
$pdf->Text( 18 , 273  , mb_convert_encoding( "上記内容を確認しました。", "SJIS", "UTF-8" ) );

$pdf->SetDrawColor(0,0,0);
$pdf->Rect(95 ,260 ,100 ,15);
$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 277  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "13/15" );


//14ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 60 , 30  , mb_convert_encoding( "SP（スペシャル）プランに関する同意書", "SJIS", "UTF-8" ) );

$pdf->SetXY( 17, 40 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "本書は、SP（スペシャル）プラン（以下「本プラン」という）に関する諸注意事項等を明記したものになります。
本書は、概要書面およびエステティックサービス利用約款に付随し、一体となって契約内容となります。以下を確認のうえ、本プランに同意ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 56 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(181, 7, mb_convert_encoding( "
□　本プランの契約期間は、「エステティックサービス契約書」記載の契約期間とし、期間満了をもって終了となります。
       本プランは、契約期間終了後も期間・回数とも無制限で、無償にて役務提供いたします。但し、当該役務提供を受
       けることができる対象者は、現金一括払い、クレジットカード一括払いもしくは当社と提携するローン会社の立替払い
       のいずれかの支払が完了しており、かつ、概要書面およびエステティックサービス契約書に定める契約期間中に指定す
       る回数の本サービスの提供を受けたお客様のみになります。
□　本プランは、現金一括払い、クレジットカード一括払いもしくは当社と提携するローン会社の立替払いのいずれかに
       なります。
□　契約期間満了後の役務提供は、契約クレジット、ローン会社の対象期間とはなりませんので、仮に役務提供が受けられ
       なくても、クレジット契約、ローン契約に基づく支払い停止の抗弁や既払金の返金原因とはなりません。
       （お支払いの方法としてクレジット、ローンをご契約のお客様のみ対象となります。）
□　本プランは契約期間中の中途解約は可能になります。なお、中途解約における解約手数料等は以下のとおりとなります。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 143 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 7, mb_convert_encoding( "
              残              金　 =　支払総額金 - （1回あたりの料金 × 利用回数）
              解約手数料金額 =　残金×10％（最大￥20,000）
              精      算      金   =　残金 - 解約手数料金額
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 170 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 7, mb_convert_encoding( "
□　本プランは、契約期間が過ぎた場合、契約書に定める役務の指定回数が未消化であっても、返金の対象外となります。
□　契約期間内までは最短2ヶ月に1回マイページにて予約、契約期間終了後の来店の場合は3ヶ月に1回電話予約とさせて
       いただきます。
       なお、契約期間終了後の来店予約は90日空けて予約いただきます。
□　繁忙期等については、予約が立て込み予約がとりにくくなる場合がございますので、予めご了承ください。
□　契約期間終了後以降はパーツでの施術が可能です。
       契約期間内まではパーツ施術の場合、全身1回分消化となりますので、全身の施術でのご来店をお勧めいたします。
□　本書に記載なき事項は、エステティックサービス利用約款に準拠いたします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

//$pdf->SetXY( 17, 239 );
//$pdf->SetFont( KOZMIN,'' , 9 );
//$pdf->MultiCell(182, 7, mb_convert_encoding( "
//□　初回の施術はご契約の店舗にご来店くださいますようお願いいたします。
//□　初回の施術時までに口座情報と銀行お届け印をご持参いただけなかった場合、当日の施術が出来かねますのでご了承ください。
//", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "14/15" );


//15ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 17 , 24  , mb_convert_encoding( "<ローンをお申込みのご契約者様>", "SJIS", "UTF-8" ) );

$pdf->SetXY( 17, 20 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(181, 7, mb_convert_encoding( "
□　ローンはローン会社の審査により、契約ができない場合がございます。予めご了承ください。
□　ローンをお申込みの場合、別途分割手数料を頂戴します。
□　ローンをお申込みの場合、支払い方法、回数の変更は致しかねます。
□　ご指定頂いた日時にお申込みのローン会社から契約内容、ご本人確認の電話がございますのでご対応お願いいたします。
□　ローンを中途解約される場合、ローンキャンセル手数料はお客様のご負担となります。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 18 , 70  , mb_convert_encoding( "以上、同意のうえ、私はSP（スペシャル）プランに申込みいたします。", "SJIS", "UTF-8" ) );
$pdf->Text( 18 , 75  , mb_convert_encoding( substr($contract['contract_date'], 0,4)." 年 ".substr($contract['contract_date'], 5,2)." 月 ".substr($contract['contract_date'], 8,2)." 日", "SJIS", "UTF-8" ) );

$pdf->SetDrawColor(0,0,0);
$pdf->Rect(95 ,85 ,100 ,15);
$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 102  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "15/15" );


//$pdf->Output();
$pdf->Output($customer_name.$customer['no'].".pdf","I");
?>