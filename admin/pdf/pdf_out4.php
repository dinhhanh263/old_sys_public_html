<?php

ini_set( 'include_path', dirname(__FILE__) . "/../../php-lib/fpdf" );
require_once( 'mbfpdf.php');
require_once( '../../config/config.php');

header('Content-type: application/pdf');
header('Content-disposition: attachment; filename="'.$_GET['name'].'.pdf"');

//$title = "特例トリートメント同意書&保証期間延長申請書";

//$msg = "このたびはご契約頂いた".$_GET['course_name']."プランをご解約致しました。\nご確認下さいませ。\n宜しくお願い致します。";

//$shop = "https://kireimo.jp\n\n株式会社ヴィエリス\nKIREIMO ".$_GET['shop_name']."\n〒160-0023\n東京都新宿区西新宿1-19-18\n新東京ビル5F\nTEL:03-6721-1641\nEmail:info@kireimo.jp";

$balance_name = $_GET['balance'] ? "残金" : "";
$_GET['shop_tel']="0120-444-680";

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
$pdf->Text( 165 , 36  , mb_convert_encoding( "No. ".substr($_GET['hope_date'], 2,2).substr($_GET['hope_date'], 5,2)."- ". $_GET['no']."  ", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 18 );
$pdf->Text( 75 , 40  , mb_convert_encoding( "概 要 書 面", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 14 );
$pdf->Text( 107 , 40  , mb_convert_encoding( "(事前説明書)", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 16 , 48  , mb_convert_encoding( substr($_GET['hope_date'], 0,4)." 年 ".substr($_GET['hope_date'], 5,2)." 月 ".substr($_GET['hope_date'], 8,2)." 日", "SJIS", "UTF-8" ) );



if($_GET['shop_id']==6 && $_GET['hope_date']<'2015-01-04'){

$pdf->Image("../../img/ckr.png",155,38,24); //横幅のみ指定,24

$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Text( 107 , 48  , mb_convert_encoding( "会  社  名", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 122 , 48  , mb_convert_encoding( "株式会社CKR", "SJIS", "UTF-8" ) ); 

$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Text( 107 , 52  , mb_convert_encoding( "所  在  地", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 52  , mb_convert_encoding( "〒150- 0012　東京都渋谷区広尾5-25-5", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 55  , mb_convert_encoding( "広尾アネックスビル7F", "SJIS", "UTF-8" ) );

$pdf->Text( 107 , 59  , mb_convert_encoding( "電話番号", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 59  , mb_convert_encoding( "TEL: 03- 5422- 7501　FAX:03- 3447- 6086", "SJIS", "UTF-8" ) );

$pdf->Text( 107 , 63  , mb_convert_encoding( "代  表  者", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 63  , mb_convert_encoding( "代表取締役社長　大澤　美加", "SJIS", "UTF-8" ) );

}else{

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

}



$pdf->Text( 107 , 67  , mb_convert_encoding( "サロン名", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 122 , 67  , mb_convert_encoding( "KIREIMO", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Text( 16 , 67  , mb_convert_encoding( "お名前　".$_GET['name']."　　　　　様 ", "SJIS-win", "UTF-8" ) );  
$pdf->Text( 106 , 74  , mb_convert_encoding( "店  舗  名： ".$_GET['shop_name']."                                                              ", "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 78  , mb_convert_encoding( "店舗住所： ".$_GET['shop_address'], "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 82  , mb_convert_encoding( "電話番号： ".$_GET['shop_tel']."                                               ", "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 86  , mb_convert_encoding( "作  成  者： ".$_GET['staff']."                                                         ", "SJIS-win", "UTF-8" ) );
    
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
$pdf->Cell(15, 10, mb_convert_encoding( $_GET['length'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['per_fixed_price']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['fixed_price']), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['per_price']), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( number_format($_GET['fixed_price']-$_GET['discount']), "SJIS", "UTF-8" ), 1,0,"R");

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
$pdf->Text( 17 , 153  , mb_convert_encoding( "※1回当たりのお手入れ時間は、目安となります。※コース金額につきましては、概要書面発行日当日にご契約いただいた場合の金額となります。", "SJIS", "UTF-8" ) );


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
$pdf->Cell(45, 10, mb_convert_encoding( number_format($_GET['fixed_price']-$_GET['discount'])."円", "SJIS", "UTF-8" ), 1,0,"R");

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "1/10" );

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
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['fixed_price']-$_GET['discount']), "SJIS", "UTF-8" ), 1,0,"R");
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
$pdf->Text( 100 , 285  , "2/10" );

//3ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'' , 12 );
$pdf->SetTextColor(255, 0, 0);
$pdf->Text( 17 , 24  , mb_convert_encoding( "<クーリングオフについて>", "SJIS", "UTF-8" ) );

$pdf->SetDrawColor(255,0,0);
$pdf->Rect(15 ,26 ,180 ,29);

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Text( 17 , 32  , mb_convert_encoding( "本契約を定める事項を記載した契約書面を受領した日から起算して8日間がクーリングオフ期間となり契約を解", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 37  , mb_convert_encoding( "除することができます。この場合は解約手数料・違約金を不要とし、お客様より既に受領した代金はご返金致し", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 42  , mb_convert_encoding( "ます。以後の請求はございません。", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 47  , mb_convert_encoding( "クーリングオフ（契約解除）の文例について約款の第7条をご参考下さい。", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 52  , mb_convert_encoding( "クーリングオフの詳細については約款の第6条~8条をご確認下さい。", "SJIS", "UTF-8" ) );

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 65  , mb_convert_encoding( "< 中 途 解 約 >", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 68 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "クーリングオフ期間を過ぎた場合でも契約を解除できます。
途中解約手数料として残金の10%を差し引かせて頂きます。
解約時の返金額等の算出方法としては、コース・プラン1回あたりの料金である補正単価（契約金額を契約回数で除した結果が割り切れない場合1円未満を四捨五入）に利用回数をかけた金額を消化額とし、支払総額から消化額
を引いた金額を清算金とします。
　　解約手数料金額　＝　｛支払総額　－　（1回あたりの料金　×　利用回数　）｝×10%
　　清算金　＝　残金　－　解約手数料金額
なお、クーリングオフ期間外で契約期間内にお手入れを一度も行っていないコース・プランの解約については契約した金額から解約手数料10%を頂戴致します。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetTextColor(255, 0, 0);
$pdf->SetXY( 19, 117 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(174, 5, mb_convert_encoding( "※複数箇所でコース1回分とみなす契約（全身パック、月額コース、キャンペーン時におけるセットのコース）につきましてはコースに含まれる箇所を1箇所でもお手入れした場合、コース1回分を消化したとみなし、解約時の返金対象とはなりません。
（例）全身コース6回契約時、ヒザ下1回のみお手入れ後、解約を希望した場合の返金対象は、全身5回分となります。
※現金の受け渡しによる返金は行っておりません。金融機関への振込とさせて頂きます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY( 15, 146 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "以下のいずれかに該当する場合は、当社より契約の解除をさせていただく場合がございます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 19, 152 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(174, 5, mb_convert_encoding( "※契約金の金額が、契約日より起算して90日以内にお支払いいただけない場合。
※お客様の体質等に起因して、お手入れの継続が困難だと当社が判断した時。
※お客様との信頼関係の維持が困難と判断した時。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 175  , mb_convert_encoding( "< システム補足 >", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 180 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "●当サロンのコース契約についてはご契約の際、身分証の提示が必要になります。

●未成年のお客様については、親権者の同意が必要となります。同意書には、親権者の続柄・ご連絡先の電話番号
   のご記入と署名・捺印が必要です。また、追ってサロンから親権者の方へ確認の連絡をさせて頂きます。
   (同意書の提出がない場合は無効とさせていただきます）

●ご契約に関しては、現金一括払いをご希望のお客様で、契約日当日にお手持ちの現金が足りない場合は、お支払
   い代金の一部(1回当たりの料金以上の金額)を手付金としてお支払いいただくことで、当日の契約が可能です。
   ただし、契約日から90日以内に残りの代金をお支払いいただけない場合は、サービスを提供できないことと手付
   金を放棄したものとして、契約を解除させていただきますのでご注意下さい。なお、契約日から30日以内に残
   りの代金をお支払いいただいていない場合は、契約解除手続きについてのお知らせが届くことがあります。

●お手入れの間隔は2ヶ月以上空けて下さい。期間内に回数消化できない場合は契約日から保証期間中を回数保証
   とし契約期間が過ぎても最終来店日（お手入れ日）から1年以内であれば返金が可能です。

●各種特典のご利用については、ご契約いただいた回数の最後に適用可能です。有料の特典については未使用の場
   合のみ、特典分として受領した金額が返金対象となります。また無料の特典については返金対象外です。
", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "3/10" );

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

$pdf->SetXY( 15, 90 );
$pdf->Cell(6, 10, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 10, mb_convert_encoding(  $_GET['course_name'], "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(10, 10, mb_convert_encoding( $_GET['times'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(15, 10, mb_convert_encoding( $_GET['length'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['per_fixed_price']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(20, 5, mb_convert_encoding(  number_format($_GET['fixed_price']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(20, 5, mb_convert_encoding(  number_format($_GET['per_price']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding(  number_format($_GET['fixed_price']-$_GET['discount']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->SetXY( 110, 95 );
$pdf->Cell(20, 5, mb_convert_encoding( "契約期間", "SJIS", "UTF-8" ), 1,0,"C");
$contract_period = ($_GET['end_date']=="0000-00-00") ? "" : str_replace("-", "/",$_GET['contract_date'])."～".str_replace("-", "/",$_GET['end_date']);
$pdf->Cell(65, 5, mb_convert_encoding( $contract_period , "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 100 );
$pdf->Cell(6, 10, mb_convert_encoding( "2", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 10, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(10, 10, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(15, 10, mb_convert_encoding( "", "SJIS", "UTF-8" ), "1",0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 110, 105 );
$pdf->Cell(20, 5, mb_convert_encoding( "契約期間", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 110 );
$pdf->Cell(6, 10, mb_convert_encoding( "3", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 10, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(10, 10, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(15, 10, mb_convert_encoding( "", "SJIS", "UTF-8" ), "1",0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 110, 115 );
$pdf->Cell(20, 5, mb_convert_encoding( "契約期間", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetFont( KOZMIN,'' , 7 );
$pdf->Text( 17 , 124  , mb_convert_encoding( "※契約期間は契約箇所別の回数に2ヶ月を乗じた期間とします。", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 128  , mb_convert_encoding( "※1回当たりのお手入れ時間は、目安となります。※コース金額につきましては、概要書面発行日当日にご契約いただいた場合の金額となります。", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 16 , 138  , mb_convert_encoding( "■特典明細", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 10 );

$pdf->SetXY( 15, 140 );
$pdf->Cell(135, 5, mb_convert_encoding( "特典明細", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "回数", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "特典価格（円）", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 145 );
$pdf->Cell(6, 5, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 150 );
$pdf->Cell(6, 5, mb_convert_encoding( "2", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 155 );
$pdf->Cell(6, 5, mb_convert_encoding( "3", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 160 );
$pdf->Cell(6, 5, mb_convert_encoding( "4", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 165 );
$pdf->Cell(6, 5, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 16 , 178  , mb_convert_encoding( "■割引", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 10 );

$pdf->SetXY( 15, 180 );
$pdf->Cell(70, 10, mb_convert_encoding( "割引対象コース", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 10, mb_convert_encoding( "割引明細", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(45, 5, mb_convert_encoding( "割引金額", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 150, 185 );
$pdf->Cell(20, 5, mb_convert_encoding( "単価(円)", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "料金(円)", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 190 );
$pdf->Cell(6, 5, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( ($_GET['discount'] ? $_GET['course_name'] : ""), "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( ($_GET['discount']  ? ($_GET['times'] ? number_format(round($_GET['discount']/ $_GET['times'])) : $_GET['discount']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($_GET['discount'] ? number_format($_GET['discount']) : ""), "SJIS", "UTF-8" ), 1,0,"R");


$pdf->SetXY( 15, 195 );
$pdf->Cell(6, 5, mb_convert_encoding( "2", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 200 );
$pdf->Cell(6, 5, mb_convert_encoding( "3", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 205 );
$pdf->Cell(6, 5, mb_convert_encoding( "4", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 210 );
$pdf->Cell(6, 5, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 215 );
$pdf->Cell(6, 5, mb_convert_encoding( "6", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 220 );
$pdf->Cell(6, 5, mb_convert_encoding( "7", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 225 );
$pdf->Cell(6, 5, mb_convert_encoding( "8", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 230 );
$pdf->Cell(6, 5, mb_convert_encoding( "9", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 235 );
$pdf->Cell(6, 5, mb_convert_encoding( "10", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(64, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 120, 250);
$pdf->SetFillColor(238, 233, 233);
$pdf->Rect( 120 ,250 ,30 ,10, 'DF');
$pdf->Cell(30, 10, mb_convert_encoding( "総支払合計", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(45, 10, mb_convert_encoding( number_format($_GET['fixed_price']-$_GET['discount'])." 円", "SJIS", "UTF-8" ) , 1,0,"R");

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "4/10" );

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
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['fixed_price']-$_GET['discount']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( "-", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 150 );
$pdf->Cell(180, 5, mb_convert_encoding( "前払い金の保金処置：なし", "SJIS", "UTF-8" ), 1,0,"L");

$pdf->Rect(95 ,160 ,100 ,15);
$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 177  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 17 , 190  , mb_convert_encoding( "上記内容を確かに受け取りました。", "SJIS", "UTF-8" ) );


if($_GET['shop_id']==6 && $_GET['hope_date']<'2015-01-04'){

$pdf->Image("../../img/ckr.png",155,200,24); //横幅のみ指定,24

$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Text( 103 , 208  , mb_convert_encoding( "(乙)会  社  名", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 122 , 208  , mb_convert_encoding( "株式会社CKR", "SJIS", "UTF-8" ) ); 

$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Text( 107 , 212  , mb_convert_encoding( "所  在  地", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 212  , mb_convert_encoding( "〒150- 0012　東京都渋谷区広尾5-25-5", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 215  , mb_convert_encoding( "広尾アネックスビル7F", "SJIS", "UTF-8" ) );

$pdf->Text( 107 , 219  , mb_convert_encoding( "電話番号", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 219  , mb_convert_encoding( "TEL: 03- 5422- 7501　FAX:03- 3447- 6086", "SJIS", "UTF-8" ) );

$pdf->Text( 107 , 223  , mb_convert_encoding( "代  表  者", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 223  , mb_convert_encoding( "代表取締役社長　大澤　美加", "SJIS", "UTF-8" ) );

}else{

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

}


$pdf->Text( 107 , 227  , mb_convert_encoding( "サロン名", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 122 , 227  , mb_convert_encoding( "KIREIMO", "SJIS", "UTF-8" ) );

$pdf->Image("img/logo.png",20,230,40); //横幅のみ指定,24

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Text( 106 , 235  , mb_convert_encoding( "店  舗  名： ".$_GET['shop_name']."                                                              ", "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 240  , mb_convert_encoding( "店舗住所： ".$_GET['shop_address'], "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 245  , mb_convert_encoding( "電話番号： ".$_GET['shop_tel']."                                               ", "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 250  , mb_convert_encoding( "担 当  者： ".$_GET['staff']."                                                         ", "SJIS-win", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "5/10" );

//6ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );

$pdf->Text( 73 , 24  , mb_convert_encoding( "エステティックサービス約款", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 29  , mb_convert_encoding( "第1条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 26.8 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(契約の成立)
お客様(以下「甲」といいます)は、本契約書の記載内容および約款の各条項を承諾の上、本日当サロン(以下「乙」いいます)に対して
、エステティックサービス(以下「役務」といいます)にお申し込みを行い、乙はこれを承諾しました。
2. 甲が未成年の場合は、親権者の同意が必要としますので、「親権者同意書」等の書面で親権者の同意を乙が確認した上で、本契約の
    成立となります。
3. 甲がクレジットを利用する場合は、甲およびクレジット会社の立替払契約が成立しないときは、本契約も成立しなかったものとみな
    します。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 53  , mb_convert_encoding( "第2条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 50.2 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(役務内容)
乙は甲に対し、本契約書に記載する月額コースまたはパックプランおよびその回数の役務を提供するものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 60  , mb_convert_encoding( "第3条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 57.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(役務の金額、支払方法、支払時期)
乙は、甲に提供する役務の対価、関連商品がある場合は、その代金その他甲が支払わなければならない金額を本契約に明記します。
2. 月額制コースにおいては、前月前払い制とし、その支払いをクレジットもしくは銀行口座振替を選択できるものとします。但し、い
    づれも金融機関の決済が取れなかった場合、その月末までにお支払いがない限り、翌月以降の予約が取り消しとなります。
3. 甲は、役務の支払い方法として、前払金の現金一括払いまたは乙と提携するクレジット会社の立替払い等の中から甲の希望する方法
    を選択できるものとします。
4. 甲が前項の前払い一括払いを選択した場合、契約日にその全額を持ち合わせていない場合、甲は一時金(手付金)を納付するものとし
    ます。
5. 前項の場合、甲は契約日から起算して９０日以内に前払金の残金のお支払いがない場合、乙は甲が前項の手付金を放棄したものとし
    て、本契約が解約処理となる事に異議を述べないものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 93  , mb_convert_encoding( "第4条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 90.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(役務の提供期間)
役務の提供期間は、本契約書に記載された期間とします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetDrawColor(255,0,0);
$pdf->Rect(13 ,98 ,186 ,76);
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->SetTextColor(255, 0, 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 103  , mb_convert_encoding( "第5条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 100.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(クーリング・オフ)
甲は、契約書面を受領した日から起算して８日間以内であれば、関連商品を含め、書面により契約を解除することができます。尚、
関連商品のみのクーリング・オフはできません。また、乙が契約に関して甲より金銭を受領している場合は、速やかに全額を返金いたし
ます。但し、関連商品を開封したり、その全部もしくは一部を消費した時は、当該商品に限りクーリング・オフすることはできません。
2. 乙が甲に不実のことを告げ、または威迫等によりクーリング・オフが妨害された場合、甲は改めてクーリング・オフができる旨を記
    載した書面を受領し、乙より説明を受けた日から起算して８日間以内であれば、書面によるクーリング・オフをすることができ
    ます。
3. 関連商品の引き渡しが既に行われている場合は、当該関連商品の引き取りに要する費用は乙の負担とします。
4. クーリング・オフは、甲がクーリング・オフの書面を乙宛てに発信した時に、その効力が生じます。
5. クレジットを利用した契約の場合、甲は乙に契約の解除を申し出た旨をクレジット会社にも別途書面による通知をするものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 27, 137 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(160, 3.2, mb_convert_encoding( "
  	                                                         クーリング・オフ(契約解除)の文例
20○○年〇月○日、貴社〇〇店との間で締結したエステティックサービス契約について、約款第５条に基づき契約を解除し
ます。つきましては、私が貴社に支払った代金○○○円を下記銀行口座に振り込んでください。（また、私が受け取った商品
をお引き取りください）
○○銀行〇○支店　普通預金口座○○○○　口座名義人　○○○○
20○○年〇月〇日
契約者 (住所)
            (氏名)　　　　　　　　　    　印
".
($_GET['shop_id']==6 && $_GET['hope_date']<'2015-01-04' ? "株式会社CKR　代表者　大澤　美加" : "株式会社ヴィエリス　代表者　".$kireimo_ceo)."殿

", "SJIS", "UTF-8" ) , 1, 'L', 0);


$pdf->SetTextColor(0, 0, 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 180  , mb_convert_encoding( "第6条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 177.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(中途解約)
甲は、クーリング・オフ期間を過ぎても、関連商品を含め契約の中途解約ができます。
2. 中途解約に関して、既にお支払いいただいている金額の内、未消化役務分の１０％（円未満切り捨て）を解約手数料としてお支払いい
    ただきます。（上限２万円）
3. 返金に関しては、未消化役務の金額より、前項の解約手数料と銀行送金手数料を差し引いた金額を返金いたします。
4. 関連商品の場合、解約手数料はかかりませんが、返送の費用および返金の振込手数料は、甲の負担とします。但し、乙に商品到着後の
    返金となります。
5. 但し、関連商品の場合、商品を開封したり、その全部もしくは一部を消費した時は、当該商品に限り中途解約することができません。
    また、未使用であっても、著しく商品価値が損なわれている場合は、返金対象外となる場合があります。
6. 役務の提供期間が過ぎた契約については、解約ができませんのでご注意ください。
7. クレジット等をご使用の場合の精算は、各クレジット会社の所定の方法によるものとします。また、甲は乙がクレジット会社の請求に
    より精算上必要な範囲において、甲の利用回数をクレジット会社に通知することを承諾するものとします。
8. 月額コースの契約の場合、解約の申し出は、原則１か月以前とさせていただきます。但し、金融機関の都合により、解約の申し出時点
   でクレジット決済または銀行口座振替の中止ができない場合、乙はその金額を金融機関より受領後、すみやかに全額返金します。
   (返金手数料は、甲の負担となります)
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 229  , mb_convert_encoding( "第7条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 226.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(施術上の注意)
乙は、甲に役務提供するにあたり、事前に甲の体質（治療中の皮膚疾患・アレルギー・敏感肌・薬の服用の有無など）および体調を聴取
し、確認するものとします。甲の体調・体質により、甲への役務提供をお断りする場合があります。
2. 役務提供期間中、甲が体調を崩したり、施術部位に異常が生じた場合、甲はその旨を乙に伝えるものとします。この場合、乙は直ちに
    役務を中止します。その原因が乙の施術に起因する疑いがある場合は、一旦乙の負担で、甲に医師の診断を受けて頂く等の適切な処置
    を取ることとし、甲乙協議の上解決するものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 249  , mb_convert_encoding( "第8条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 246.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(別途協議)
本契約書に定める事項に疑義が生じた場合は、甲乙協議の上解決するものとします。
2. 本契約書に定めのない事項については、民法その他の法令によるものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "6/10" );

//7ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 80 , 24  , mb_convert_encoding( "KIREIMOのご案内", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 32  , mb_convert_encoding( "1.会員登録について", "SJIS", "UTF-8" ) );
$pdf->SetXY( 17, 34 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "当サロンは会員制となっておりますので、お手入れを始める前にあらかじめ会員登録をお願いしています。会員の皆様には、各種特典や優待価格の案内をさせて頂きます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 51  , mb_convert_encoding( "2.月額制について", "SJIS", "UTF-8" ) );

$pdf->SetFillColor(256, 256, 0);
$pdf->Rect( 153 ,58 ,44 ,4, 'F');
$pdf->Rect( 19 ,63 ,161 ,4, 'F');
$pdf->Rect( 19 ,68 ,177 ,4, 'F');
$pdf->Rect( 19 ,73 ,42 ,4, 'F');

$pdf->Rect( 19 ,78 ,177 ,4, 'F');
$pdf->Rect( 19 ,83 ,21 ,4, 'F');

$pdf->Rect( 19 ,93 ,157 ,4, 'F');
$pdf->Rect( 42 ,98 ,58 ,4, 'F');
$pdf->Rect( 19 ,103 ,126 ,4, 'F');

$pdf->Rect( 19 ,118 ,177 ,4, 'F');

$pdf->Rect( 19 ,123 ,72 ,4, 'F');
$pdf->Rect( 66 ,133 ,130 ,4, 'F');
$pdf->Rect( 19 ,138 ,62 ,4, 'F');
$pdf->Rect( 19 ,143 ,151 ,4, 'F');
$pdf->Rect( 19 ,158 ,112 ,4, 'F');


$pdf->SetXY( 17, 53 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "・月額制は新規入会時に2か月分のご料金をお支払頂きます。
・3か月目以降はクレジットもしくは銀行引き落としのみのお支払とさせて頂きます。銀行引き落としをご希望の
  場合は1ヵ月目のお手入れの際にお手続きをさせて頂きますので、キャッシュカードをご持参下さい。
  クレジット引き落としをご希望の場合は2ヵ月目のお手入れの際にお手続きをさせて頂きますのでクレジットカ
  ードをお持ち下さいませ。ご持参頂けなかった場合は3ヵ月目以降のご継続をお断りさせて頂きます。
  引き落としが出来なかった場合には弊社からご連絡をさせて頂き、お客様からご希望があった場合、解約手続き
  を致します。
・月額制を1度退会されると再契約が出来兼ねますのでご注意下さい。
・月額制は一度お手入れのご予約をされますと、ご予約変更は致し兼ねますのでご注意下さいませ。
・通い方として1ヵ月目に下半身、2か月目に上半身のご予約となります。
・ご予約時間から20分すぎた遅刻の場合はお手入れを１回消化させて頂きます。
　20分以内にご来店頂いた場合は出来る限りお手入れをさせて頂きますが、出来なかった箇所に関しては消化扱
   いとなります。
・生理中、お薬の服用中、予防接種前後2週間、親権者様の同意書の無い未成年のお客様はお手入れをお断りさせ
   て頂いておりますので、ご了承下さいませ。
・シェービングのサービスは行っておりません。
   お客様自身で手の届かない、背中、うなじ、Oライン、ヒップはシェービング補助代として1000円を、頂戴し
   ております。(お支払方法は現金のみ)
   その他の箇所はご予約の2,3日前にお客様自身でシェービングをして頂くようお願い致します。
   剃り残しがあった場合は当日お手入れをお断りさせて頂きますのでご了承下さい。
・月額制はいかなる場合も払い戻し致しかねますので、ご了承下さいませ。
・中途解約をされる場合は、解約の1か月前に店舗へご連絡下さいませ。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 168  , mb_convert_encoding( "3. パックプランについて", "SJIS", "UTF-8" ) );


$pdf->Rect( 18 ,182 ,158 ,5, 'F');
$pdf->Rect( 18 ,188 ,177 ,5, 'F');
$pdf->Rect( 18 ,194 ,62 ,5, 'F');

$pdf->Rect( 18 ,206 ,177 ,5, 'F');
$pdf->Rect( 18 ,212 ,132 ,5, 'F');
$pdf->Rect( 18 ,224 ,147 ,5, 'F');


$pdf->SetXY( 17, 170 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "・ご予約時間に遅刻された場合は出来る限りお手入れをさせて頂きますが、出来なかった箇所については消化扱
	いとなりますのでご了承下さいませ。
・当日キャンセル、無断キャンセルをされますと、いかなる場合でも1回分を消化させて頂きます。
   ※ご予約当日、台風・大雪・地震など天変地異や交通機関のマヒなど特別な事情がある場合のキャンセルにつ
   いては、対応を考慮させて頂きます）
・シェービングのサービスは基本的に行っておりません。
  背中、うなじ、Oライン、ヒップについてはこちらでシェービングのお手伝いをさせて頂きますが、その他の箇
  所はご予約の2,3日前にお客様自身でシェービングをして頂くようお願い致します。
 剃り残しのある箇所は当日お手入れをカットさせて頂きますのでご了承下さい。
・解約をご希望の場合はKIREIMOコールセンター（0120-444-680）へお問い合わせ下さい。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFillColor(255, 105, 180);
$pdf->Rect( 18 ,232 ,110 ,5, 'F');
$pdf->Rect( 18 ,238 ,138 ,5, 'F');
$pdf->Rect( 18 ,244 ,178 ,5, 'F');
$pdf->Rect( 18 ,250 ,99 ,5, 'F');

$pdf->SetXY( 17, 232 );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "・残金のお支払いは契約日から30日以内にご入金をお願い致します。
※30日以内に残りの代金をお支払頂けない場合は、お電話にてご連絡させて頂きます。
※契約日から90日以内に残りの代金をお支払頂けない場合は、サービスをご提供できないことと手附金を破棄し
   たものとして契約を解除させて頂きますのでご注意下さい。

", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 239 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "7/10" );

//8ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 73 , 24  , mb_convert_encoding( "個人情報のお取扱いについて", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 35  , mb_convert_encoding( "≪個人情報保護方針≫", "SJIS", "UTF-8" ) );
$pdf->SetXY( 17, 38 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "株式会社".($_GET['shop_id']==6 && $_GET['hope_date']<'2015-01-04' ? "CKR" : "ヴィエリス")."（以下「当社」といいます）は、お客様に安全で快適なエステティックサービスを提供するにあたり「個人情報の保護に関する法律」他、関係諸法令並びに本方針と当社の各規程を遵守し、お客様の個人情報保護の徹底に努めます。
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
($_GET['shop_id']==6 && $_GET['hope_date']<'2015-01-04' ? "会社名：株式会社CKR 
屋　号：ＫＩＲＥＩＭＯ 
代表者：代表取締役　大澤　美加 
本　社：東京都渋谷区広尾5-25-5　広尾アネックスビル7F " : "会社名：株式会社ヴィエリス 
屋　号：ＫＩＲＥＩＭＯ 
代表者：代表取締役　".$kireimo_ceo."
本　社：" .$company_address)
."

お客様相談室：電話　".$_GET['shop_tel']."
※ 受付時間は11：00～20：00（年末年始を除く）とさせて頂いております。
※ お客様から頂いたお電話は内容確認のため録音させて頂いております。



", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 20 , 240  , mb_convert_encoding( substr($_GET['hope_date'], 0,4)." 年 ".substr($_GET['hope_date'], 5,2)." 月 ".substr($_GET['hope_date'], 8,2)." 日", "SJIS", "UTF-8" ) );


$pdf->SetDrawColor(0,0,0);
$pdf->Rect(95 ,260 ,100 ,15);

$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 277  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "8/10" );

//9ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 73 , 24  , mb_convert_encoding( "除毛・減毛トリートメント同意書", "SJIS", "UTF-8" ) );

$pdf->SetXY( 17, 32 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "当サロンで行うトリートメントはＩＰＬを用いた機器を使用し施術を行います。								
トリートメントを安心してお受けいただくため、下記内容についてご確認・ご承諾のチェックをお願いいたします。	
下記の内容をご確認の上、不明な点はスタッフにご質問ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 50 );
$pdf->SetFont( KOZMIN,'U' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "下記の内容は禁忌とされている状態、または箇所になります。原則として施術を行うことができませんので、ご了承下さい。									
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 53 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(100, 6, mb_convert_encoding( "
□現在治療中または持病等をお持ちの方			
□ガン、てんかん等の既往歴がある方			
□妊娠中、授乳中、または妊娠の可能性がある方			
□医療、美容機関での注射前後１週間以内	
   (ニンニク注射や美容点滴)		
□感染症もしくは、感染症の疑いがある方			
□光線過敏症の方			
□ケロイドになりやすい方			
□過度な敏感肌の方			
□飲酒後の方や、飲酒のご予定のある方
   (お手入れの前後１２時間はお控えください)
□粘膜部位		□白髪部位
□予防接種、抜歯前後　2週間以内	
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 89, 53 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(110, 6, mb_convert_encoding( "
□お薬を服用中、またはぬり薬、湿布薬をご使用されている方
　 または、その直後1週間以内(※市販薬含む)
□白斑症もしくは、白斑症の疑いがある方
□皮膚トラブルで通院中の方や、皮膚疾患部位（傷、湿疹、腫れ物等）
□過度の日焼けをしている方、日焼けをされるご予定がある方
□ペースメーカーなどの医療用機器を使用されている方
□皮膚に何かが入っている方(金属プレート等・ボトックス・ヒアルロン酸・金の糸など)
□体調がすぐれない方（生理中,生理中のデリケートゾーン）
□ほくろ､アートメイクをされている部位､タトゥー及び､刺青をされている部位	
□ビタミンＡのサプリメントを多量に服用されている方、または、ゴマージュ剤 
□ピーリング系の化粧品を施術部位にご使用されている方
(施術前後3日は使用を中止して下さい。）
", "SJIS", "UTF-8" ) , 0, 'L', 0);



$pdf->SetXY( 17, 145 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "
	                             下記内容は、トリートメント期間中の注意事項になります。									
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFillColor(256, 256, 0);
$pdf->Rect( 21 ,157 ,103 ,4, 'F');
$pdf->Rect( 21 ,162 ,108 ,4, 'F');
$pdf->Rect( 21 ,167 ,74 ,4, 'F');
$pdf->Rect( 21 ,172 ,144 ,4, 'F');
$pdf->Rect( 23 ,177 ,98 ,4, 'F');
$pdf->Rect( 98 ,187 ,24 ,4, 'F');
$pdf->Rect( 21 ,192 ,53 ,4, 'F');
$pdf->Rect( 21 ,202 ,164 ,4, 'F');
$pdf->Rect( 21 ,212 ,111 ,4, 'F');

$pdf->SetXY( 17, 152 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(182, 5, mb_convert_encoding( "
□トリートメント2､3日前にトリートメント部位の剃毛を行ってください。(当日の剃毛はトラブルの原因になります。)
□施術の効果は個人差があり、目に見えてのご実感は3,4回目以降になります。
□トリートメント期間中は日焼けをしないでください。(期間中過度な日焼けはトラブルの原因になり､施術不可になります)
□トリートメント当日は、サウナ・スポーツ・飲酒などの体温上昇、発汗を促す行為は避けてください。	
　 (当日の湯船での入浴は避けていただき、シャワーのみにしてください。また、トリートメント部位はナイロンタオルの使
	 用も避けてください。)
□日のあたる箇所には、日焼け止めを塗ってください。(ＳＰＦ１５程度)
□日ごろから保湿ケアをしてください。(かゆみの防止、肌がやわらかくなり埋もれ毛も出やすくなり、脱毛効果が上がります)
□トリートメント期間中は、規則正しい生活を心がけ下さい。
□トリートメント期間中の自己処理は、毛抜きや、ワックス、脱色などでの処理は行わず、剃るのみにしてください。
    (効果を高めるため、できるだけ刺激にならないよう剃毛の回数を減らすことをおすすめしています。)
□施術後、赤みや・かゆみが生じた際には清潔な冷タオルで冷やしてください。
□自己判断せず、違和感がある場合や、不安な事がありましたら、必ず当サロンにご連絡ください。
								
注意事項において厳守することを約束し、肌状態など偽りなく担当者に申告し、その上で施術を行うことに同意します。
上記内容について、ご理解・ご承諾いただきましたら、誠に恐れいりますが、ご署名お願いいたします。

", "SJIS", "UTF-8" ) , 0, 'L', 0);




$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 20 , 250  , mb_convert_encoding( substr($_GET['hope_date'], 0,4)." 年 ".substr($_GET['hope_date'], 5,2)." 月 ".substr($_GET['hope_date'], 8,2)." 日", "SJIS", "UTF-8" ) );




$pdf->SetDrawColor(0,0,0);
$pdf->Rect(95 ,260 ,100 ,15);

$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 277  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "9/10" );

//10ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 42 , 24  , mb_convert_encoding( "エステティックサービス契約　ご契約内容チェックシート", "SJIS", "UTF-8" ) );

$pdf->SetXY( 17, 32 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "この｢ご契約内容チェックシート｣は、ご契約が、ご契約者様のご希望に沿った内容になっていること、お引き受けするご契約
の内容が適切であることをご契約者様に確認させていただくためのものです。
ご契約のプランの種類にしたがい、以下の該当箇所につきまして、ご確認の上、チェックを入れてください。

", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 50 );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 17 , 56  , mb_convert_encoding( "<月額制をお申込みのご契約者様>", "SJIS", "UTF-8" ) );

$pdf->SetFillColor(256, 256, 0);

$pdf->Rect( 61 ,63 ,37 ,4, 'F');
$pdf->Rect( 40 ,71 ,12 ,4, 'F');
$pdf->Rect( 46 ,79 ,129 ,4, 'F');
$pdf->Rect( 56 ,87 ,40 ,4, 'F');

$pdf->Rect( 61 ,141 ,37 ,4, 'F');
$pdf->Rect( 36 ,149 ,119 ,4, 'F');
$pdf->Rect( 62 ,165 ,30 ,4, 'F');
$pdf->Rect( 72 ,173 ,40 ,4, 'F');

$pdf->SetXY( 17, 53 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(160, 8, mb_convert_encoding( "
□　ご契約者様の生理期間中は肌トラブルが想定されるため、施術はお断りさせて頂いております。
□　月額制は、毎月1回店舗に通って施術をして頂くプランです。
□　月額制において当月ご来店されなかった場合には、当月分の施術は行われたものとして扱わせて頂きます。
□　一度ご予約いただいた施術予約は変更できません。
      施術予約のご変更の場合は、当月分の施術はなされたものと扱われ、予約はお1人様毎月1回までとさせて
      頂きます。
□　ご契約後3ヶ月目以降のお支払い方法は、銀行引落し又はクレジット決済のいずれかのみとなります。
□　弊社でシェービングをお手伝いする場合、シェービング代1000円を別途頂戴致します。
", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetXY( 17, 132 );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 17 , 134  , mb_convert_encoding( "<全身脱毛パックプランをお申し込みのご契約者様>", "SJIS", "UTF-8" ) );

$pdf->SetXY( 17, 131 );
$pdf->SetFont( KOZMIN,'' , 9 );

$pdf->MultiCell(180, 8, mb_convert_encoding( "
□　ご契約者様の生理期間中は肌トラブルが想定されるため、施術はお断りさせて頂いております。
□　ご予約の当日のキャンセルに関しては、当該1回分の施術が行われたものとして扱われます。
      前日20時までにKIREIMOコールセンター(0120-444-680)へご連絡頂ければ、予約変更が可能です。
□　クーリング・オフ期間は、ご契約日から8日以内です。
□　中途解約(クーリングオフ期間外・契約日から9日目以後の解約)は解約手数料を頂戴します。
　　残りの施術回数分の金額の10％(上限2万円)を解約手数料とさせて頂いております。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 192 );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 17 , 197  , mb_convert_encoding( "<ローンをお申込みのご契約者様>", "SJIS", "UTF-8" ) );

$pdf->SetXY( 17, 196 );
$pdf->SetFont( KOZMIN,'' , 9 );

$pdf->MultiCell(180, 8, mb_convert_encoding( "
□　ローンをお申込みの場合、別途分割手数料を頂戴します。
□　ローンをお申込みの場合、支払い方法、回数の変更は致しかねます。
□　ローン必要書類の口座振替用紙には、必ず銀行お届出印でご捺印の上、3日以内にポストへ投函をお願い致します。
□　ご指定頂いた日時に(株)CBSフィナンシャルサービスから契約内容、ご本人確認のお電話がございますので、ご対応お願
		 い致します。
", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 18 , 250  , mb_convert_encoding( substr($_GET['hope_date'], 0,4)." 年 ".substr($_GET['hope_date'], 5,2)." 月 ".substr($_GET['hope_date'], 8,2)." 日", "SJIS", "UTF-8" ) );
$pdf->Text( 18 , 255  , mb_convert_encoding( "上記内容を確認しました。", "SJIS", "UTF-8" ) );

$pdf->SetDrawColor(0,0,0);
$pdf->Rect(95 ,260 ,100 ,15);

$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 277  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "10/10" );



//$pdf->Output();
$pdf->Output($_GET['name'].$_GET['no'],"I");

?>
