<?php

ini_set( 'include_path', dirname(__FILE__) . "/../../php-lib/fpdf" );
require_once( 'mbfpdf.php');
require_once( '../../config/config.php');

header('Content-type: application/pdf');
header('Content-disposition: attachment; filename="'.$_GET['name'].'.pdf"');

$title = "クレジット取消処理明細書";

$msg = "このたびはご契約頂いた".$_GET['course_name']."プランをご解約致しました。\nご確認下さいませ。\n宜しくお願い致します。";

$shop = "https://kireimo.jp\n\n株式会社ヴィエリス\nKIREIMO ".$_GET['shop_name']."\n〒160-0023\n東京都新宿区西新宿1-19-18\n新東京ビル5F\nTEL:03-6721-1641\nEmail:info@kireimo.jp";

$balance_name = $_GET['balance'] ? "残金" : "";

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
$pdf->SetTextColor(128, 128, 0);
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

$pdf->Text( 107 , 67  , mb_convert_encoding( "サロン名", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 122 , 67  , mb_convert_encoding( "KIREIMO", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
//髙等のIBM拡張文字をsjis-winに
$pdf->Text( 16 , 67  , mb_convert_encoding( "お名前　".$_GET['name']."　　　　　様 ", "sjis-win", "UTF-8" ) );  
$pdf->Text( 106 , 74  , mb_convert_encoding( "店  舗  名： ".$_GET['shop_name']."                                                              ", "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 78  , mb_convert_encoding( "店舗住所： ".$_GET['shop_address'], "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 82  , mb_convert_encoding( "電話番号： 0120- 567- 144                                               ", "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 86  , mb_convert_encoding( "作  成  者： ".$_GET['staff']."                                                         ", "SJIS", "UTF-8" ) );
    
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
$pdf->Cell(15, 5, mb_convert_encoding( "1回のお手れ", "SJIS", "UTF-8" ), "LTR",0,"L");
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Cell(40, 5, mb_convert_encoding( "定価", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(45, 5, mb_convert_encoding( "割引後金額", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 95, 110 );
$pdf->SetFont( KOZMIN,'' , 7 );
$pdf->Cell(15, 5, mb_convert_encoding( "時間(分)", "SJIS", "UTF-8" ), "LRB",0,"C");
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Cell(20, 5, mb_convert_encoding( "単価(円)", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "料金(円)", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "単価(円)", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "料金(円)", "SJIS", "UTF-8" ), 1,0,"C");


$pdf->SetXY( 15, 115 );
$pdf->Cell(6, 10, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 10, mb_convert_encoding( $_GET['course_name'], "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(10, 10, mb_convert_encoding( $_GET['times'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(15, 10, mb_convert_encoding( $_GET['lenth'], "SJIS", "UTF-8" ), "1",0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['per_fixed_price']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['fixed_price']), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['per_price']), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( number_format($_GET['price']), "SJIS", "UTF-8" ), 1,0,"R");

$pdf->SetXY( 110, 120 );
$pdf->Cell(20, 5, mb_convert_encoding( "契約期間", "SJIS", "UTF-8" ), 1,0,"C");
//$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$contract_period = ($_GET['end_date']=="0000-00-00") ? "" : str_replace("-", "/",$_GET['contract_date'])."～".str_replace("-", "/",$_GET['end_date']);
$pdf->Cell(65, 5, mb_convert_encoding( $contract_period , "SJIS", "UTF-8" ), 1,0,"C");

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
$pdf->Text( 17 , 149  , mb_convert_encoding( "※契約期間は契約箇所別の回数に2ヶ月を乗じた期間とします。", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 153  , mb_convert_encoding( "※1回当たりのお手入れ時間あ、目安となります。※コース金額につきましては、概要書面発行日当日にご契約いただいた場合の金額となります。", "SJIS", "UTF-8" ) );


$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 16 , 163  , mb_convert_encoding( "■特典明細", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 165 );
$pdf->Cell(135, 5, mb_convert_encoding( "特典明細", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "回数", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "特典価格（円）", "SJIS", "UTF-8" ), 1,0,"L");
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
$pdf->Cell(64, 5, mb_convert_encoding( ($_GET['discount'] ? $_GET['course_name'] : ""), "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( ($_GET['discount']  ? ($_GET['times'] ? number_format(round($_GET['discount']/ $_GET['times'])) : $_GET['discount']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($_GET['discount'] ? number_format($_GET['discount']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
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
$pdf->Cell(45, 10, mb_convert_encoding( number_format($_GET['price'])."円", "SJIS", "UTF-8" ), 1,0,"R");

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "1/2" );

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
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['price']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( "-", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 150 );
$pdf->Cell(180, 5, mb_convert_encoding( "前払い金の保金処置：なし", "SJIS", "UTF-8" ), 1,0,"L");


$pdf->SetFont( KOZMIN,'' , 7 );
$pdf->Text( 17 , 158  , mb_convert_encoding( "※クレジットご利用の場合、抗弁権の接続ができます。詳細については契約書を良くお読み下さい。", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 162  , mb_convert_encoding( "前受け金の保全措置はありません。", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 17 , 170  , mb_convert_encoding( "3.特約事項：なし", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 180  , mb_convert_encoding( "4.契約の解除に関する事項：クーリングオフ並びに中途解約につきましては、概要書面の該当欄をご確認ください。", "SJIS", "UTF-8" ) );


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
$pdf->Text( 100 , 285  , "2/2" );




$pdf->Output($_GET['name'].$_GET['no']."(概要書面)","I");
//$pdf->Output();
?>
