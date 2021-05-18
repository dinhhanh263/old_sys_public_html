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
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");

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
$pdf->Cell(45, 10, mb_convert_encoding( number_format($contract['fixed_price'] - $contract['discount'])."円", "SJIS", "UTF-8" ), 1,0,"R");


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "1/12" );


//2ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 16 , 20  , mb_convert_encoding( "■備考", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 22);
$pdf->MultiCell(180, 60, mb_convert_encoding( $contract['memo'], "SJIS", "UTF-8" ), 1,"T");

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
$pdf->Text( 17 , 180  , mb_convert_encoding( "4.契約の解除に関する事項：クーリングオフ並びに中途解約につきましては、概要書面の該当欄をご確認ください。", "SJIS", "UTF-8" ) );

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
$pdf->Text( 100 , 285  , "2/12" );


//3ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'' , 12 );
$pdf->SetTextColor(255, 0, 0);
$pdf->Text( 17 , 7  , mb_convert_encoding( "<クーリングオフについて>", "SJIS", "UTF-8" ) );

$pdf->SetDrawColor(255,0,0);
$pdf->Rect(15 ,9 ,180 ,49);

$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Text( 17 , 12  , mb_convert_encoding( "■法定継続的役務提供受領者（エステティック契約者）は、締結した契約書面を受領した日から起算して8日以内であれば、書面により、", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 17  , mb_convert_encoding( "関連商品を含めその契約を解除（クーリングオフ）できます。但し、エステティック契約者が、クーリングオフに関し当社から不実のことを", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 22  , mb_convert_encoding( "告げられ誤認し又は威迫により困惑しクーリングオフを行わなかった場合には、当該期間経過後も書面によりその契約をクーリングオフする", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 27  , mb_convert_encoding( "ことができます。但し、関連商品において、その全部若しくは一部を消費（開封若しくは使用）したときは、その対象ではございませんが、", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 32  , mb_convert_encoding( "当社がお客様に商品を使用させ、また消費させた場合はこの限りではありません。", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 37  , mb_convert_encoding( "■クーリングオフは、書面を当社宛に発信したときにその効力が生じます。", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 42  , mb_convert_encoding( "■クーリングオフに伴う損害賠償、違約金、本契約役務ご利用代金の支払い請求はいたしません。", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 47  , mb_convert_encoding( "■（クーリングオフ対象）関連商品の引渡しが既にされているとき、その返還に要する費用は、当社が負担します。", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 52  , mb_convert_encoding( "当社が既に受領した代金は（クーリングオフ対象外の関連商品代金は除く）速やかにエステティック契約者が指定した口座へ振込みで", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 57  , mb_convert_encoding( "返還致します。（振込み手数料は、当社が負担致します。）", "SJIS", "UTF-8" ) );

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 67  , mb_convert_encoding( "< 中 途 解 約 >", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 68 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 4, mb_convert_encoding( "クーリングオフ期間を過ぎた場合でも契約を解除できます。但し関連商品のみの解約はできません。
解約時の返金額等の算出方法としては、コース・プラン1回あたりの料金である補正単価（契約金額を契約回数で除した結果が割り切れない場合1円未満を四捨五入）に利用回数をかけた金額を消化額とし、支払総額から消化額を引いた金額を残金
とします。途中解約手数料金額として残金の10%（￥20,000以内）を差し引かせて頂きます。
通いホーダイプランについては、各コースで定められている単位で計算するものとします。

    解約手数料金額  = ｛ 支払総額  -  ( 1回あたりの料金   ×   利用回数 )  ｝×10% （￥20,000以内）
    清算金  =  残金  -  解約手数料金額

お支払いの方法としてローンをご契約のお客様につきましては、中途解約の場合、上記解約手数料金額に加えて、契約ローン
会社からのローンキャンセル手数料を別途頂戴致します。

    解約手数料金額  = ｛ 支払総額  -  ( 1回あたりの料金   ×   利用回数 )  ｝×10% （￥20,000以内）
    清算金  =  残金  -  解約手数料金額   -  ローンキャンセル手数料
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetTextColor(255, 0, 0);
$pdf->SetXY( 19, 124 );
$pdf->SetFont( KOZMIN,'' , 8.5 );
$pdf->MultiCell(180, 4, mb_convert_encoding( "※ローンキャンセル手数料の算出方法につきましては、ローン会社の規定に従い算出させていただきます。
※複数箇所でコース1回分とみなす契約（全身パック、月額コース、キャンペーン時におけるセットのコース）につきましてはコースに含まれる箇所を1箇所でもお手入れした場合、コース1回分を消化したとみなし、解約時の返金対象とはなりません。
（例）全身コース6回契約時、ヒザ下1回のみお手入れ後、解約を希望した場合の返金対象は、全身5回分となります。
※現金の受け渡しによる返金は行っておりません。金融機関への振込とさせて頂きます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY( 15, 146 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "以下のいずれかに該当する場合は、当社より契約の解除をさせていただく場合がございます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 19, 151 );
$pdf->SetFont( KOZMIN,'' , 8.5 );
$pdf->MultiCell(174, 4, mb_convert_encoding( "※契約金の金額が、契約日より起算して90日以内にお支払いいただけない場合。
※お客様の体質等に起因して、お手入れの継続が困難だと当社が判断した時。
※お客様との信頼関係の維持が困難と判断した時。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 170  , mb_convert_encoding( "< システム補足 >", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 171 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(190, 4, mb_convert_encoding( "●未成年のお客様については、親権者の同意が必要となります。同意書には、親権者の続柄・ご連絡先の電話番号
   のご記入と署名・捺印が必要です。また、追ってサロンから親権者の方へ確認の連絡をさせて頂くことがございます。
   (同意書の提出がない場合は契約無効とさせていただきます）
●ご契約に関しては、お支払い代金の一部(1回当たりの料金以上の金額)を手付金としてお支払いいただくことで、
   当日の契約が可能です。ただし、契約日から90日以内に残りの代金をお支払いいただけない場合は、サービス
   を提供できないことと手付金を放棄したものとして、契約を解除させていただきますのでご注意下さい。
   なお、契約日から30日以内に残りの代金をお支払いいただいていない場合は、契約解除手続きについての
   お知らせをお送りすることがございます。
●お手入れの間隔は1ヶ月半以上空けて下さい。
●期間内に回数消化できない場合、契約日から保証期間内であれば残回数分の施術が可能です。
   但し、契約期間を過ぎての解約はできませんのでご注意ください。
●各種特典のご利用については、ご契約いただいた回数の最後に適用可能です。有料の特典については未使用の
   場合のみ、特典分として受領した金額が返金対象となります。また無料の特典については返金対象外です。
●施術の効果には個人差がございます。本サービスは特定の効果を保証するものではございません。
   また、コース代金は施術に対するものであり、特定の効果に対するものではございません。
●当サロンご利用中の損害や怪我、その他の事故について、当サロンに故意または過失がない場合、その損害に
   対する一切の責任を負いません。
●通いホーダイプランの契約期間については返金の保証期間とさせていただいております。
●エステティック契約書記載の役務提供期間を過ぎて残回数が残っている場合は返金の対象外になります。
   やむを得ない事情により、有効期間を延長した場合(弊社指定の手続きが必要です。)も返金の対象外となります。
   なお、通いホーダイプランも同様、契約期間(保証期間)を過ぎて保証回数が残っている場合についても返金の対象外と
   なります。
", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "3/12" );


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
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");

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
$pdf->Cell(45, 10, mb_convert_encoding( number_format($contract['fixed_price'] - $contract['discount'])." 円", "SJIS", "UTF-8" ) , 1,0,"R");


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "4/12" );


//5ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 16 , 20  , mb_convert_encoding( "■備考", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 22);
$pdf->MultiCell(180, 60, mb_convert_encoding( $contract['memo'], "SJIS", "UTF-8" ), 1,"T");

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
$pdf->Cell(20, 5, mb_convert_encoding( number_format($contract['payment_cash']+$contract['payment_card']+$contract['payment_transfer']+$contract['payment_loan']+$contract['payment_coupon']+$contract['balance']), "SJIS", "UTF-8" ), 1,0,"R");
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
$pdf->Image("../../img/stamp.png",155,200,24); //横幅のみ指定,24

$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Text( 103 , 208  , mb_convert_encoding( "(乙)会  社  名", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 122 , 208  , mb_convert_encoding( "株式会社　ヴィエリス", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Text( 107 , 212  , mb_convert_encoding( "所  在  地", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 212  , mb_convert_encoding( $company_zip_code, "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 215  , mb_convert_encoding( $company_address, "SJIS", "UTF-8" ) );

$pdf->Text( 107 , 219  , mb_convert_encoding( "電話番号", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 219  , mb_convert_encoding( "TEL: ".$company_tel_no."　FAX: ".$company_fax_no, "SJIS", "UTF-8" ) );

$pdf->Text( 107 , 223  , mb_convert_encoding( "代  表  者", "SJIS", "UTF-8" ) );
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
$pdf->Text( 100 , 285  , "5/12" );


//6ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 73 , 10  , mb_convert_encoding( "エステティックサービス約款", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 15  , mb_convert_encoding( "第1条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 12.8 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(契約の成立)
お客様(以下「甲」といいます)は、本契約書の記載内容および約款の各条項を承諾の上、本日当サロン(以下「乙」いいます)に対して
、エステティックサービス(以下「役務」といいます)にお申し込みを行い、乙はこれを承諾しました。
2. 甲が未成年の場合は、親権者の同意を必要としますので、「親権者同意書」等の書面で親権者の同意を乙が確認した上で、本契約の
    成立となります。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 40  , mb_convert_encoding( "第2条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 37.2 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(役務内容)
乙は甲に対し、本契約書に記載する月額コースまたはパックプランおよびその回数の役務を提供するものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 48  , mb_convert_encoding( "第3条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 45.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(役務の金額、支払方法、支払時期)
乙は、甲に提供する役務の対価、関連商品がある場合は、その代金その他甲が支払わなければならない金額を本契約に明記します。
2. 月額制コースにおいては、前月前払い制とし、その支払いをクレジットもしくは銀行口座振替を選択できるものとします。
    但し、いづれも金融機関の決済が取れなかった場合、その月末までにお支払いがない限り、翌月以降の予約が取り消しとなります。
3. 甲は、役務の支払い方法として、前払金の現金一括払いまたは乙と提携するクレジット会社の立替払い等の中から甲の希望
    する方法 を選択できるものとします。
4. 甲が前項の前払い一括払いを選択した場合、契約日にその全額を持ち合わせていない場合、甲は一時金(手付金)を納付
    するものとします。
5. 前項の場合、甲は契約日から起算して90日以内に前払金の残金のお支払いがない場合、乙は甲が前項の手付金を放棄
    したものとして、本契約が解約処理となる事に異議を述べないものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 82  , mb_convert_encoding( "第4条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 80.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(役務の提供期間)
役務の提供期間は、本契約書に記載された契約期間とします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetDrawColor(255,0,0);
$pdf->Rect(13 ,89 ,186 ,78);
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->SetTextColor(255, 0, 0);
$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 94  , mb_convert_encoding( "第5条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 91.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(180, 3.2, mb_convert_encoding( "(クーリング・オフ)
甲は、契約書面を受領した日から起算して8日間以内であれば、書面により契約を解除することができます。
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

$pdf->SetXY( 27, 131 );
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
// ($shop['id']==6 && $contract['contract_date']<'2015-01-04' ? "株式会社CKR　代表者　大澤　美加" : "株式会社ヴィエリス　代表者　".$kireimo_ceo)."殿
"株式会社ヴィエリス　代表者　".$kireimo_ceo."殿

", "SJIS", "UTF-8" ) , 1, 'L', 0);


$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 174  , mb_convert_encoding( "第6条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 171.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(中途解約)
甲は、クーリング・オフ期間を過ぎても、関連商品を含め契約の中途解約ができます。
2. 中途解約に関して、既にお支払いいただいている金額の内、未消化役務分の１０％（契約金額を契約回数で除した結果が割り切れない
    場合1円未満を四捨五入）を解約手数料金額としてお支払いい ただきます。（上限２万円）
3. 返金に関しては、未消化役務の金額より、前項の解約手数料金額と銀行送金手数料を差し引いた金額を返金いたします。
4.お支払いの方法としてローンをご契約のお客様につきましては、中途解約の場合、上記解約手数料金額に加えて、契約ローン会社からの
    ローンキャンセル手数料を別途頂戴致します。
5.ローンキャンセル手数料の算出方法につきましては、ローン会社の規定に従い算出させていただきます。
6. 関連商品の場合、解約手数料はかかりませんが、返送の費用および返金の振込手数料は、甲の負担とします。
    但し、乙に商品到着後の返金となります。
7. 但し、関連商品の場合、商品を開封したり、その全部もしくは一部を消費した時は、当該商品に限り中途解約することができません。
    また、未使用であっても、著しく商品価値が損なわれている場合は、返金対象外となる場合があります。
8. 役務の提供期間が過ぎた契約については、解約ができませんのでご注意ください。
9. クレジット等をご使用の場合の精算は、各クレジット会社の所定の方法によるものとします。また、甲は乙がクレジット会社の請求により
    精算上必要な範囲において、甲の利用回数をクレジット会社に通知することを承諾するものとします。
10. 月額コースの契約の場合、解約の申し出は、原則最終施術希望月の前月とさせていただきます。
    但し、金融機関の都合により、解約の申し出時点 でクレジット決済または銀行口座振替の中止ができない場合、乙はその金額を
    金融機関より受領後、すみやかに全額返金します。 (返金手数料は、甲の負担となります)
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 234  , mb_convert_encoding( "第7条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 231.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(施術上の注意)
乙は、甲に役務提供するにあたり、事前に甲の体質（治療中の皮膚疾患・アレルギー・敏感肌・薬の服用の有無など）および体調を聴取
し、確認するものとします。甲の体調・体質により、甲への役務提供をお断りする場合があります。
2. 役務提供期間中、甲が体調を崩したり、施術部位に異常が生じた場合、甲はその旨を乙に伝えるものとします。この場合、乙は直ちに
    役務を中止します。その原因が乙の施術に起因する疑いがある場合は、一旦乙の負担で、甲に医師の診断を受けて頂く等の適切な処置
    を取ることとし、甲乙協議の上解決するものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 255  , mb_convert_encoding( "第8条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 252.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(抗弁権の接続)
甲は、信販を利用して支払う場合、割賦販売法により、乙との間で生じている事由をもって、信販会社からの請求を拒否出来ます
    （これを抗弁権の接続といいます。）。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 267  , mb_convert_encoding( "第9条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 264.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(別途協議)
本契約書に定める事項に疑義が生じた場合は、甲乙協議の上解決するものとします。
2. 本契約書に定めのない事項については、民法その他の法令によるものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "6/12" );


//7ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 80 , 15  , mb_convert_encoding( "KIREIMOのご案内", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 22  , mb_convert_encoding( "1.月額制について", "SJIS", "UTF-8" ) );

$pdf->SetFillColor(256, 256, 0);
$pdf->Rect( 19 ,24 ,54 ,4, 'F');
$pdf->Rect( 19 ,34 ,92 ,4, 'F');
$pdf->Rect( 19 ,39 ,112 ,4, 'F');

$pdf->Rect( 93 ,54 ,102 ,4, 'F');
$pdf->Rect( 19 ,59 ,146 ,4, 'F');

$pdf->Rect( 19 ,89 ,83 ,4, 'F');
$pdf->Rect( 79 ,95 ,118 ,4, 'F');
$pdf->Rect( 19 ,99 ,30 ,4, 'F');

$pdf->SetFillColor(255, 105, 180);
$pdf->Rect( 19 ,114 ,168 ,4, 'F');
$pdf->Rect( 19 ,119 ,73 ,4, 'F');

$pdf->Rect( 63 ,134 ,132 ,4, 'F');
$pdf->Rect( 19 ,139 ,13 ,4, 'F');

$pdf->SetFillColor(256, 256, 0);
$pdf->Rect( 85 ,169 ,45 ,4, 'F');
$pdf->Rect( 19 ,186 ,150 ,4, 'F');
$pdf->Rect( 112 ,199 ,70 ,4, 'F');
$pdf->Rect( 19 ,204 ,139 ,4, 'F');

$pdf->SetXY( 17, 24 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "・月額制は毎月、月に1度お手入れをさせていただきます。
	1度お手入れのご予約をされますと、ご予約の変更はいたしかねます。ご注意くださいませ。
・通い方は1ヶ月目に下半身、2ヶ月目に上半身のお手入れをさせていただき変更は出来かねます。
・ご予約時間から20分過ぎた遅刻の場合は、お手入れをせず１回分消化とさせていただきます。 　
	20分以内にご来店頂いた場合は出来る限りお手入れをさせて頂きますが、出来なかった箇所に関しては
	消化扱いとさせていただきます。 
・シェービングサービスは行っておりません。 お客様自身で手の届きにくい、背中、うなじ、Oライン、ヒップ
	のみ補助を行い、シェービング補助代として1,000円（税込）を現金で頂戴しております。
	その他の箇所はご予約の1～2日前にお客様自身でシェービングをしていただくようお願いいたします。
	※剃り残しがあった部位は当日お手入れをお断りさせて頂きますのでご了承下さい。

【お支払いについて】
・月額制は新規入会時に2ヶ月分のご料金をお支払いただきます。
・3ヶ月目以降は継続手続きが必要となり、前払い制となります。クレジットカード決済もしくは銀行引き落とし
	のみのお支払とさせて頂きますので、1ヶ月目のお手入れの際にクレジットカードもしくはキャッシュカード
	をお持ち下さい。
	※店舗により使用できるカードが異なります。詳しくは店舗スタッフまでお問い合わせくださいませ。
・月額制はいかなる場合も払い戻し致しかねますので、ご了承下さいませ。
・万が一3ヶ月連続でお引落しが出来ない場合には自動退会となり、お客様の意思を確認することなく退会
	手続きをさせていただく場合がございます。

【退会手続きについて】
・退会を希望される場合は、最終施術希望月の前月までにKIREIMOコールセンター(0120-444-680)へお電話で
	ご連絡下さいませ。
	期限を過ぎてのご連絡の場合、希望月に退会が出来ない可能性がございますのでご注意ください。
・月額制を1度退会された場合、1回に限り再契約が可能です。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 166  , mb_convert_encoding( "2. パックプランについて", "SJIS", "UTF-8" ) );

// $pdf->Rect( 18 ,192 ,158 ,5, 'F');
// $pdf->Rect( 18 ,198 ,177 ,5, 'F');
// $pdf->Rect( 18 ,204 ,62 ,5, 'F');

// $pdf->Rect( 18 ,216 ,177 ,5, 'F');
// $pdf->Rect( 18 ,222 ,132 ,5, 'F');
// $pdf->Rect( 18 ,234 ,147 ,5, 'F');

$pdf->SetXY( 17, 168 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "・1度のご来店で全身の施術を行っており、45日以上期間を空けて施術を行います。
・ご予約時間に遅刻された場合は出来る限りお手入れをさせて頂きますが、出来なかった箇所については消化扱い
	となりますのでご了承下さいませ。
・予約日の前日20時以降のキャンセル、無断キャンセルの場合はいかなる場合でも1回分を消化させていただき
	ます。 
・シェービングのサービスは基本的に行っておりませんが、お客様自身で手の届きにくい 背中、うなじ、
	Oライン、ヒップについてはこちらでシェービングのお手伝いをさせていただきます。
	その他の箇所はご予約の1～2日前にお客様自身でシェービングを行っていただくようお願いいたします。 
	※剃り残しのある箇所は当日お手入れをお断りさせていただきますのでご了承下さい。 

＜通いホーダイプランについて＞
・保証期間を延長することは出来かねますのでご了承下さい。
・18回目までマイページ予約可能、19回目以降はKIREIMOコールセンターへお電話して頂きご予約をお取り下さ
	い。

", "SJIS", "UTF-8" ) , 0, 'L', 0);

// $pdf->SetFillColor(255, 105, 180);
// $pdf->Rect( 18 ,242 ,110 ,5, 'F');
// $pdf->Rect( 18 ,248 ,138 ,5, 'F');
// $pdf->Rect( 18 ,254 ,178 ,5, 'F');
// $pdf->Rect( 18 ,260 ,99 ,5, 'F');

// $pdf->SetXY( 17, 242 );
// $pdf->SetFont( KOZMIN,'B' , 10 );
// $pdf->MultiCell(178, 6, mb_convert_encoding( "・残金のお支払いは契約日から30日以内にご入金をお願い致します。
// ※30日以内に残りの代金をお支払頂けない場合は、お電話にてご連絡させて頂きます。
// ※契約日から90日以内に残りの代金をお支払頂けない場合は、サービスをご提供できないことと手付金を放棄し
//    たものとして契約を解除させて頂きますのでご注意下さい。

// ", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetXY( 17, 239 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "7/12" );


//8ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
//$pdf->Text( 80 , 14  , mb_convert_encoding( "KIREIMOのご案内", "SJIS", "UTF-8" ) );

// マーカ(2.パックプランについて)
$pdf->SetFillColor(256, 256, 0);
$pdf->Rect( 19 ,24 ,112 ,4, 'F');

$pdf->SetFillColor(255, 105, 180);
$pdf->Rect( 18 ,34 ,154 ,4, 'F');
$pdf->Rect( 18 ,40 ,120 ,4, 'F');

$pdf->SetXY( 17, 14 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "
【お支払いについて】
・残金のお支払いは契約日から30日以内にご入金をお願いいたします。(初回の施術はお支払い後となります。)
※30日以内に残りの代金をお支払頂けない場合は、お電話にてご連絡させていただきます。 
※契約日から90日以内に残りの代金をお支払頂けない場合は、サービスをご提供できないことと、
手付金を放棄したものとして契約を解除させて頂きますのでご注意下さい。

【解約について】
・解約をご希望の場合は必ずKIREIMOコールセンター（0120-444-680）へお電話でご連絡くださいませ。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

// マーカ(2.パックプランについて 以降)
$pdf->SetFillColor(256, 256, 0);
$pdf->Rect( 19 ,73 ,138 ,4, 'F');

$pdf->SetFillColor(255, 105, 180);
$pdf->Rect( 78 ,83 ,81 ,4, 'F');

$pdf->SetFillColor(256, 256, 0);
$pdf->Rect( 101 ,93 ,26 ,4, 'F');

$pdf->Rect( 19 ,115 ,179 ,4, 'F');
$pdf->Rect( 19 ,122 ,116 ,4, 'F');
$pdf->Rect( 19 ,140 ,131 ,4, 'F');
$pdf->Rect( 19 ,147 ,96 ,4, 'F');

$pdf->SetFillColor(255, 105, 180);
$pdf->Rect( 19 ,152 ,158 ,4, 'F');

$pdf->SetFillColor(256, 256, 0);
$pdf->Rect( 19 ,180 ,112 ,4, 'F');

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 71  , mb_convert_encoding( "3.会員ページについて", "SJIS", "UTF-8" ) );

$pdf->SetXY( 17, 73 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "・ご契約のお客様にはWEBからご予約を頂ける会員様専用ページをご用意しております。
	24時間WEBからのご予約が可能でございますが、ご希望日の前日20時以降のご予約は希望日当日お電話にて
	ご予約をお願いいたします。また、予約日前日の20時以降の予約キャンセルは1回消化とさせていただきます。
	（月額のお客様は予約変更を承っておりません）
・ご予約の確認の為、メールをお送りいたしますのでinfo@kireimo.jpからのメールを受け取れるようアドレスの
	登録をお願いいたします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 113  , mb_convert_encoding( "4.施術に関して (補足)", "SJIS", "UTF-8" ) );

$pdf->SetXY( 17, 115 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "・市販薬服用 前後3日間、処方薬服用 前後1週間、予防接種 前後1週間、親権者様の同意書が無い未成年のお客様
	はお手入れをお断りさせていただいておりますので、ご了承下さいませ。 
・ご予約当日、台風・大雪・地震など天変地異や交通機関のマヒなど特別な事情がある場合のキャンセル
	については、対応を考慮させていただきます。
※公共交通機関の遅延による遅刻の場合には、遅延証を忘れずにお持ちください。
	お忘れの場合には通常の遅刻扱いとさせていただきます。
・生理中のお手入れにつきましては、衛生上デリケート部位、ヒップの施術はお断りしております。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 165  , mb_convert_encoding( "5.副反応について", "SJIS", "UTF-8" ) );

$pdf->SetXY( 17, 167 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "・施術後お肌に副反応（赤み、かゆみ、ヒリつき等）が出る可能性がございます。
	清潔な濡れタオルなどで冷やしていただき、症状が落ち着かない場合、お手数をお掛けしますが、
	KIREIMOコールセンター(0120-444-680)へお電話でご連絡下さい。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 195  , mb_convert_encoding( "6.その他", "SJIS", "UTF-8" ) );

$pdf->SetXY( 17, 197 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "・お客様の私物はトリートメントルームのロッカーに入れ、ご自身で鍵の管理を行って下さい。
	紛失等の事故がおきましても、当サロンでは責任を負いかねます。

その他ご不明点やご質問の際はKIREIMOコールセンター（0120-444-680）までご連絡下さいませ。
", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetXY( 17, 239 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "8/12" );


//9ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 73 , 24  , mb_convert_encoding( "個人情報のお取扱いについて", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 35  , mb_convert_encoding( "≪個人情報保護方針≫", "SJIS", "UTF-8" ) );
$pdf->SetXY( 17, 38 );
$pdf->SetFont( KOZMIN,'' , 10 );
// $pdf->MultiCell(180, 6, mb_convert_encoding( "株式会社".($shop['id']==6 && $contract['contract_date']<'2015-01-04' ? "CKR" : "ヴィエリス")."（以下「当社」といいます）は、お客様に安全で快適なエステティックサービスを提供するにあたり「個人情報の保護に関する法律」他、関係諸法令並びに本方針と当社の各規程を遵守し、お客様の個人情報保護の徹底に努めます。
$pdf->MultiCell(180, 6, mb_convert_encoding( "株式会社ヴィエリス（以下「当社」といいます）は、お客様に安全で快適なエステティックサービスを提供するにあたり「個人情報の保護に関する法律」他、関係諸法令並びに本方針と当社の各規程を遵守し、お客様の個人情報保護の徹底に努めます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 65  , mb_convert_encoding( "≪個人情報取り扱い規約≫", "SJIS", "UTF-8" ) );
$pdf->SetXY( 17, 68 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "(1) 収集、利用について当社は、お客様の個人情報をお客様へのエステティックサービスの提供業務に必要な範囲
	 内で適正・適法な手段によって取得いたします。また、 事前にお伝えした目的の範囲内でのみ利用しお客様の
	 同意無くその範囲を超えて利用しません。
(2) 第三者への提供について当社は、あらかじめご了承をいただいた場合及び法の定めによる場合を除き第三者に
    お客様の情報を提供又は開示いたしません。但し、目的の範囲内で共同利用会社との間でお客様の個人情報を
    共同利用する場合及び秘密保持契約を締結した事業者に個人情報の取り扱いを含む業務を委託する場合があり
    ます。その場合、お客様の情報が適正に取扱われるよう共同利用会社及び委託先を管理いたします。
(3) 正確性の確保当社はお預かりした情報を正確、最新のものに保つように努めます。
(4) 適正管理当社はお預かりした個人情報を漏洩、紛失、改ざん等の事態から防ぐ為に、適切なセキュリティ対策
    を講じ厳重に管理いたします。
(5) 開示・訂正・利用停止当社はお客様がご自身の情報の内容の開示、訂正、利用停止等を希望された場合はこれ
    に応じます。但し、請求が法令による要件を満たさない場合及び当社の最終のご利用から相当期間を経過し
    たお客様の情報に関しましては対応できない場合があります。
(6) 維持、改善当社は、お客様の個人情報の取扱いが適正に行われるように従業者の教育・監督を実施します。ま
    た、本方針は適宜その内容を見直し個人情報保護の改善を図ります。
(7) 問い合わせ先個人情報の取扱いに関してのお問い合わせご相談及び開示等のお申し出は当社お客様相談室へ
    ご連絡ください。

".
// ($shop['id']==6 && $contract['contract_date']<'2015-01-04' ? "会社名：株式会社CKR
// 屋　号：ＫＩＲＥＩＭＯ
// 代表者：代表取締役　大澤　美加
// 本　社：東京都渋谷区広尾5-25-5　広尾アネックスビル7F " : "会社名：株式会社ヴィエリス
// 屋　号：ＫＩＲＥＩＭＯ
// 代表者：代表取締役　".$kireimo_ceo."
// 本　社：".$company_address)
"会社名：株式会社ヴィエリス
屋　号：ＫＩＲＥＩＭＯ
代表者：代表取締役　".$kireimo_ceo."
本　社：". $company_address
."
お客様相談室：電話　".$shop_tel."
※ 受付時間は11：00～20：00（年末年始を除く）とさせて頂いております。
※ お客様から頂いたお電話は内容確認のため録音させて頂いております。



", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 20 , 240  , mb_convert_encoding( substr($contract['contract_date'], 0,4)." 年 ".substr($contract['contract_date'], 5,2)." 月 ".substr($contract['contract_date'], 8,2)." 日", "SJIS", "UTF-8" ) );


$pdf->SetDrawColor(0,0,0);
$pdf->Rect(95 ,260 ,100 ,15);

$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 277  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "9/12" );


//10ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
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
・医療、美容機関での注射前後1週間以内
   (ニンニク注射や美容点滴)
・感染症もしくは、感染症の疑いがある方
・光アレルギー、紫外線アレルギー、光線過敏症の方
・ケロイドになりやすい方
・過度な敏感肌の方
・飲酒後の方や、飲酒のご予定のある方
   (お手入れの前後12時間はお控えください)
・粘膜部位		・白髪部位
・予防接種 前後1週間、抜歯 前後2週間
", "SJIS", "UTF-8" ) , 0, 'L', 0);
$pdf->SetXY( 90, 31 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(130, 6, mb_convert_encoding( "
・お薬を服用中、またはぬり薬、湿布薬をご使用されている方
　 または、その直後1週間以内(※市販薬…前後3日間、処方薬…前後1週間)
・白斑症もしくは、白斑症の疑いがある方
・皮膚トラブルで通院中の方や、皮膚疾患部位（傷、湿疹、腫れ物、アザ等）
・過度の日焼けをしている方、日焼けをされるご予定がある方
・ペースメーカーなどの医療用機器を使用されている方
・体内に以下のものが入っている方(ピアスも不可)
   (ボトックス・ヒアルロン酸・金の糸等)
・体調がすぐれない方（生理中含む）
・ほくろ､アートメイクをされている部位､タトゥー及び､刺青をされている部位
・ビタミンＡのサプリメントを多量に服用されている方、またはゴマージュ剤
　 ピーリング系の化粧品を施術部位にご使用されている方
   (施術前後3日は使用を中止して下さい。)
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 12, 116 );
$pdf->SetFont( KOZMIN,'U' , 10 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "施術後まれに、下記内容の副反応が起こる場合もあります。", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFillColor(256, 256, 0);
$pdf->Rect( 16 ,123 ,76 ,4, 'F');
$pdf->Rect( 16 ,133 ,92 ,4, 'F');
$pdf->Rect( 16 ,148 ,115 ,4, 'F');
$pdf->SetXY( 12, 118 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(190, 5, mb_convert_encoding( "
・施術後一時的に毛穴の赤みが起こる場合があります。患部を清潔な冷タオルで冷やし、掻いたり、こすらないようご注意ください。
　 2～3日経っても症状が引かない場合はお手数ですがKIREIMOコールセンター（0120-444-680）までご連絡お願い致します。
　 施術日を含む3日以内にご連絡がない場合、施術に起因するものか判断が困難なため責任を負いかねる場合がございます。
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
$pdf->Text( 100 , 290  , "10/12" );


//11ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
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
$pdf->Text( 17 , 42  , mb_convert_encoding( "<月額制をお申込みのご契約者様>", "SJIS", "UTF-8" ) );

$pdf->SetFillColor(256, 256, 0);
$pdf->Rect( 43 ,45 ,12 ,4, 'F');
$pdf->Rect( 50 ,65 ,129 ,4, 'F');
$pdf->Rect( 56 ,73 ,40 ,4, 'F');
$pdf->Rect( 110,107 ,80 ,4, 'F');
$pdf->Rect( 36 ,122 ,119 ,4, 'F');
$pdf->Rect( 61 ,136 ,40 ,4, 'F');
$pdf->Rect( 73 ,143 ,40 ,4, 'F');
$pdf->Rect( 110,156 ,80 ,4, 'F');
$pdf->SetXY( 17, 36 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 7, mb_convert_encoding( "
□　本プランは、毎月1回店舗に通って施術をして頂くプランです。
□　契約後1回目の施術は契約月の翌月までにご来店ください。それ以降は毎月1回の施術を受けるものとさせて頂きます。
□　本プランはいかなる場合も払い戻しは致しかねます。
□　本プランにおいて当月ご来店されなかった場合には、当月分の施術は行われたものとして扱わせて頂きます。
□　一度ご予約いただいた施術予約は変更できません。
      施術予約のご変更の場合は、当月分の施術はなされたものと扱われ、予約はお1人様毎月1回までとさせて頂きます。
□　ご契約後3ヶ月目以降のお支払い方法は、銀行引落し又はクレジット決済のいずれかのみとなります。
□　弊社でシェービングをお手伝いする場合、シェービング代1000円を別途頂戴致します。
□　本プランは、クーリングオフ対象外のプランです。ご契約後の返金は致し兼ねます。
□　キャンペーンはお1人様1回までのご利用とさせて頂きます。プラン変更の際のキャンペーンの適用は致し兼ねます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 113 );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 17 , 119  , mb_convert_encoding( "<全身脱毛パックプランをお申し込みのご契約者様>", "SJIS", "UTF-8" ) );

$pdf->SetXY( 17, 113 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 7, mb_convert_encoding( "
□　ご予約の当日のキャンセルに関しては、当該1回分の施術が行われたものとして扱われます。
      前日20時までにKIREIMOコールセンター(0120-444-680)へご連絡頂ければ、予約変更が可能です。
□　クーリング・オフ期間は、ご契約日から8日以内です。
□　中途解約(クーリングオフ期間外・契約日から9日目以後の解約)は解約手数料を頂戴します。
　　残りの施術回数分の金額の10％(上限2万円)を解約手数料とさせて頂いております。
□　キャンペーンはお1人様1回までのご利用とさせて頂きます。プラン変更の際のキャンペーンの適用は致し兼ねます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 163 );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 17 , 169  , mb_convert_encoding( "<ローンをお申込みのご契約者様>", "SJIS", "UTF-8" ) );

$pdf->SetFillColor(256, 256, 0);
$pdf->Rect( 23 ,172 ,52 ,4, 'F');
$pdf->Rect( 23 ,179 ,174 ,4, 'F');
$pdf->SetXY( 17, 163 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 7, mb_convert_encoding( "
□　初回の施術はご契約の店舗にご来店くださいますようお願い致します。
□　初回施術時までに口座情報と銀行お届け印をご持参いただけなかった場合、当日の施術が出来かねますのでご了承下さい。
□　ローンをお申込みの場合、別途分割手数料を頂戴します。
□　ローンをお申込みの場合、支払い方法、回数の変更は致しかねます。
□　ご指定頂いた日時にお申込のローン会社から契約内容、ご本人確認のお電話がございますのでご対応お願い致します。
□　ローンを中途解約される場合、別途ローンキャンセル手数料を頂戴します。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

// $pdf->SetXY( 17, 213 );
// $pdf->SetFont( KOZMIN,'B' , 10 );
// $pdf->Text( 17 , 219  , mb_convert_encoding( "<通いホーダイプランをお申込みのご契約者様>", "SJIS", "UTF-8" ) );

// // $pdf->SetFillColor(256, 256, 0);
// // $pdf->Rect( 23 ,232 ,52 ,4, 'F');
// // $pdf->Rect( 23 ,239 ,174 ,4, 'F');

// $pdf->SetXY( 17, 213 );
// $pdf->SetFont( KOZMIN,'' , 9 );

// $pdf->MultiCell(180, 7, mb_convert_encoding( "
// □　本プランの契約期間については返金の保証期間とさせて頂きます。
// □　本プランは、契約期間(保証期間)過ぎて保証回数が残っている場合も、返金の対象外となります。
// □　本プランご契約の場合、本契約書記載の契約単価に利用回数をかけた金額を消化額と致します。
// □　19回目以降は2ヶ月に1回のご予約とさせて頂きます。
// ", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 18 , 268  , mb_convert_encoding( substr($contract['contract_date'], 0,4)." 年 ".substr($contract['contract_date'], 5,2)." 月 ".substr($contract['contract_date'], 8,2)." 日", "SJIS", "UTF-8" ) );
$pdf->Text( 18 , 273  , mb_convert_encoding( "上記内容を確認しました。", "SJIS", "UTF-8" ) );

$pdf->SetDrawColor(0,0,0);
$pdf->Rect(95 ,260 ,100 ,15);
$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 277  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "11/12" );


//12ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 60 , 20  , mb_convert_encoding( "通いホーダイプランに関する同意書", "SJIS", "UTF-8" ) );

$pdf->SetXY( 17, 30 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "｢通いホーダイプランに関する同意書｣は、通いホーダイプラン(以下本プラン)が、ご契約者様のご希望に沿った内容になって
 いること、ご契約の内容が適切であることをご契約者様に確認させていただくためのものです。
 以下の該当箇所につきまして、ご確認の上、チェックを入れてください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 46 );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 100 , 62  , mb_convert_encoding( "記", "SJIS", "UTF-8" ) );

$pdf->SetXY( 17, 66 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 7, mb_convert_encoding( "
□　本プランの契約期間は、「エステティックサービス契約書」記載の契約期間とし、期間満了をもって終了となります。
       但し、本プランは、契約期間が過ぎても、役務提供は期間、回数ともに無制限とさせて頂きます。
□　契約期間が過ぎた役務提供は、契約ローン会社の対象期間とはなりませんので、仮に役務提供が受けられなくとも、ローン
       契約に基づく支払い停止の抗弁や既払金の返金原因とはなりません。
       （お支払いの方法としてローンをご契約のお客様のみ対象となります。）
□　本プランは、契約期間(保証期間)過ぎて保証回数が残っている場合も、返金の対象外となります。
□　本プランご契約の場合、本契約書記載の契約単価に利用回数をかけた金額を消化額と致します。
□　18回目までは最短1ヶ月半に1回マイページ予約、19回目以降は2ヶ月に1回電話ご予約とさせて頂きます。 
□　19回目以降はパーツでの施術が可能です。
       18回目まではパーツ施術の場合、全身1回分消化となりますので、全身の施術でのご来店をお勧め致します。

", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 18 , 218  , mb_convert_encoding( substr($contract['contract_date'], 0,4)." 年 ".substr($contract['contract_date'], 5,2)." 月 ".substr($contract['contract_date'], 8,2)." 日", "SJIS", "UTF-8" ) );
$pdf->Text( 18 , 223  , mb_convert_encoding( "私は、通いホーダイプランについて、上記のとおり同意します。", "SJIS", "UTF-8" ) );

$pdf->SetDrawColor(0,0,0);
$pdf->Rect(95 ,240 ,100 ,15);
$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 257  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "12/12" );


//$pdf->Output();
$pdf->Output($customer_name.$customer['no'].".pdf","I");
?>