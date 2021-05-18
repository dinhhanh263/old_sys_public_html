<?php

ini_set( 'include_path', dirname(__FILE__) . "/php-lib/fpdf" );
require_once( 'mbfpdf.php');
require_once( 'config/config.php');

header('Content-type: application/pdf');
header('Content-disposition: attachment; filename="'.$_GET['name'].'.pdf"');

$title = "";

$msg = "このたびはご契約頂いた".$_GET['course_name']."プランをご解約致しました。\nご確認下さいませ。\n宜しくお願い致します。";

$shop = "https://kireimo.jp\n\n株式会社ヴィエリス\nKIREIMO ".$_GET['shop_name']."\n〒160-0023\n東京都新宿区西新宿1-19-18\n新東京ビル5F\nTEL:03-6721-1641\nEmail:info@kireimo.jp";

$balance_name = $_GET['balance'] ? "残金" : "";
$_GET['shop_tel']="0120-444-680";

// 無料追加(全身1回)
if($_GET['option_name']==12) $option_name = "バースデーキャンペーン全身脱毛1回無料プレゼント※契約回数消化後使用可能";
// フリーチケット(全身1回)
elseif($_GET['option_name']==13) $option_name = "全身脱毛1回無料チケットプレゼント";
else $option_name = "";

if($_GET['option_name']){
	$option_times = 1;
	$option_price =0 ;
}else{
	$option_times = "";
	$option_price = "";
}

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
$pdf->Text( 17 , 24  , mb_convert_encoding( "整体用契約書整体用契約書整体用契約書この書面は、当サロンのサービス及び商品の内容をご理解いただくために、特定商取引法第42条に基づきお渡しするもので、契約書ではあ", "SJIS", "UTF-8" ) );
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
$pdf->Image("img/ckr.png",155,38,24); //横幅のみ指定,24
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
$pdf->Image("img/stamp.png",155,38,24); //横幅のみ指定,24
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
$pdf->Cell(64, 10, mb_convert_encoding( $_GET['course_name'], "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(10, 10, mb_convert_encoding( $_GET['times'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(15, 10, mb_convert_encoding( $_GET['length'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['per_fixed_price']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['fixed_price']), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['per_price']), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( number_format($_GET['fixed_price']-$_GET['discount']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->SetXY( 110, 120 );
$pdf->Cell(20, 5, mb_convert_encoding( "施術期間", "SJIS", "UTF-8" ), 1,0,"C");
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
$pdf->Cell(20, 5, mb_convert_encoding( "施術期間", "SJIS", "UTF-8" ), 1,0,"C");
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
$pdf->Cell(20, 5, mb_convert_encoding( "施術期間", "SJIS", "UTF-8" ), 1,0,"C");
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
$pdf->Cell(25, 5, mb_convert_encoding( "特典価格（円）", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 170 );
$pdf->Cell(6, 5, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( $option_name, "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( $option_times, "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( $option_price, "SJIS", "UTF-8" ), 1,0,"C");
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

$pdf->SetFillColor(238, 233, 233);
$pdf->Rect( 120 ,266 ,30 ,10, 'DF');
$pdf->SetXY( 120, 266);
$pdf->Cell(30, 10, mb_convert_encoding( "総支払合計", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(45, 10, mb_convert_encoding( number_format($_GET['fixed_price']-$_GET['discount'])."円", "SJIS", "UTF-8" ), 1,0,"R");
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "1/24" );

//2ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 16 , 20  , mb_convert_encoding( "■備考", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 22);
// 新月額のみ、下記の文言を備考に入れる ※判定は admin/library/reservation/edit.php で行っています。
if($_GET['course_type']){
	$pdf->MultiCell(180, 7, mb_convert_encoding( "・月額プランは契約終了月の末日の2ヶ月前までに当社に申し出がない場合、契約期間は更に2ヶ月間更新し、
	それ以降も同様です。
	", "SJIS", "UTF-8" ), 1,"T");
} else {
	$pdf->MultiCell(180, 60, mb_convert_encoding( $_GET['memo'], "SJIS", "UTF-8" ), 1,"T");
}
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
$pdf->SetXY( 15, 125 );
$pdf->SetFillColor(238, 233, 233);
$pdf->Rect( 15 ,120 ,135 ,5, 'DF');

$pdf->Cell(135, 5, mb_convert_encoding( "合計", "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['payment_cash']+$_GET['payment_card']+$_GET['payment_transfer']+$_GET['payment_loan']+$_GET['payment_coupon']+$_GET['balance']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( "-", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 130 );
}else{
$pdf->Cell(6, 5, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( $balance_name, "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( ($_GET['balance'] ? number_format($_GET['balance']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 120 );
$pdf->SetFillColor(238, 233, 233);
$pdf->Rect( 15 ,120 ,135 ,5, 'DF');

$pdf->Cell(135, 5, mb_convert_encoding( "合計", "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['payment_cash']+$_GET['payment_card']+$_GET['payment_transfer']+$_GET['payment_loan']+$_GET['payment_coupon']+$_GET['balance']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( "-", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 125 );
}
$pdf->Cell(180, 5, mb_convert_encoding( "前払い金の保全処置：なし", "SJIS", "UTF-8" ), 1,0,"L");

//$pdf->SetFont( KOZMIN,'' , 7 );
//$pdf->Text( 17 , 158  , mb_convert_encoding( "※クレジットご利用の場合、抗弁権の接続ができます。詳細については契約書を良くお読み下さい。", "SJIS", "UTF-8" ) );
//$pdf->Text( 17 , 162  , mb_convert_encoding( "前受け金の保全措置はありません。", "SJIS", "UTF-8" ) );
//$pdf->SetFont( KOZMIN,'' , 9 );
//$pdf->Text( 17 , 170  , mb_convert_encoding( "3.特約事項：なし", "SJIS", "UTF-8" ) );
//$pdf->Text( 17 , 180  , mb_convert_encoding( "4.契約の解除に関する事項：クーリング・オフ並びに中途解約につきましては、概要書面の該当欄をご確認ください。", "SJIS", "UTF-8" ) );

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
$pdf->Text( 100 , 285  , "2/24" );

//3ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

//$pdf->SetFillColor(256, 256, 0);
//$pdf->Rect( 23 ,181 ,15 ,4, 'F');

$pdf->SetFont( KOZMIN,'' , 12 );
$pdf->SetTextColor(255, 0, 0);
$pdf->Text( 17 , 20  , mb_convert_encoding( "<クーリング・オフについて>", "SJIS", "UTF-8" ) );

$pdf->SetDrawColor(255,0,0);
$pdf->Rect(15 ,15 ,180 ,57);

$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Text( 17 , 25  , mb_convert_encoding( "■お客様は、締結した契約書面を受領した日から起算して8日以内であれば、書面により、関連商品を含めその契約を解除（クーリング・", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 30  , mb_convert_encoding( "オフ）できます。また、お客様が、クーリング・オフに関し当社から不実のことを告げられ誤認し又は威迫により困惑し、クーリング・オ", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 35  , mb_convert_encoding( "フを行わなかった場合には、当該期間経過後も書面によりその契約をクーリング・オフすることができます。但し、関連商品において、そ", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 40  , mb_convert_encoding( "の全部若しくは一部を開封したり使用したりしたときは、その対象ではございませんが、当社がお客様に商品を使用させ、また消費させた", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 45  , mb_convert_encoding( "場合はこの限りではありません。", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 50  , mb_convert_encoding( "■クーリング・オフは、書面を当社宛に発信したときにその効力が生じます。", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 55  , mb_convert_encoding( "■クーリング・オフに伴う損害賠償、違約金、本契約役務ご利用代金の支払い請求はいたしません。", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 60  , mb_convert_encoding( "■（クーリング・オフ対象）関連商品の引渡しが既にされているとき、その返還に要する費用は、当社が負担します。", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 65  , mb_convert_encoding( "当社が既に現金支払い・金融機関口座引き落とし・クレジットカード払い等にてお客様より受領した代金は（クーリング・オフ対象外の関", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 70  , mb_convert_encoding( "連商品代金は除く）速やかにお客様が指定した口座へ振込みにて返還致します。（振込み手数料は、当社が負担致します。）", "SJIS", "UTF-8" ) );


$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 82 , mb_convert_encoding( "< 中 途 解 約 >", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 85 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 4, mb_convert_encoding( "クーリング・オフ期間を過ぎた場合でも、当社所定の手続きにより、契約期間中の解約（中途解約）をすることができます。	但し関連商品のみの解約はできません。
中途解約時の返金額等の算出方法としては、プラン1回あたりの料金である補正単価（契約金額を契約回数で除した結果が割り切れない場合1円未満を四捨五入）に利用回数を乗じた金額を消化額とし、支払総額から消化額を引いた金額を残金とします。
解約手数料金額として残金の10%（最大￥20,000）を差し引き、精算金を算出いたします。
以下に、残金・解約手数料・精算金の算出方法を記載いたします。

    残　　　　　金   =　支払総額 - ( 1回あたりの料金 × 利用回数 )
    解  約  手  数  料   = 残金 ×10% （最大￥20,000）
    精　　算　　金　=  残金  -  解約手数料

", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetTextColor(257, 0, 0);
$pdf->SetXY( 19, 125 );
$pdf->SetFont( KOZMIN,'' , 8.5 );
$pdf->MultiCell(180, 4, mb_convert_encoding( "
※複数箇所でコース1回分とみなす契約（キレイモ全身脱毛【1年・2年・スペシャル】プラン、平日とく得【1年・2年・スペシャル】プラン、月額プラン、キャンペーン時におけるセットのコース）につきましてはコースに含まれる箇所を1箇所でもお手入れした場合、コース1回分を消化したとみなし、解約時の返金対象とはなりません。（例）キレイモ全身脱毛1年プラン（指定回数8回）をご契約時、全身1回のみお手入れ後、中途解約を希望した場合の返金対象は、全身7回分となります。
※現金の受け渡しによる返金は行っておりません。お客様の金融機関口座への振込とさせて頂きます。なお、振込にかかる手数料は、お客様負担となります。また、返金額が振込手数料額以下の場合、返金は行わず、また、振込手数料の請求も行いません。", "SJIS", "UTF-8" ) , 0, 'L', 0);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY( 15, 154 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "以下のいずれかに該当する場合は、当社より契約の解除をさせていただく場合がございます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 19, 159 );
$pdf->SetFont( KOZMIN,'' , 8.5 );
$pdf->MultiCell(174, 4, mb_convert_encoding( "※複数回連続で月々のお支払いが確認できない場合。（月額プラン）
※本サービス料金の全額が、契約日より起算して90日以内にお支払いいただけない場合。（キレイモ全身脱毛【1年・2年・スペシャル】プラン、平日とく得【1年・2年・スペシャル】プラン）
※お客様の体質等に起因して、お手入れの継続が困難だと当社が判断した時。
※お客様との信頼関係の維持が困難と判断した時。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "3/24" );

//4ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

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
$pdf->Cell(15, 5, mb_convert_encoding( "1回のお手入", "SJIS", "UTF-8" ), "LTR",0,"L");
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Cell(40, 5, mb_convert_encoding( "定価", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(45, 5, mb_convert_encoding( "割引後金額", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 95, 85 );
$pdf->SetFont( KOZMIN,'' , 7 );
$pdf->Cell(15, 5, mb_convert_encoding( "れ時間(分)", "SJIS", "UTF-8" ), "LRB",0,"C");
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
$pdf->Cell(20, 5, mb_convert_encoding( "施術期間", "SJIS", "UTF-8" ), 1,0,"C");
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
$pdf->Cell(20, 5, mb_convert_encoding( "施術期間", "SJIS", "UTF-8" ), 1,0,"C");
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
$pdf->Cell(20, 5, mb_convert_encoding( "施術期間", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(65, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetFont( KOZMIN,'' , 7 );
$pdf->Text( 17 , 124  , mb_convert_encoding( "※1回当たりのお手入れ時間は、目安となります。※コース金額につきましては、概要書面発行日当日にご契約いただいた場合の金額となります。", "SJIS", "UTF-8" ) );
// $pdf->Text( 17 , 128  , mb_convert_encoding( "※単価は18回を想定しての目安となります。", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 16 , 138  , mb_convert_encoding( "■特典明細", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 140 );
$pdf->Cell(135, 5, mb_convert_encoding( "特典明細", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "回数", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "特典価格（円）", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 145 );
$pdf->Cell(6, 5, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( $option_name, "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( $option_times, "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( $option_price, "SJIS", "UTF-8" ), 1,0,"C");
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

$pdf->SetXY( 120, 250);
$pdf->SetFillColor(238, 233, 233);
$pdf->Rect( 120 ,250 ,30 ,10, 'DF');
$pdf->Cell(30, 10, mb_convert_encoding( "総支払合計", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(45, 10, mb_convert_encoding( number_format($_GET['fixed_price']-$_GET['discount'])." 円", "SJIS", "UTF-8" ) , 1,0,"R");
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "4/24" );

//5ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 16 , 20  , mb_convert_encoding( "■備考", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 22);
// 新月額のみ、下記の文言を備考に入れる ※判定は admin/library/reservation/edit.php で行っています。
if($_GET['course_type']){
	$pdf->MultiCell(180, 7, mb_convert_encoding( "・月額プランは契約終了月の末日の2ヶ月前までに当社に申し出がない場合、契約期間は更に2ヶ月間更新し、
	それ以降も同様です。
	", "SJIS", "UTF-8" ), 1,"T");
} else {
	$pdf->MultiCell(180, 60, mb_convert_encoding( $_GET['memo'], "SJIS", "UTF-8" ), 1,"T");
}
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
    $pdf->SetXY( 15, 125 );
    $pdf->SetFillColor(238, 233, 233);
    $pdf->Rect( 15 ,120 ,135 ,5, 'DF');

    $pdf->Cell(135, 5, mb_convert_encoding( "合計", "SJIS", "UTF-8" ), 1,0,"R");
    $pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['payment_cash']+$_GET['payment_card']+$_GET['payment_transfer']+$_GET['payment_loan']+$_GET['payment_coupon']+$_GET['balance']), "SJIS", "UTF-8" ), 1,0,"R");
    $pdf->Cell(25, 5, mb_convert_encoding( "-", "SJIS", "UTF-8" ), 1,0,"C");
    $pdf->SetXY( 15, 130 );
}else{
    $pdf->Cell(6, 5, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
    $pdf->Cell(129, 5, mb_convert_encoding( $balance_name, "SJIS", "UTF-8" ), 1,0,"L");
    $pdf->Cell(20, 5, mb_convert_encoding( ($_GET['balance'] ? number_format($_GET['balance']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
    $pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
    $pdf->SetXY( 15, 120 );
    $pdf->SetFillColor(238, 233, 233);
    $pdf->Rect( 15 ,120 ,135 ,5, 'DF');

    $pdf->Cell(135, 5, mb_convert_encoding( "合計", "SJIS", "UTF-8" ), 1,0,"R");
    $pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['payment_cash']+$_GET['payment_card']+$_GET['payment_transfer']+$_GET['payment_loan']+$_GET['payment_coupon']+$_GET['balance']), "SJIS", "UTF-8" ), 1,0,"R");
    $pdf->Cell(25, 5, mb_convert_encoding( "-", "SJIS", "UTF-8" ), 1,0,"C");
    $pdf->SetXY( 15, 125 );
}
$pdf->Cell(180, 5, mb_convert_encoding( "前払い金の保全処置：なし", "SJIS", "UTF-8" ), 1,0,"L");

$pdf->Rect(95 ,160 ,100 ,15);
$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 177  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 17 , 190  , mb_convert_encoding( "上記内容を確かに受け取りました。", "SJIS", "UTF-8" ) );
if($_GET['shop_id']==6 && $_GET['hope_date']<'2015-01-04'){
$pdf->Image("img/ckr.png",155,200,24); //横幅のみ指定,24
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Text( 107 , 208  , mb_convert_encoding( "会  社  名", "SJIS", "UTF-8" ) );
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
$pdf->Image("img/stamp.png",155,200,24); //横幅のみ指定,24
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
}
$pdf->Text( 107 , 227  , mb_convert_encoding( "サロン名", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 122 , 227  , mb_convert_encoding( "KIREIMO", "SJIS", "UTF-8" ) );
$pdf->Image("logo.png",20,230,40); //横幅のみ指定,24
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Text( 106 , 235  , mb_convert_encoding( "店  舗  名： ".$_GET['shop_name']."                                                              ", "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 240  , mb_convert_encoding( "店舗住所： ".$_GET['shop_address'], "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 245  , mb_convert_encoding( "電話番号： ".$_GET['shop_tel']."                                               ", "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 250  , mb_convert_encoding( "担 当  者： ".$_GET['staff']."                                                         ", "SJIS-win", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "5/24" );

//6ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 73 , 10  , mb_convert_encoding( "エステティックサービス約款", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 14 , mb_convert_encoding( "第1条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 11.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(本約款の適用範囲）
株式会社ヴィエリス（以下「当社」といいます）は、エステティックサービス約款（以下「本約款」といいます）に基づき、エステティックサービス（以下「本サービス」といいます）を提供するものとします。
2.	当社が本約款以外に定める「概要書面（事前説明書）」、「エステティックサービス契約書」、「KIREIMOのご案内」、「エステティックサービス契約　ご契約内容チェックシート」、「除毛・減毛トリートメント同意書」およびその他、当社が定めるもの（以下これらを総称して「個別約款」といいます）は、本約款の一部を構成するものとし、本約款と個別約款の定めが異なる場合、別段の定めがない限り、個別約款の定めが優先して適用されるものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);
$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 37 , mb_convert_encoding( "第2条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 34.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(契約の成立)
お客様が当社の定めるエステティックサービス契約書（以下「本契約書」といいます）の記載内容、本約款および個別約款に承諾の上、本サービスにお申し込みをし、当社がこれを承諾したことによって、エステティックサービス契約（以下「本契約」といいます）が成立いたします。
2.	お客様が未成年の場合、本契約の成立には前項の手続きに加え、当社所定の書式よる親権者の承諾が必要となります。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 53.5  , mb_convert_encoding( "第3条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 51 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.5, mb_convert_encoding( "(役務内容)
当社はお客様に対し、本契約書に記載する各プラン、およびプランごとに指定された回数の本サービスを提供するものとします。なお、プランの概要は以下のとおりとします。
（1）	月額プランとは、当社の定める2ヶ月の期間内に1度（以下「当該期間」といいます）、本サービスの提供を受けることができるコースです。
（2）	パックプランとは、本契約書に定める契約期間（返金保証期間と同義）中に指定する回数の本サービスの提供を受けることができるコースです。
（3）	スペシャルプランとは、パックプランの一種であり、本契約書に定める契約期間中に指定回数の本サービスの提供を受けられることに加え、契約期間終了後も、当社の定める条件の下で引き続き本サービスの提供を受けることができるコースです。
", "SJIS", "UTF-8" ) , 0, 'L', 0);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY( 25, 82.5 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "
2.	当社はお客様に対し、新たなコースや付加的なオプションを提供することがあります。その詳細は本約款または個別約款に定め、お客様へ説明を行うものとします。
3.	本サービスの提供を受けるために予約が必要となりますので、当社所定の方法により予約手続きをしていただきます。なお、ご希望の予約日が月末、繁忙期と重なる場合、ご希望する日時の予約が取れず、前項第1号の当該期間内に本サービスを提供ができない場合がございますので、予めご了承ください。
4.	また、ご契約プランの内容や役務消化の進捗状況等に応じて、予約取得の周期、予約可能な曜日または時間帯等に制限のある場合がありますので、予めご了承ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 111  , mb_convert_encoding( "第4条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 108.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(本サービスの料金、支払時期、支払方法)
お客様に提供する本サービスの料金、および販売する関連商品等の料金は本契約書に明記します。
2.	本サービスの料金の支払方法は以下のとおりといたします。
（1）	月額プランは、1回目の本サービス料金を本契約の契約日にお支払いいただきます。2回目以降のお支払いは、契約時に当社所定の継続手続きを行っていただき、契約月の翌月より毎月クレジットカード決済もしくは金融機関口座振替払いのいずれかになります。
（2）	パックプラン（スペシャルプランを含みます）は、現金払い、クレジットカード払い、当社指定金融機関への振込、もしくは当社と提携するローン会社の立替払いをご選択いただけます。また、複数の支払方法を併用することも可能です。
3.	前項第１号に定める支払方法のうち、金融機関口座振替払いの方で金融機関の決済が取れなかった場合、その月末までにお支払いがない限り、すでに予約されている次回以降の予約が取り消しとなります。
4.	お客様は、原則として契約の締結日に本サービス料金総額のうちの一部を手付金として納付するものとし、残額は本契約の契約日より90日以内に支払うものとします。
5.	契約日から起算して90日以内にサービス料金の総額を当社が領収できなかった場合、お客様は本契約を解約する意思表示を示したものとし、当社はこれを受け、本契約を解約いたします。また、この場合、お客様が納付した手付金の返還を放棄したものとみなします。
また、一度こちらの解約が成立した場合、お客様がお支払いされた手付金を充当して元のプランに復帰するなどの対応は一切いたしかねますのでご注意ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 159.5  , mb_convert_encoding( "第5条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 157 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.5, mb_convert_encoding( "(本サービスの提供期間および期間終了後の措置)
本サービスの提供期間は、契約書に記載された契約期間とします。なお、月額プランは、最終施術希望期間開始月の前月末日までに、当社へ契約終了の申し出がない場合、本契約は更に2ヶ月間更新し、以後も同様とします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY( 25, 164 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "
2.	パックプランにおいて、契約期間内に指定回数の本サービスが全て受けられなかった場合の措置として、契約期間終了日より契約期間と同等の期間（以下「保証延長期間」といいます）、残回数分の本サービスの提供を受けることができます。なお、保証延長期間中に解約される場合は、第8条の定めに従うものとし、契約期間満了時に未消化の役務については、返金の対象外となるとともに、保証延長期間中に当社が行なった施術に対する返金はいたしかねます。
3.	原則として、保証延長期間の延長（再延長）は致しかねますので、予めご了承ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 186 , mb_convert_encoding( "第6条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 183.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(契約の変更・追加)
お客様は、当社の定める条件の下で、お申出により契約内容を変更すること、もしくは新たな契約を追加することができます。
但し、ご契約中のプランの種類や役務消化の進捗、お客様の状況（年齢・お支払状況等）により、ご希望に沿えない場合がございますので、予めご了承ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetDrawColor(255,0,0);
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->SetTextColor(255, 0, 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 199.5  , mb_convert_encoding( "第7条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 196.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(クーリング・オフ)
お客様は、契約書面を受領した日から起算して8日間以内であれば、書面により本契約を解除することができます。
2. 当社がお客様に不実のことを告げ、または威迫等によりクーリング・オフが妨害された場合、当社は改めてクーリング・オフができる旨を記載した書面を受領し、当社より説明を受けた日から起算して8日間以内であれば、書面によるクーリング・オフをすることができます。
3. 前二項に基づく解除がなされた場合、関連商品販売契約についても、その契約を解除することができます。但し、関連商品を開封したり、その全部もしくは一部を消費したりした場合、当該商品に限りクーリング・オフすることはできません。
関連商品の引き渡しが既に行われている場合は、当該関連商品の引き取りに要する費用は当社の負担とします。
4. クーリング・オフは、お客様がクーリング・オフの書面を当社宛てに発信した時に、その効力が生じます。クレジットを利用した契約の場合、お客様は当社に契約の解除を申し出た旨をクレジット会社にも別途書面による通知をしていただく必要がございます。
5. 本条による契約解除については、違約金及び利用した本サービスの料金の支払いは不要とし、当社はお客様から現金一括払い・クレジットカード決済・金融機関口座振替等により受領した前受金及び関連商品販売に関し金銭を受領している場合には、
当該金銭につき速やかにお客様の金融機関口座に振り込みにより返還するものとします。なお、当該金銭を返還する際の費用は当社の負担とします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "6/24" );

//7ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetXY( 26, 15 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->SetTextColor(255, 0, 0);
$pdf->MultiCell(162, 3.2, mb_convert_encoding( "
  	                                                        		クーリング・オフ(契約解除)の文例
西暦○○○○年○月○日、貴社〇〇店との間で締結したエステティックサービス契約について、約款第7条に基づき契約を解除します。つきましては、私が貴社に支払った代金○○○円を下記銀行口座に振り込んでください。
（また、私が受け取った商品をお引き取りください）

○○銀行○○支店　普通預金口座○○○○　口座名義人　○○○○

西暦○○○○年〇月〇日

契約者(住所)           
　　　(氏名)　　　　　　　　　    　印
						
".
    ($_GET['shop_id']==6 && $_GET['hope_date']<'2015-01-04' ? "株式会社CKR　代表者　大澤　美加" : "株式会社ヴィエリス　代表者　".$kireimo_ceo)."殿

", "SJIS", "UTF-8" ) , 1, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->SetTextColor(0, 0, 0);
$pdf->Text( 15 , 68 , mb_convert_encoding( "第8条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 65.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(中途解約・返品）
本契約は、クーリング・オフ期間を過ぎても、関連商品を含め契約を以下に定める方法により中途解約をすることができます。
（1）	月額プランの場合、最終施術希望期間開始月の前月末日までに、当社所定の方法により解約手続きを行うものとします。
なお、金融機関の都合により解約の申し出時点でクレジット決済または銀行口座振替の中止ができない場合がございますので、その際は当該金額を金融機関より受領後、すみやかに全額返金します。なお、返金は金融機関口座への振込とし、それにかかる手数料は、お客様負担となります。
（2）	パックプランの場合、原則として、契約期間内に、当社所定の方法により解約手続きを行うものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY( 25, 84.5 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "
2.	パックプランをご契約のお客様が、契約期間中に本契約を中途解約した場合、解約手数料として本サービスの未消化分額の10％(契約金額を契約回数で除した結果が割り切れない場合1円未満を四捨五入)をお支払いいただきます。但し、解約手数料の上限額は2万円とします。
3.	パックプランをご契約のお客様が、契約期間終了後、延長保証期間中の解約（未消化分の役務提供を受ける権利の放棄）を行なった場合、契約期間満了時に未消化の役務については、返金の対象外となります。 
4.	中途解約により当社より返金がある場合、本サービスの未消化分の金額より、前項により算出した解約手数料を差し引いた金額を返金いたします。なお、返金方法は、金融機関口座への振込払いになり、振込にかかる手数料は、お客様負担となります。なお、返金金額が振込手数料額以下の場合、返金は行わず、また振込手数料も請求しないものといたします。
5.	関連商品は、当該商品を開封したり、その全部もしくは一部を消費したりした場合は、返品できないものとします。但し、未使用の場合であっても、保存方法により著しく商品価値が損なわれている場合は、返品不可となります。なお、返品にあたっての返送費用およびお客様へ返金がある場合、返金方法は金融機関口座への振込払いとし、それにかかる手数料は、お客様の負担とします。
6.	お支払い方法がクレジットカード払いの場合、本条における返金方法は金融機関口座への振込払いとなり、それにかかる手数料は、お客様の負担とします。なお、返金金額が振込手数料額以下の場合、返金は行わず、また振込手数料も請求しないものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 129 , mb_convert_encoding( "第9条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 127 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(契約解除)
当社は、お客様が以下のいずれかに該当した場合には、何らの催告なしに本契約を解除することができるものとします。
（1）	本契約に違反し、当社より催告されたにも関わらず、是正されていないと判断された場合
（2）	本契約における代金の支払いが複数回にわたり遅滞した場合
（3）	差押え、仮差押え、仮処分その他の強制執行または滞納処分の申し立てを受けた場合
（4）	破産手続、民事再生手続、会社更生手続等の開始申立を受け、若しくは自らこれらの申立をなしたとき
（5）	お客様の体質的に起因して、本サービスの提供の継続が困難だと判断した場合
（6）	お客様の信用状態に重大な変化が生じた場合
", "SJIS", "UTF-8" ) , 0, 'L', 0);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY( 25, 149.5 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "
2.	前項に基づき当社が本契約を解除したことにより、お客様に生じた不利益、損害について当社は一切の責任を負わないものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 159.5 , mb_convert_encoding( "第10条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 157 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.5, mb_convert_encoding( "(施術上の注意)
当社は、お客様に本サービスを提供するにあたり、事前にお客様の体質（治療中の皮膚疾患・アレルギー・敏感肌・薬の服用の有無など）および体調を聴取し、確認するものとします。お客様の体調・体質により、お客様への本サービスの提供をお断りする場合があります。
2.	本サービス提供期間中、お客様が体調を崩したり、施術部位に異常が生じたりした場合、お客様はその旨を当社に伝えるものとします。この場合、当社は直ちに役務を中止します。その原因が当社の施術に起因する疑いがある場合は、一旦当社の負担で、お客様に医師の診断を受けて頂く等の適切な処置を取ることとし、当事者間の協議の上解決するものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 180.5  , mb_convert_encoding( "第11条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 178.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(抗弁権の接続)
お客様は、信販を利用して支払う場合、割賦販売法により、当社との間で生じている事由をもって、信販会社からの請求を拒否することができます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 191  , mb_convert_encoding( "第12条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 188.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.5, mb_convert_encoding( "(別途協議)
本約款に定める事項に疑義が生じた場合もしくは本約款に定めのない事項が生じた場合は、本契約当事者間にて誠意をもってこれを協議の上、解決するものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 202 , mb_convert_encoding( "第13条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 200 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(174, 3.2, mb_convert_encoding( "(個人情報の取り扱いについて)
本約款に基づき取得した個人情報は、本サービスを提供するために利用し、お客様本人の承諾なく第三者に開示、提供を行わないこととします。
2.	当社は、個人情報の保護に関する法律、関係各庁が定めるガイドラインならびに各種プライバシーに関する法令を遵守するものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 218.5 , mb_convert_encoding( "第14条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 216.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(174, 3.2, mb_convert_encoding( "(約款の改訂)
当社は、お客様の承諾を得ることなく、本約款を変更することができるものとし、当社およびお客様は、変更後の本約款に拘束されるものとします。
なお、変更後の約款に承諾できない場合は、第8条に基づき、解約手続きを行うものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 232.5 , mb_convert_encoding( "第15条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 230.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(174, 3.2, mb_convert_encoding( "(管轄裁判所)
本約款に起因した紛争の解決については、東京地方裁判所を第一審の専属的管轄裁判所とします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "7/24" );

//8ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 50 , 20  , mb_convert_encoding( "KIREIMOのご案内（月額プランをご希望のお客様）", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 25 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "月額プランとは、当社が提供する以下のプランを指します。
　・キレイモ全身脱毛月額定額制プラン（以下、「月額定額制プラン」といいます）
　・U-19応援プラン
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'U' , 10 );
$pdf->Text( 15 , 48  , mb_convert_encoding( "1．月額定額制プランについて", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 45 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "　
・本プランは、当社の指定する2ヶ月の期間内（以下、「指定期間」といいます）に1回、施術をさせていただく
　プランとなっております。なお、毎回の施術後に次回の予約をしていただきます。
・初回の施術は、原則として契約月の翌月より提供を開始いたしますので、契約月の翌月1日～翌々月末日までの
　間でご都合の良い日をご予約ください。
・ご予約時間に遅刻された場合はできる限り施術をさせていただきますが、できなかった箇所については消化扱い
　となりますのでご了承ください。
・予約日は、原則として当社の定める2ヶ月の期間内に指定していただき、予約日の3日前の20時まで（マイページ
　からは23時59分まで）にご連絡いただければ、当該期間内であれば、何度でも予約の変更が可能です。
・予約変更可能期間を過ぎたキャンセル、および事前のご連絡を頂けず当日にご来店がなかった場合
　（無断キャンセル）、原則として（※）1回分を消化させていただきます。
　※例外的な場合については「4.施術に関して (補足)」をご覧ください。
・シェービングサービスは行っておりません。 お客様自身で手の届きにくい、背中、うなじ、Oライン、ヒップ
　のみ補助を行います。その他の箇所はご予約の前日にお客様自身でシェービングをしていただくようお願い
　いたします。
　※剃り残しがあった部位は当日の施術をお断りさせていただきますのでご了承ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 15, 145 );
$pdf->MultiCell(180, 6, mb_convert_encoding( " 【月額定額制プランの契約期間について（ご参考）】
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetLineWidth(0);
$pdf->SetDrawColor(0,0,0);
// $pdf->Rect(15 ,222 ,0 ,5); // 左から数えて縦線1
$pdf->Rect(15 ,165 ,0 ,5); // 左から数えて縦線
$pdf->Rect(40 ,161 ,70 ,7);// 左から数えて上の囲み1
$pdf->Rect(40 ,165 ,0 ,5); // 左から数えて縦線2

$pdf->Rect(110 ,161 ,70 ,7);// 左から数えて上の囲み2
$pdf->Rect(110 ,165 ,0 ,5); // 左から数えて縦線3

$pdf->Rect(180 ,161 ,20 ,0);// 左から数えて上の囲み3
$pdf->Rect(180 ,165 ,0 ,5); // 左から数えて縦線4

$pdf->Rect(15 ,168 ,185 ,0); //基準となる横線


$pdf->SetFont( KOZMIN,'' , 10 );

$pdf->Text( 70 , 160  , mb_convert_encoding( "2ヶ月", "SJIS", "UTF-8" ) );
$pdf->Text( 140 , 160  , mb_convert_encoding( "2ヶ月", "SJIS", "UTF-8" ) );

$pdf->Text( 130 , 166  , mb_convert_encoding( "※契約期間更新", "SJIS", "UTF-8" ) );

$pdf->Text( 13 , 174  , mb_convert_encoding( "契約日", "SJIS", "UTF-8" ) );
$pdf->Text( 33 , 160  , mb_convert_encoding( "翌月1日", "SJIS", "UTF-8" ) );
$pdf->Text( 103 , 174  , mb_convert_encoding( "1.契約終了日", "SJIS", "UTF-8" ) );
$pdf->Text( 173 , 174  , mb_convert_encoding( "2.契約終了日", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 180 );
$pdf->MultiCell(180, 6, mb_convert_encoding( " 【お支払いについて】
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 180 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "　
・月額定額制プランは本契約日に、原則として施術1回分の料金を現金またはクレジットカードでお支払いいただ
　き、併せて2回目以降の施術料金について引き落としの手続きをさせていただきます。
・2回目以降の料金は毎月、クレジットカード決済もしくは銀行引き落としとさせて頂きます。但し、決済日
　または引き落とし日に当社へのご入金が確認できなかった場合、コンビニエンスストア等でのお支払いをお願い
　することがございます。
・万が一、複数回連続でお支払いが確認できない場合には、お客様の意思を確認することなく退会（解約）手続き
　をさせていただく場合がございます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 15, 235 );
$pdf->MultiCell(180, 6, mb_convert_encoding( " 【退会（解約）手続きについて】
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 235 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "　
・退会（解約）を希望される場合は、契約終了月の2ヶ月前の末日までにKIREIMOコールセンター(0120-444-680)
　へお電話でご連絡ください。退会（解約）の手続きをご案内させていただきます。お客様の解約申告が遅れた
　場合、希望期間内に退会（解約）ができない可能性がございますのでご注意ください。
・月額定額制プランを1度退会（解約）された場合、1回に限り再契約が可能です。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 243 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "8/24" );

//9ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'U' , 10 );
$pdf->Text( 15 , 20  , mb_convert_encoding( "2．U-19応援プランについて", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 17 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "　
・ご契約時に未成年のお客様（満20歳未満、且つご契約日から満20歳のお誕生日まで３ヶ月超の期間のある方）
　は、1.の通常の月額プランよりお得な限定プラン（U-19応援プラン）がご利用いただけます。
・ご契約日から３ヶ月以内に満20歳のお誕生日を迎えるお客様はU-19応援プランをお申込みいただけません。
・U-19応援プランのご契約に際しては、親権者の承諾が必要になります。
・また、お客様の生年月日、および未成年であることを証明する資料の確認をさせていただきます。
・U-19応援プランをご契約のお客様が満20歳を迎える月の末日もしくは翌月の末日を基準日とし、基準日以降
　に開始される指定期間から通常の月額定額制プランに変更されます。月額プランは前払い制のため、
　移行後の最初の契約（月額定額制プラン）に対するお支払いが基準日より前となる場合があります。
・上記に定める事項を除く事項については、月額定額制プランに準じます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->Text( 15 , 85  , mb_convert_encoding( " 【U-19応援プランから月額定額制プランへの移行例】", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'U' , 10 );
$pdf->Text( 15 , 92 , mb_convert_encoding( "例[1]）2021年5月に満20歳となるお客様が、2020年2月から契約を開始した場合", "SJIS", "UTF-8" ) );
$pdf->SetXY( 15, 95 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding("・2021年6月から通常の月額定額制プランが適用されます。
　・移行した月額定額制プランに対するお支払（※）は、2021年の4月〜5月頃となります。
　（※銀行口座からの引き落としをご利用の場合など、特にご注意ください。）
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetLineWidth(0);
$pdf->SetXY( 15, 115 );
$pdf->Cell(50, 5, mb_convert_encoding( "U-19[1]施術", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(50, 5, mb_convert_encoding( "U-19[2]施術", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(50, 5, mb_convert_encoding( "月額定額制[1]施術", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 120 );
$pdf->Cell(25, 10, mb_convert_encoding(  "2021年2月", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 10, mb_convert_encoding( "3月", "SJIS", "UTF-8" ), LTR,0,"C");
$pdf->Cell(25, 10, mb_convert_encoding( "4月", "SJIS", "UTF-8" ), LTR,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "5月", "SJIS", "UTF-8" ), LTR,0,"C");
$pdf->Cell(25, 10, mb_convert_encoding( "6月", "SJIS", "UTF-8" ), LTR,0,"C");
$pdf->Cell(25, 10, mb_convert_encoding( "7月", "SJIS", "UTF-8" ), LTR,0,"C");
$pdf->SetXY( 90, 125 );
$pdf->Cell(25, 5, mb_convert_encoding( "(お誕生月)", "SJIS", "UTF-8" ), LR,0,"C");
$pdf->SetXY( 15, 130 );
$pdf->Cell(50, 10, mb_convert_encoding( "U-19[2]分お支払い", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(50, 10, mb_convert_encoding( "月額定額制[1]分お支払い", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(50, 10, mb_convert_encoding( "月額定額制[2]分お支払い", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 90, 140 );
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), R,0,"C");
$pdf->SetXY( 105, 145 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "基準日（5/31）", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetFont( KOZMIN,'U' , 10 );
$pdf->Text( 15 , 160 , mb_convert_encoding( "例[2]）2021年5月に満20歳となるお客様が、2020年3月から契約を開始した場合", "SJIS", "UTF-8" ) );
$pdf->SetXY( 15, 163 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding("・2021年7月から通常の月額定額制プランが適用されます。
・移行した月額定額制プランに対するお支払は、2021年の5月〜6月頃となります。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetLineWidth(0);
$pdf->SetXY( 15, 180 );
$pdf->Cell(50, 5, mb_convert_encoding( "U-19[1]施術", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(50, 5, mb_convert_encoding( "U-19[2]施術", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(50, 5, mb_convert_encoding( "月額定額制[1]施術", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 185 );
$pdf->Cell(25, 10, mb_convert_encoding(  "2021年3月", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 10, mb_convert_encoding( "4月", "SJIS", "UTF-8" ), LTR,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "5月", "SJIS", "UTF-8" ), LTR,0,"C");
$pdf->Cell(25, 10, mb_convert_encoding( "6月", "SJIS", "UTF-8" ), LTR,0,"C");
$pdf->Cell(25, 10, mb_convert_encoding( "7月", "SJIS", "UTF-8" ), LTR,0,"C");
$pdf->Cell(25, 10, mb_convert_encoding( "8月", "SJIS", "UTF-8" ), LTR,0,"C");
$pdf->SetXY( 65, 190 );
$pdf->Cell(25, 5, mb_convert_encoding( "(お誕生月)", "SJIS", "UTF-8" ), LR,0,"C");
$pdf->SetXY( 15, 195 );
$pdf->Cell(50, 10, mb_convert_encoding( "U-19[2]分お支払い", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(50, 10, mb_convert_encoding( "月額定額制[1]分お支払い", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(50, 10, mb_convert_encoding( "月額定額制[2]分お支払い", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 90, 205 );
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), R,0,"C");
$pdf->SetXY( 105, 210 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "基準日（6/30）", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetFont( KOZMIN,'U' , 10 );
$pdf->Text( 15 , 228  , mb_convert_encoding( "3．会員様専用ページについて", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 225 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "　
・ご契約のお客様にはWEBからご予約を頂ける会員様専用ページ（マイページ）をご用意しております。
　マイページからは24時間ご予約が可能ですが、予約変更可能期間を過ぎたキャンセルについては、原則として
　施術1回分を消化扱いとさせていただきます。
・マイページでは、一部条件下において一時的に予約を取得、変更、キャンセルすることができない場合がござ
　います。その際には、大変お手数ですが、KIREIMOコールセンター（0120-444-680）までご連絡ください。
・ご予約の確認の為、当社よりメールをお送りする場合がございますので、当社からのメールを受け取れるよう、
　お手持ちの機器およびアプリケーション等の設定をお願いいたします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 239 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "9/24" );

//10ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();


$pdf->SetFont( KOZMIN,'U' , 10 );
$pdf->Text( 15 , 20  , mb_convert_encoding( "4．施術に関して (補足)", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 17 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "　
・書面による親権者様の承諾が無い未成年者のお客様については、施術の提供をお断りさせていただいております
　。ご契約時に承諾書面をご用意できない場合、必ず初回施術日までに当社へご提出ください。初回施術日までに
　書面の提出をいただけない場合、施術の提供はいたしかねますのでご了承ください。
・また、初回施術日以降も引き続き承諾書面の提出をいただけない場合、エステティックサービス契約を無効と
　させていただくことがございます。
・ご予約当日、台風・大雪・地震など天変地異や著しい公共交通機関の遅延など特別な事情がある場合の
　キャンセルについては、対応を考慮させていただきます。ただし、当社の指定する期間内にご来店いただけなか
　った場合、当該期間内の施術は行われたものとさせていただきますので、指定期間内での早期のご予約を推奨
　いたします。
・災害等を原因とする臨時の休業等につきましては、原則として公式ホームページやマイページ等でお知らせいた
　しますので、そちらをご確認ください。なお、当社からのお知らせ等をご確認頂けなかったことによるお客様
　の損失等については責任を負いかねます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'U' , 10 );
$pdf->Text( 15 , 110  , mb_convert_encoding( "5．安全に施術を受けていただくために", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 108 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "　
・別紙「除毛・減毛トリートメント同意書」をよくお読みいただき、ご理解をいただいた上で当社サービスをご利用ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'U' , 10 );
$pdf->Text( 15 , 135  , mb_convert_encoding( "6．その他", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 133 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "　
・お客様の私物はトリートメントルームのロッカーに入れ、ご自身で鍵の管理を行ってください。
・紛失等の事故がおきましても、当社では責任を負いかねます。
・店舗にお忘れ物をされた場合、原則として店舗内で3ヶ月間保管した後に破棄いたします。
・ご契約プランの変更等をご希望される場合には当社所定の手続が必要となります。ご契約プランの種別やお客様
　の状況によりご希望に添えない場合がございますので、別紙「コース変更等に関するご案内」をよくお読みい
　ただいた上でお申込みください。

    その他ご不明点やご質問の際はKIREIMOコールセンター（0120-444-680）までご連絡ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 239 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "10/24" );

//11ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 73 , 24  , mb_convert_encoding( "個人情報のお取扱いについて", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 35  , mb_convert_encoding( "1.個人情報保護方針", "SJIS", "UTF-8" ) );
$pdf->SetXY( 17, 38 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "株式会社".($_GET['shop_id']==6 && $_GET['hope_date']<'2015-01-04' ? "CKR" : "ヴィエリス")."（以下「当社」といいます）は、お客様に安全で快適なエステティックサービスを提供するにあたり「個人情報の保護に関する法律」他、関係諸法令並びに本方針と当社の各規程を遵守し、お客様の個人情報保護の徹底に努めます。
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
$pdf->Text( 100 , 285  , "11/24" );


//12ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
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
本　社：東京都渋谷区広尾5-25-5　広尾アネックスビル7F " : "会社名：株式会社ヴィエリス
代表者：代表取締役　".$kireimo_ceo."
本　社：". $company_address)
."
KIREIMOコールセンター：電話　".$_GET['shop_tel']."
※ 受付時間は11：00～20：00（年末年始を除く）とさせて頂いております。

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
$pdf->Text( 100 , 285  , "12/24" );


//13ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 70 , 10  , mb_convert_encoding( "除毛・減毛トリートメント同意書", "SJIS", "UTF-8" ) );

$pdf->SetXY( 12, 13 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "当サロンで行うトリートメントはＩＰＬを用いた機器を使用し施術を行います。
トリートメントを安心してお受けいただくため、下記内容についてご確認・ご承諾をお願いいたします。
ご不明な点はスタッフにご質問下さい。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFillColor(256, 256, 0);
$pdf->Rect( 16 ,31 ,150 ,4, 'F');
$pdf->Rect( 16 ,119 ,110 ,4, 'F');
$pdf->Rect( 16 ,150 ,151 ,4, 'F');
$pdf->Rect( 16 ,177 ,88 ,4, 'F');

$pdf->SetXY( 12, 31 );
$pdf->SetFont( KOZMIN,'U' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "□下記の内容は禁忌とされております。原則として施術を行うことができませんので、ご了承下さい。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 12, 31 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "
・体調がすぐれない方（生理中含む）
・現在治療中または持病等をお持ちの方
・光アレルギー、紫外線アレルギー、光線過敏症の方
・てんかんの方	
・ペースメーカー以外の医療用機器を使用されている方	
・心疾患の方（狭心症、心筋梗塞等）	
・妊娠中、授乳中、または妊娠の可能性がある方
・感染症もしくは、感染症の疑いがある方	
・白斑症の方
・ケロイド体質の方
・帯状疱疹の方	
・過度の日焼けや日焼けをされるご予定がある方、セルフタンニングをされている方
・飲酒後の方や、飲酒のご予定のある方 (お手入れの前後12時間はお控え下さい)
", "SJIS", "UTF-8" ) , 0, 'L', 0);
$pdf->SetXY( 12, 119 );
$pdf->SetFont( KOZMIN,'U' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "□下記の箇所は、原則として施術を行うことができませんので、ご了承下さい。
", "SJIS", "UTF-8" ) , 0, 'L', 0);
$pdf->SetXY( 12, 121 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "
・粘膜部位
・皮膚トラブルで通院中の方や、皮膚疾患部位（傷、湿疹、腫れ物、アザ、乾燥等）
・ほくろ､アートメイクをされている部位､タトゥー及び､刺青をされている部位
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 12, 150 );
$pdf->SetFont( KOZMIN,'U' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "□下記の内容は、医師の同意書の提出がないと原則として施術を行うことができませんので、ご了承下さい。	
", "SJIS", "UTF-8" ) , 0, 'L', 0);
$pdf->SetXY( 12, 152 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "
・精神病（うつ病、自律神経失調症等）	
※その他、薬の服用や持病、既往歴がある方につきましては、特例トリートメント同意書の提出をお願いする場合がございます。特例トリートメント同意書に同意いただけない場合は、原則として施術を行うことができませんので、ご了承下さい。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 12, 177 );
$pdf->SetFont( KOZMIN,'U' , 10 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "□施術後は、下記内容の副反応が起こる場合があります。
", "SJIS", "UTF-8" ) , 0, 'L', 0);
$pdf->SetXY( 12, 179 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "
・施術後一時的に毛穴の赤みが起こる場合があります。冷たい清潔なタオルや保冷材などをタオルで包み冷やして頂くと症状が
　改善致します。また、皮膚温が上がることによりお肌が乾燥しやすい状態になるため、十分に保湿していただくことをおす
　すめいたします。お冷やしと保湿を継続して行っていただくことで通常２～３日すると症状も収まってきますが、1週間ほど
　冷やしても症状が落ち着かない場合、弊社ではドクターサポートを導入しておりますのでKIREIMOコールセンターまでお問い
　合わせ下さいませ。
・過度な日焼け、乾燥は火傷の可能性が高く施術をお断りすることがございます。十分に保湿を行って頂きますようお願い致し
　ます。火傷によるかさぶたが出来た際は全治まで2週間程度かかる場合もございます。
・施術後1ヶ月以内に過度な日光を浴びた場合、施術部位に色素沈着を残すことがあります。日焼け対策をお願い致します。
　(ＳＰＦ15程度)
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "13/24" );


//14ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFillColor(256, 256, 0);
$pdf->Rect( 16 ,13 ,95 ,4, 'F');

$pdf->SetXY( 12, 13 );
$pdf->SetFont( KOZMIN,'U' , 10 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "□下記内容は、トリートメント期間中の注意事項になります。
", "SJIS", "UTF-8" ) , 0, 'L', 0);
$pdf->SetXY( 12, 15 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "
・トリートメント期間中は、規則正しい生活をお心がけ下さい。
・トリートメント期間中の自己処理は、毛抜きや、ワックス、脱色などでの処理は行わず、剃るのみにして下さい。 （効果
　を高めるため、できるだけ刺激にならないよう剃毛の回数を減らすことをおすすめしています。)	
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetTextColor(255, 0, 0);
$pdf->SetXY( 12, 30 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "
・トリートメント部位の剃毛は、可能な限りトリートメントの前日に行って下さい。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY( 12, 35 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "
・施術の効果は個人差があり、目に見えてのご実感は平均して3,4回目以降になります。
　（毛周期や満足度により異なります。）
・個人の体調やホルモンバランスにより、脱毛後、毛が再生する可能性もございます。
・トリートメント期間中は日焼けをしないで下さい。
　 日焼けをする可能性がある場合にはＳＰＦ15程度日焼け止めをこまめ塗って下さい。（期間中、過度な日焼けはトラブル
　の原因になり、施術不可になります。）
・日ごろから保湿ケアをして下さい。(かゆみの防止、肌がやわらかくなり埋もれ毛も出やすくなり、脱毛効果が上がります)
・トリートメント当日は、湯船での入浴・ナイロンタオルの使用 ・サウナ・スポーツ・飲酒などの体温上昇、発汗を促す行為
　は避けて頂き、ご入浴はぬるめのシャワーのご利用を心がけて下さい。
・トリートメント期間中、お薬の服用や通院が必要となった場合、必ずご申告下さい。医師の同意が無ければトリートメン
　トが出来ない場合がございます。
・万が一、施術部位に異常が生じ、その原因がトリートメントに起因する可能性が考えられる場合、トリートメントを含む2週
　間以内にご連絡下さい。ご連絡がない場合、トリートメントに起因するものか判断が困難なため責任を負いかねる場合が
　ございます。




上記内容について、ご理解・ご承諾いただきましたら、誠に恐れいりますが、ご署名お願い致します。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 20 , 140 , mb_convert_encoding( substr($_GET['hope_date'], 0,4)." 年 ".substr($_GET['hope_date'], 5,2)." 月 ".substr($_GET['hope_date'], 8,2)." 日", "SJIS", "UTF-8" ) );

$pdf->SetDrawColor(0,0,0);
$pdf->Rect(95 ,150 ,100 ,15);

$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 167 , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 290  , "14/24" );

//15ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 42 , 15  , mb_convert_encoding( "エステティックサービス契約　ご契約内容チェックシート", "SJIS", "UTF-8" ));

$pdf->SetXY( 17, 20 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "この｢ご契約内容チェックシート｣は、このたびのご契約内容のうち特に重要な事項について、契約後のトラブル等の発生を未然に防止するため、契約者ご本人様に確認していただく書面です。
お申し込みいただくプランの種類やお支払いの方法など、以下の該当する箇所をご確認の上でチェックを入れ、末尾にご署名をお願いいたします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 17 , 46  , mb_convert_encoding( "□　<未成年のご契約者様>", "SJIS", "UTF-8" ));
$pdf->SetXY( 17, 43 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(170, 6, mb_convert_encoding( "
□　ご契約に際しては、書面による親権者様の承諾が必要となります。
□　承諾書には、親権者様にご記入いただく欄がございます。必ず親権者ご本人様にて署名・捺印等をお願いいたしま
　　す。
□　ご契約時に承諾書面をご用意できない場合、必ず初回施術日までに当社へご提出ください。	
　　初回施術日までに書面の提出をいただけない場合、施術の提供はいたしかねます。
□　また、初回施術日以降も引き続き承諾書面の提出をいただけない場合、エステティックサービス契約を無効とさせ
　　ていただくことがございます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 17 , 98  , mb_convert_encoding( "□　<スペシャルプランをお申し込みのご契約者様>", "SJIS", "UTF-8" ));
$pdf->SetXY( 17, 101);
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(170, 6, mb_convert_encoding( "□　キレイモ全身脱毛スペシャルプラン、もしくは平日とく得スペシャルプランをお申し込みいただく際には、別紙
　　「スペシャルプランに関する同意書」に記載の内容に同意いただく必要がございます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 17 , 120 , mb_convert_encoding( "□　<平日とく得プランをお申し込みのご契約者様>", "SJIS", "UTF-8" ));
$pdf->SetXY( 17, 123);
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(170, 6, mb_convert_encoding( "□　プランの性質上、予約の取得ができない時間帯（制限時間帯）を設けております。
□　会員様専用ページからは、システム制御により制限時間帯での予約取得を行うことができません。
□　また、店舗やお電話による場合であっても、原則として制限時間帯の予約は承りかねますので、予めご了承くださ
　　い。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 17 , 155 , mb_convert_encoding( "□　<パックプラン（1年・2年）をお申し込みのご契約者様>", "SJIS", "UTF-8" ));
$pdf->SetXY( 17, 153 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(170, 6, mb_convert_encoding( "
□　クーリング・オフ期間は、ご契約日から8日以内です。
□　パックプランは、契約書に定める指定回数の施術を、契約期間内に受けていただくコースです。
□　施術と施術との間隔は45日以上とさせていただいております。
□　ご予約の取得については会員様専用ページ（マイページ）をご利用ください。何らかの理由によりマイページを
　　ご利用いただけない場合には、KIREIMOコールセンターへご連絡いただくことでも予約取得が可能です。
□　予約日の前日20時（マイページからは23時59分）までにKIREIMOコールセンター(0120-444-680)へご連絡いた
　　だければ、予約変更が可能です。
□　上記変更可能期間を過ぎた場合の予約の変更・キャンセルは承りかねます。
□　ご予約の当日のキャンセル（予約変更可能時間を過ぎたキャンセル）は、当該1回分の施術が行われたものとして
　　扱います。また、事前にキャンセルのご連絡をされず、ご予約当日に来店されなかった場合（無断キャンセル）に
　　関しましても、当該1回分の施術が行われたものとさせていただきます。ただし、災害発生時など、特段の事情が
　　存する場合には、当社で対応を検討させていただく場合がございます。
□　契約期間内に指定回数の施術を受けられなかった場合、残回数の施術は消化（返金の対象外）となります。
□　ただし、上記の場合であっても、別途に契約期間と同等の保証延長期間を付与し、当該保証延長期間内に限り、
　　残回数相当分の無償役務を提供させて頂きますので、付与された保証延長期間内に残回数分の施術を受けてください
　　ますようお願いいたします。
□　原則として、保証延長期間の延長（再延長）はいたしかねます。
□　解約をご希望される場合は、KIREIMOコールセンターまでご連絡ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 290  , "15/24" );

//16ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetXY( 17, 20 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(170, 6, mb_convert_encoding( "
□　契約期間中の中途解約(契約日から9日目以後の解約)は解約手数料を頂戴いたします。
　　解約手数料は、残りの施術回数分の金額の10％(上限2万円)とさせていただいております。
□　保証延長期間内の解約も承りますが、解約時点で未消化の施術については返金の対象外となりますので、
　　予めご了承ください。
□　キャンペーンはお1人様1回までのご利用とさせていただきます。コース変更等の際のキャンペーンの適用はいた
　　しかねます。
□　コース変更等は、契約期間中のみ承ります。延長保証期間でのお申込みは承りかねますので、予めご了承くださ
　　い。コース変更等をご希望の場合には、別紙「コース変更等に関するご案内」をお読みいただいた上で、お早めに
　　店舗スタッフもしくはKIREIMOコールセンター(0120-444-680)までご連絡ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 17 , 90  , mb_convert_encoding( "□　<パックプランの料金のお支払いについて>", "SJIS", "UTF-8" ) );
$pdf->SetXY( 17, 88 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "
□　ご契約日に、契約代金のうち一部を手付金としてお支払いいただきます。
□　残金のお支払いはご契約日を含め30日以内にお支払いいただきますようお願いいたします。
□　残金のお支払いを当社が確認できない場合、施術の提供はいたしかねますので、予めご了承ください。
□　契約日から起算して90日以内にサービス料金の総額を当社が領収できなかった場合、お客様は本契約を解約する意思表示
　　をしたものとし、当社はこれを受け、本契約を解約いたします。また、その際にはお客様が納付した手付金の返還を放棄し
　　たものとみなします。
□　また、一度こちらの解約が成立した場合、お客様がお支払いされた手付金を充当して元のプランに復帰するなどの対応は
　　一切いたしかねますのでご注意ください。
□　プランの組替を行なった場合、組替日を含め30日以内に代金をお支払いください。
　　また、組替日から起算して90日以内にサービス料金の総額を当社が領収できなかった場合については、新規契約の場合と
　　同様、お客様は本契約を解約する意思表示をしたものとし、当社はこれを受け、本契約を解約いたします。
□　また、上記の場合、お客様が納付した手付金の返還を放棄したものとみなします。
　　ご返金依頼には応じかねますので、予めご了承ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 17 , 185  , mb_convert_encoding( "□　<月額プランをお申込みのご契約者様>", "SJIS", "UTF-8" ));
$pdf->SetXY( 17, 183 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(170, 6, mb_convert_encoding( "
□　月額プランは、当社の指定する2ヶ月の期間内に1回、店舗に通って施術をしていただくプランです。
□　契約後1回目の施術は、原則として契約月の翌月からの提供になりますので、契約月の翌月1日～翌々月末日までの
　　ご都合の良い日をご予約のうえ、ご来店ください。
□　毎回の施術後に次回の予約を取得していただきます。なお、予約日は当社の定める2ヶ月の期間内に指定していただき
　　、予約日の3日前の20時まで（マイページからは23時59分まで）にKIREIMOコールセンター(0120-444-680)にご連絡
　　いただければ、当該期間内であれば何度でも変更は可能です。
□　上記変更可能期間を過ぎた場合の予約の変更・キャンセルは承りかねます。
□　当社が定めた2ヶ月の期間内にご来店されなかった場合には、当該期間分の施術は行われたものとし、いただいた
　　代金の返金はいたしかねますのでご了承ください。また、いただいている代金が1回分の施術料金に満たない場合
　　、別途、当社より不足分の請求をさせていただきます。
□　施術2回目以降のお支払い方法は、銀行引落し又はクレジット決済のいずれかとなります。但し、施術開始月により
　　上記方法と異なる場合がございますので、詳しくは店舗スタッフよりご説明させていただきます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 290  , "16/24" );

//17ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();


$pdf->SetXY( 17, 20 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(170, 6, mb_convert_encoding( "
□　ご予約の日時までにお支払いが確認できない場合、施術の提供をお断りさせていただきます。
□　複数回連続でお支払いが確認できない場合、お客様の意思を確認することなく解約手続きをさせていただく場合が
　　ございます。
□　クーリング・オフ期間は、ご契約日から8日以内です。
□　解約をご希望される場合は、契約期間終了月の2ヶ月前の末日までにKIREIMOコールセンターへご連絡ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 17 , 65  , mb_convert_encoding( "□　<U-19応援プランをお申込みのご契約者様>", "SJIS", "UTF-8" ));
$pdf->SetXY( 17, 63 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(170, 6, mb_convert_encoding( "
□　本プランは、未成年のお客様（ご契約時に満20歳未満、且つご契約日から満20歳のお誕生日まで３ヶ月超の期間の
　　ある方）のみお申込が可能です。
□　本プランをご契約のお客様が満20歳を迎える月の末日もしくは翌月の末日を基準日とし、基準日以降に開始され
　　る指定期間から通常の月額定額制プランに変更されます。
□　月額定額制プランへの変更を希望されない方は、U-19応援プランの適用最終月の２ヶ月前の末日までに、KIREIMO
　　コールセンターへご連絡ください。解約の手続きをご案内いたします。
□　月額プランは前払い制のため、移行後の最初の契約（月額定額制プラン）に対するお支払いが基準日より前となる
　　場合があります。
□　契約開始月によって月額定額制プランへの移行月、およびお支払額の変更時期が異なります。下掲の例をご確認・
　　ご了承の上でお申込みください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->Text( 15 , 140  , mb_convert_encoding( " 【U-19応援プランから月額定額制プランへの移行例】", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'U' , 9 );
$pdf->Text( 15 , 147 , mb_convert_encoding( "例[1]）2021年5月に満20歳となるお客様が、2020年2月から契約を開始した場合", "SJIS", "UTF-8" ) );
$pdf->SetXY( 15, 150 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 6, mb_convert_encoding("・2021年6月から通常の月額定額制プランが適用されます。
・移行した月額定額制プランに対するお支払は、2021年の4月〜5月頃となります。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetLineWidth(0);
$pdf->SetXY( 15, 165 );
$pdf->Cell(50, 5, mb_convert_encoding( "U-19[1]施術", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(50, 5, mb_convert_encoding( "U-19[2]施術", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(50, 5, mb_convert_encoding( "月額定額制[1]施術", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 170 );
$pdf->Cell(25, 10, mb_convert_encoding(  "2021年2月", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 10, mb_convert_encoding( "3月", "SJIS", "UTF-8" ), LTR,0,"C");
$pdf->Cell(25, 10, mb_convert_encoding( "4月", "SJIS", "UTF-8" ), LTR,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "5月", "SJIS", "UTF-8" ), LTR,0,"C");
$pdf->Cell(25, 10, mb_convert_encoding( "6月", "SJIS", "UTF-8" ), LTR,0,"C");
$pdf->Cell(25, 10, mb_convert_encoding( "7月", "SJIS", "UTF-8" ), LTR,0,"C");
$pdf->SetXY( 90, 175 );
$pdf->Cell(25, 5, mb_convert_encoding( "(お誕生月)", "SJIS", "UTF-8" ), LR,0,"C");
$pdf->SetXY( 15, 180 );
$pdf->Cell(50, 10, mb_convert_encoding( "U-19[2]分お支払い", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(50, 10, mb_convert_encoding( "月額定額制[1]分お支払い", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(50, 10, mb_convert_encoding( "月額定額制[2]分お支払い", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 90, 190 );
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), R,0,"C");
$pdf->SetXY( 105, 195 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "基準日（5/31）", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'U' , 9 );
$pdf->Text( 15 , 210 , mb_convert_encoding( "例[2]）2021年5月に満20歳となるお客様が、2020年3月から契約を開始した場合", "SJIS", "UTF-8" ) );
$pdf->SetXY( 15, 213 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 6, mb_convert_encoding("・2021年7月から通常の月額定額制プランが適用されます。
・移行した月額定額制プランに対するお支払は、2021年の5月〜6月頃となります。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetLineWidth(0);
$pdf->SetXY( 15, 230 );
$pdf->Cell(50, 5, mb_convert_encoding( "U-19[1]施術", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(50, 5, mb_convert_encoding( "U-19[2]施術", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(50, 5, mb_convert_encoding( "月額定額制[1]施術", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 235 );
$pdf->Cell(25, 10, mb_convert_encoding(  "2021年3月", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 10, mb_convert_encoding( "4月", "SJIS", "UTF-8" ), LTR,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "5月", "SJIS", "UTF-8" ), LTR,0,"C");
$pdf->Cell(25, 10, mb_convert_encoding( "6月", "SJIS", "UTF-8" ), LTR,0,"C");
$pdf->Cell(25, 10, mb_convert_encoding( "7月", "SJIS", "UTF-8" ), LTR,0,"C");
$pdf->Cell(25, 10, mb_convert_encoding( "8月", "SJIS", "UTF-8" ), LTR,0,"C");
$pdf->SetXY( 65, 240 );
$pdf->Cell(25, 5, mb_convert_encoding( "(お誕生月)", "SJIS", "UTF-8" ), LR,0,"C");
$pdf->SetXY( 15, 245 );
$pdf->Cell(50, 10, mb_convert_encoding( "U-19[2]分お支払い", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(50, 10, mb_convert_encoding( "月額定額制[1]分お支払い", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(50, 10, mb_convert_encoding( "月額定額制[2]分お支払い", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 90, 255 );
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), R,0,"C");
$pdf->SetXY( 105, 260 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "基準日（6/30）", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "17/24" );

//18ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 17 , 20  , mb_convert_encoding( "□　<災害等発生時等の対応について>", "SJIS", "UTF-8" ) );
$pdf->SetXY( 17, 20 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(170, 6, mb_convert_encoding( "
□　ご予約当日、台風・大雪・地震など天変地異や著しい公共交通機関の遅延など特別な事情がある場合のキャンセル
　　については、対応を考慮させていただきます。ただし、月額プランをご契約のお客様については、当社の指定する
　　期間内にご来店いただけなかった場合、当該期間内の施術は行われたものとさせていただきますので、指定期間内
　　での早期のご予約を推奨いたします。
□　災害等を原因とする臨時の休業等につきましては、原則として事前に公式ホームページやマイページ等でお知らせ
　　いたしますので、そちらをご確認ください。なお、当社からのお知らせ等をご確認いただけなかったことによるお
　　客様の損失等については、当社は責任を負いかねます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 18 , 115  , mb_convert_encoding( substr($_GET['hope_date'], 0,4)." 年 ".substr($_GET['hope_date'], 5,2)." 月 ".substr($_GET['hope_date'], 8,2)." 日", "SJIS", "UTF-8" ) );
$pdf->Text( 18 , 122  , mb_convert_encoding( "私は今回の契約の締結に際し、上記の内容を確認し了承いたしました。", "SJIS", "UTF-8" ) );

$pdf->SetDrawColor(0,0,0);
$pdf->Rect(95 ,127 ,100 ,15);

$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 147  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "18/24" );

//19ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 60 , 30  , mb_convert_encoding( "スペシャルプランに関する同意書", "SJIS", "UTF-8" ) );

$pdf->SetXY( 17, 40 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "本書は、キレイモ全身脱毛スペシャルプラン、および平日とく得スペシャルプラン（以下、「スペシャルプラン」といいます）に関する諸注意事項等を明記したものになります。
本書は、概要書面及びエステティックサービス約款に付随し、一体となって契約内容となります。以下を確認のうえ、同意いただいた上でお申し込みください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 55 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(181, 7.5, mb_convert_encoding( "
□　支払い方法は、現金払い、クレジットカード払い、当社指定金融機関へのお振込、もしくは当社と提携するローン会社の立替
		払いをご選択いただけます。また、複数の方法を組み合わせてお支払いいただくことも可能です。
□　スペシャルプランの契約期間（返金保証期間）は、「エステティックサービス契約書」記載の契約期間とし、期間満了を
		もって終了となります。
□　契約期間内では、施術と施術との間隔を45日以上とさせていただきます。
□　スペシャルプランをご契約のお客様に対しては、契約期間終了後も、当社の定める条件の下で、期間・回数とも無制限、
		かつ無償の役務（以下、「SPサービス」という）を提供いたします。
□　平日とく得プランに適用される制限時間帯（予約取得ができない時間帯）は、SPサービスのご予約についても適用されます
		ので、予めご了承ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 125 );
$pdf->MultiCell(181, 7.5, mb_convert_encoding( "
【SPサービス概要】
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 131 );
$pdf->MultiCell(181, 7.5, mb_convert_encoding( "
□　SPサービスとは、スペシャルプランをご契約のお客様に対し、契約期間満了後も、当社の定める条件の下で、期間・回数
		とも無制限、かつ無償の役務を提供するサービスです。
□　契約書に定める役務の指定回数が未消化の状態で契約期間満了となったお客様については、契約期間満了日の翌日から
		3年間に限り、最短で45日に1回の周期でSPサービスをご利用いただけます。
□　契約期間満了日の翌日から3年間が経過した後については、最短で90日に1回のご利用周期となりますので、その旨ご了承
		ください。
□　SPサービスのご予約を当日キャンセル・無断キャンセルされた場合、それらの累計回数（※）に応じて以下の対応をさせ
		ていただきます。
		・	1度目の場合、マイページ等で予約取得を行なった日から90日間の予約不可
		・	2度目の場合、180日間の予約不可（起算日については同上）
		・	3度目の場合、エステティックサービス利用契約の解除
　		※当日キャンセル1回、無断キャンセル1回の場合、累計回数は2回となります。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 225 );
$pdf->MultiCell(181, 7.5, mb_convert_encoding( "
□　また、SPサービスについては、最終の施術日から起算して365日間、継続的に予約のない場合、エステティックサービス
		利用契約を解除させていただきます。
□　出産等の事情により365日以上の期間にわたって（SPサービスを含む）施術を受けられない場合には、店舗スタッフまたは
		KIREIMOコールセンターまでお申し出下さい。
", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "19/24" );


//20ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetXY( 17, 20 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 7, mb_convert_encoding( "
□　契約期間満了後の役務提供は、契約クレジット、ローンの対象債務とはなりません。仮に役務提供が受けられなくても、
		クレジット契約、ローン契約に基づく支払い停止の抗弁や既払金の返金原因とはなりません（お支払いの方法としてクレ
		ジット、ローンをご契約のお客様のみ対象となります。）のでご了承下さい。
□　スペシャルプランは契約期間中の中途解約が可能です。なお、中途解約における解約手数料等は以下のとおりとなり
		ます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 58 );
$pdf->MultiCell(180, 7, mb_convert_encoding( "
              残              金　 =　支払総額 - （1回あたりの料金 × 利用回数）
              解約手数料金額 =　残金×10％（最大￥20,000）
              精      算      金   =　残金 - 解約手数料金額
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 85 );
$pdf->MultiCell(180, 7, mb_convert_encoding( "
□　スペシャルプランの契約期間満了後に解約された場合は、契約期間満了時に未消化の役務については返金の対象外となり
		ますのでご了承ください。
□　繁忙期等については、予約が立て込み予約がとりにくくなる場合がございますので、予めご了承ください。
□　施術の効果には個人差がございます。本サービスは特定の効果を保証するものではございません。
		また、お支払いいただく代金は施術に対するものであり、特定の効果に対するものではございません。
□　本サービスご利用中における損害や怪我、その他の事故について、当社に故意または過失がない場合、その損害に対する
		一切の責任を負いません。
□　本書に記載なき事項は、エステティックサービス約款に準拠いたします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);



$pdf->Text( 18 , 170  , mb_convert_encoding( "私は上記の諸注意事項について確認し、同意の上でスペシャルプランに申込みいたします。
", "SJIS", "UTF-8" ) );
$pdf->Text( 18 , 177 , mb_convert_encoding( substr($cancel_date, 0,4)." 年 ".substr($cancel_date, 5,2)." 月 ".substr($cancel_date, 8,2)." 日", "SJIS", "UTF-8" ) );

$pdf->SetDrawColor(0,0,0);
$pdf->Rect(95 ,185 ,100 ,15);

$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 202  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "20/24" );


//21ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 73 , 20  , mb_convert_encoding( "コース変更等に関するご案内", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 25 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "各プランをご契約中のお客様は、当社の定める条件の下でお申込みをすることにより、コース内容の変更や追加購入など（以下、「コース変更等」といいます）を行うことができます。これらのコース変更等は、お客様が契約中のプラン種別やお申込みの時期等により制限がかかる場合があります。
今後、プランの変更や追加購入をご検討される際には、以下の案内をよくお読みいただいた上でお申込みください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'U' , 10 );
$pdf->Text( 15 , 55  , mb_convert_encoding( "1．コース変更等の種類", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 57 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "　コース変更等の手続きには、以下の種類があります。
    ・プラン組替（パックプランのコース内容変更）
    ・月額プランからパックプランへの移行
    ・パックプラン契約中の新規追加契約
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'U' , 10 );
$pdf->Text( 15 , 88  , mb_convert_encoding( "2．手続きの詳細", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 90 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "【プラン組替（パックプランのコース内容変更）】
・プラン組替とは、現在のコース内容を、同種且つより長期のコース内容へ変更することをいいます。
・契約期間中（保証延長期間は含みません）、かつ以下の条件を全て満たす場合にのみ、お申込が可能です。

1. お手続き時点で販売中のパックプランをご契約中のお客様
2. 同種のパックプランへの組替
3. 現在ご契約中のプランより、長期の（指定回数が多い）パックプランへの組替

例1.)　キレイモ全身脱毛1年プランからキレイモ全身脱毛2年プランへの組替「可」
例2.)　平日とく得2年プランから平日とく得1年プランへの組替「不可」
例3.)　平日とく得2年プランからキレイモ全身脱毛スペシャルプランへの組替「不可」
", "SJIS", "UTF-8" ) , 0, 'L', 0);

//$pdf->SetLineWidth(0);
//$pdf->SetXY( 15, 105 );
//$pdf->Cell(80, 5, mb_convert_encoding( "ご契約中のプラン", "SJIS", "UTF-8" ), 1,0,"C");
//$pdf->Cell(80, 5, mb_convert_encoding( "コース変更が可能なプラン", "SJIS", "UTF-8" ), 1,0,"C");
//$pdf->SetXY( 15, 110 );
//$pdf->Cell(80, 10, mb_convert_encoding(  "キレイモ全身脱毛1年プラン", "SJIS", "UTF-8" ), 1,0,"C");
//$pdf->Cell(80, 5, mb_convert_encoding( "キレイモ全身脱毛2年プラン", "SJIS", "UTF-8" ), LTR,0,"C");
//$pdf->SetXY( 95, 115 );
//$pdf->Cell(80, 5, mb_convert_encoding( "キレイモ全身脱毛スペシャルプラン", "SJIS", "UTF-8" ), LRB,0,"C");
//$pdf->SetXY( 15, 120 );
//$pdf->Cell(80, 5, mb_convert_encoding( "キレイモ全身脱毛2年プラン", "SJIS", "UTF-8" ), 1,0,"C");
//$pdf->Cell(80, 5, mb_convert_encoding( "キレイモ全身脱毛スペシャルプラン", "SJIS", "UTF-8" ), 1,0,"C");
//$pdf->SetXY( 15, 125 );
//$pdf->Cell(80, 10, mb_convert_encoding(  "平日とく得1年プラン", "SJIS", "UTF-8" ), 1,0,"C");
//$pdf->Cell(80, 5, mb_convert_encoding( "平日とく得2年プラン", "SJIS", "UTF-8" ), LTR,0,"C");
//$pdf->SetXY( 95, 130 );
//$pdf->Cell(80, 5, mb_convert_encoding( "平日とく得スペシャルプラン", "SJIS", "UTF-8" ), LRB,0,"C");
//$pdf->SetXY( 15, 135 );
//$pdf->Cell(80, 5, mb_convert_encoding( "平日とく得2年プラン", "SJIS", "UTF-8" ), 1,0,"C");
//$pdf->Cell(80, 5, mb_convert_encoding( "平日とく得スペシャルプラン", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 155 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "　
・現在のコース内容より短期のコース内容への変更（2年プランから1年プランへの組替、及びキレイモ 全身脱毛プラン・平日とく得プラン間での組替は承っておりませんので、予めご了承ください。
・また、2019年11月6日以降に販売されたキレイモ全身脱毛プラン、および平日とく得プランを除くパックプラン（以下、「旧販売プラン」といいます）をご契約のお客様は、プラン組替を行うことができません。
・プラン組替を行うことにより、元々の契約内容が変更されます。詳細についてはお手続きの際に提示する通知書をご確認ください。
・プラン組替後の契約期間等の起算日は、初回新規契約の締結日となります。
・既存契約の内容変更となるため、プラン組替後のクーリング・オフは承っておりません。ただし、お客様によるクーリング・オフの意思表示が、初回新規契約の締結日から起算して8日以内である場合には、クーリング・オフが可能です。
", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "21/24" );


//22ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 25 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "【月額プランからパックプランへの移行】
・月額プランからパックプランへの移行とは、あらかじめお申込をいただくことにより、現在の月額プラン契約が終了したのちに、新たな契約に基づくパックプランのサービス提供が開始されることをいいます。
・本手続きは、以下の場合にのみお申込が可能です。
1.　月額プランから、お手続き時点で販売中のパックプランへの移行

例1.)　U-19応援プランから平日とく得スペシャルプラン
例2.)　キレイモ全身脱毛月額定額制プランからキレイモ全身脱毛1年プラン
例3.)　旧販売プラン内の月額プランからキレイモ全身脱毛スペシャルプラン

・お申込の時期により、パックプランのサービス提供開始時期が遅れる場合がありますのでご注意ください。
・新たなパックプラン契約に関しては、クーリング・オフの適用があります。
・また、クーリング・オフ期間を過ぎた場合であっても、当社所定の手続きにより、契約期間中の解約(中途解約)をすることができます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 15, 120 );
$pdf->MultiCell(180, 6, mb_convert_encoding( " ■月額プランの契約期間について（ご参考）
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetDrawColor(0,0,0);
// $pdf->Rect(15 ,222 ,0 ,5); // 左から数えて縦線1
$pdf->Rect(15 ,135 ,0 ,5); // 左から数えて縦線
$pdf->Rect(40 ,131 ,70 ,7);// 左から数えて上の囲み1
$pdf->Rect(40 ,135 ,0 ,5); // 左から数えて縦線2

$pdf->Rect(110 ,131 ,70 ,7);// 左から数えて上の囲み2
$pdf->Rect(110 ,135 ,0 ,5); // 左から数えて縦線3

$pdf->Rect(180 ,131 ,20 ,0);// 左から数えて上の囲み3
$pdf->Rect(180 ,135 ,0 ,5); // 左から数えて縦線4

$pdf->Rect(15 ,138 ,185 ,0); //基準となる横線


$pdf->SetFont( KOZMIN,'' , 10 );

$pdf->Text( 70 , 130  , mb_convert_encoding( "2ヶ月", "SJIS", "UTF-8" ) );
$pdf->Text( 140 , 130  , mb_convert_encoding( "2ヶ月", "SJIS", "UTF-8" ) );

$pdf->Text( 130 , 136  , mb_convert_encoding( "※契約期間更新", "SJIS", "UTF-8" ) );

$pdf->Text( 13 , 144  , mb_convert_encoding( "契約日", "SJIS", "UTF-8" ) );
$pdf->Text( 33 , 130  , mb_convert_encoding( "翌月1日", "SJIS", "UTF-8" ) );
$pdf->Text( 103 , 144  , mb_convert_encoding( "1.契約終了日", "SJIS", "UTF-8" ) );
$pdf->Text( 173 , 144  , mb_convert_encoding( "2.契約終了日", "SJIS", "UTF-8" ) );


$pdf->SetXY( 15, 150 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "【パックプラン契約中の新規追加契約（プラン変更）】
・パックプラン契約中の新規追加契約とは、現コース（以下、「旧契約」といいます）の契約期間中に新たなコース（以下、「新契約」といいます）を追加でご契約いただくことをいいます。
・契約期間中（保証延長期間は含みません）、かつ以下の条件を全て満たす場合にのみ、お申込が可能です。

1.　旧販売プランを含むパックプランをご契約のお客様
2.　お手続き時点で販売中のパックプランを新規に契約
3.　旧契約コースの定価が、新契約コースの定価より安い
4.　旧契約の「残金額」が、新契約の「販売価格（割引金額を考慮）」より少ない
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 15, 200 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "　
・契約の種類や消化状況により追加契約できるコースが異なりますので、詳細は店舗スタッフまたはKIREIMOコールセンターまでお問い合わせください。
・手続きを行うことにより、旧契約とは別に、新契約が成立します（この点で、プラン組替と異なります）。
・新契約の契約期間等の起算日は、新契約の締結日となります。
・新契約に関しては、クーリング・オフの適用があります。
・クーリング・オフ期間を過ぎた場合であっても、当社所定の手続きにより、契約期間中の解約（中途解約）をすることができます。
・新契約がクーリング・オフ（解除）された場合には、再び旧契約が適用されるものとします。あわせて旧契約の中途解約をご希望の場合には、その旨をお申し出ください。
・中途解約の手続きについては、概要書面の記載事項に従うものとします。
・新旧契約が同時に中途解約された場合、解約手数料については最新の契約の解約についてのみ頂戴いたします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "22/24" );


//23ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'U' , 10 );
$pdf->Text( 15 , 20  , mb_convert_encoding( "3．保証延長期間での取り扱いについて", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 15 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "　
・契約期間内に指定回数の施術を受けられなかった場合、残回数の役務は消化されますが、所定の手続きにより契約期間と同等の保証延長期間が付与され、当該保証延長期間内に限り残回数相当分の施術（無償役務）を受けていただくことができます（パックプランのみ）。
・当該保証延長期間内でのコース変更等は一切承っておりません。
・引き続き当社のサービスをご希望のお客様は、保証延長期間内に残回数分の施術を全て受けて頂いてから新規のお申込みいただくか、既存契約を解約した後に新規のお申込みが必要になります。
・なお、既存契約を保証延長期間中に解約した場合、解約時に未消化の役務（施術）は全て消化扱いとなり、且つ返金の対象となりません。
また、新しい契約に持ち越すなどの手続きもいたしかねますので、予めご了承ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'U' , 10 );
$pdf->Text( 15 , 85  , mb_convert_encoding( "4．窓口のご案内", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 82 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "　
・コース変更等に関するお問い合わせについては、キレイモ各店舗のほか、以下のコールセンターでも承っております。

KIREIMOコールセンター（0120-444-680）
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "23/24" );

//24ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'U' , 10 );
$pdf->SetXY( 17, 20 );
$pdf->MultiCell(180, 7, mb_convert_encoding( "
お客様各位
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 140, 30 );
$pdf->MultiCell(180, 7, mb_convert_encoding(
	$company_address ."
株式会社ヴィエリス
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 14 );
$pdf->SetXY( 60, 55 );
$pdf->MultiCell(180, 7, mb_convert_encoding( "
ローン契約をお申し込みのお客様へ
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 17, 70 );
$pdf->MultiCell(180, 7, mb_convert_encoding( "
拝啓　時下ますますご清祥のことと存じます。平素は格別のご高配を賜り、厚く御礼申し上げます。
この度は、当社のエステティックサービスにお申し込みいただきまして、誠にありがとうございます。その際、当社提携会社とのローン契約につきましては、以下をよくお読み頂いた上でお申し込みくださいますよう、お願い申し上げます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 110 );
$pdf->MultiCell(180, 7, mb_convert_encoding( "【注意点】
	・	ローン会社の審査により、ローン契約ができない場合がございますので、予めご了承ください。
	・	ローンをお申込みの場合、申込金額に手数料を加えた額をローン会社にお支払いいただきます。
		   なお、手数料額はローン会社の規定に従います。
	・	ローンをお申込みの場合、原則として支払い方法、回数の変更はできません。
	・	ご契約後に、お申込みのローン会社から契約内容およびご本人確認の連絡がございますので、ご対応を
		   お願いいたします。
	・	ローン契約を中途解約される場合のキャンセル手数料はお客様のご負担となります。
		   なお、ローンキャンセル手数料の算出方法につきましては、ローン会社の規定に従います。
	・	ローン会社とお客様との間で、お支払いに関して何らかの問題が生じた場合、その期間における施術提供は
		   いたしかねますので、予めご了承ください。また、問題の解決が当社で確認できるまでの間、
		   お客様専用ページからの予約を制限させていただきますので、予めご了承ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 200 );
$pdf->MultiCell(180, 7, mb_convert_encoding( "
	なお、本書に関してご不明な点等ございましたら、下記お問い合わせ先までご連絡ください。

	【お問い合わせ先】
		KIREIMOコールセンター
		0120-444-680
		受付時間：11：00～20：00
		年中無休（年末年始、メンテナンス日休業）
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 180, 255 );
$pdf->MultiCell(180, 7, mb_convert_encoding( "
敬具
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "24/24" );



//$pdf->Output();
$pdf->Output($_GET['name'].$_GET['no'].".pdf","I");

?>
