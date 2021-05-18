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

$total_price = $_GET['fixed_price']+$_GET['fixed_price2']+$_GET['fixed_price3']+$_GET['fixed_price4']+$_GET['fixed_price5'];
//1ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetDrawColor(255,0,0);
$pdf->Rect(15 ,20 ,180 ,8);
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->SetTextColor(255, 0, 0);
$pdf->Text( 17 , 24  , mb_convert_encoding( "本契約（別紙）の約款に基づき以下の通り契約を締結します。", "SJIS", "UTF-8" ) );

$pdf->SetDrawColor(0,0,0);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 165 , 36  , mb_convert_encoding( "No. ".substr($_GET['hope_date'], 2,2).substr($_GET['hope_date'], 5,2)."- ". $_GET['no']."  ", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 18 );
$pdf->Text( 50 , 40  , mb_convert_encoding( "エステティックサービス契約書", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 13 );
$pdf->Text( 53 , 46  , mb_convert_encoding( "パックプラン(1回)  ・カスタマイズ(1回)", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 16 , 57  , mb_convert_encoding( substr($_GET['hope_date'], 0,4)." 年 ".substr($_GET['hope_date'], 5,2)." 月 ".substr($_GET['hope_date'], 8,2)." 日", "SJIS", "UTF-8" ) );



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
$pdf->Text( 107 , 58  , mb_convert_encoding( "会  社  名", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 122 , 58  , mb_convert_encoding( "株式会社　カレント", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Text( 107 , 62  , mb_convert_encoding( "所  在  地", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 62  , mb_convert_encoding( $company_zip_code, "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 66  , mb_convert_encoding( $company_address, "SJIS", "UTF-8" ) );

$pdf->Text( 107 , 69  , mb_convert_encoding( "電話番号", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 69  , mb_convert_encoding( "TEL: ".$company_tel_no."　FAX: ".$company_fax_no, "SJIS", "UTF-8" ) );

$pdf->Text( 107 , 73  , mb_convert_encoding( "代  表  者", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 73  , mb_convert_encoding( "代表取締役社長　".$mens_kireimo_ceo, "SJIS", "UTF-8" ) );

//}


$pdf->Text( 107 , 77  , mb_convert_encoding( "サロン名", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 122 , 77  , mb_convert_encoding( "MEN'S KIREIMO", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Text( 16 ,  77  , mb_convert_encoding( "お名前　".$_GET['name']."　　　　　様 ", "SJIS-win", "UTF-8" ) );
$pdf->Text( 106 , 84  , mb_convert_encoding( "店  舗  名： ".$_GET['shop_name']."                                                              ", "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 88  , mb_convert_encoding( "店舗住所： ".$_GET['shop_address'], "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 92  , mb_convert_encoding( "電話番号： ".$_GET['shop_tel']."                                               ", "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 96  , mb_convert_encoding( "作  成  者： ".$_GET['staff']."                                                         ", "SJIS-win", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Text( 16 , 103  , mb_convert_encoding( "サービスの内容をご確認ください。", "SJIS", "UTF-8" ) );


$pdf->Text( 16 , 115  , mb_convert_encoding( "ご利用サービス", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 10 );

$pdf->Text( 16 , 123  , mb_convert_encoding( "■コース", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetFillColor(238, 233, 233);

$pdf->SetXY( 15, 125 );
$w1 = 115;
$w2 = 50;
$w3 = 25;
// 契約部位がある場合、部位名を表示する
if($_GET['contract_part'])$_GET['contract_part'] = "（". $_GET['contract_part']. " ）";
if($_GET['contract_part2'])$_GET['contract_part2'] = "（". $_GET['contract_part2']. " ）";
if($_GET['contract_part3'])$_GET['contract_part3'] = "（". $_GET['contract_part3']. " ）";
if($_GET['contract_part4'])$_GET['contract_part4'] = "（". $_GET['contract_part4']. " ）";
if($_GET['contract_part5'])$_GET['contract_part5'] = "（". $_GET['contract_part5']. " ）";
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
// コース金額
if($_GET['fixed_price']) $_GET['fixed_price']   = "￥".number_format($_GET['fixed_price']);
if($_GET['fixed_price2']) $_GET['fixed_price2'] = "￥".number_format($_GET['fixed_price2']);
if($_GET['fixed_price3']) $_GET['fixed_price3'] = "￥".number_format($_GET['fixed_price3']);
if($_GET['fixed_price4']) $_GET['fixed_price4'] = "￥".number_format($_GET['fixed_price4']);
if($_GET['fixed_price5']) $_GET['fixed_price5'] = "￥".number_format($_GET['fixed_price5']);

// オプション
// if($_GET['option_price']){
// 	$option_price  = "￥".number_format($_GET['option_price']);
// } else {
// 	$option_price = "";
// 	$_GET['option_name']   = "";
// }

$pdf->Cell($w1, 10, mb_convert_encoding( "脱毛内容明細（コース名）", "SJIS", "UTF-8" ), 1,0,'C',1);
$pdf->Cell($w2, 10, mb_convert_encoding( "有効期間", "SJIS", "UTF-8" ), 1, 0,'C',1);
$pdf->Cell($w3, 10, mb_convert_encoding( "金額(税込)", "SJIS", "UTF-8" ), 1, 0,'C',1);
$pdf->SetXY( 15, 135 );
$pdf->Cell($w1, 10, mb_convert_encoding( $_GET['course_name'].$_GET['contract_part'], "SJIS", "UTF-8" ), 1);
$pdf->Cell($w2, 10, mb_convert_encoding( $contract_period, "SJIS", "UTF-8" ), 1, 0,'R');
$pdf->Cell($w3, 10, mb_convert_encoding( $_GET['fixed_price'], "SJIS", "UTF-8" ), 1, 0,'R');
$pdf->SetXY( 15, 145 );
$pdf->Cell($w1, 10, mb_convert_encoding( $_GET['course_name2'].$_GET['contract_part2'], "SJIS", "UTF-8" ), 1);
$pdf->Cell($w2, 10, mb_convert_encoding( $contract_period2, "SJIS", "UTF-8" ), 1, 0,'R');
$pdf->Cell($w3, 10, mb_convert_encoding( $_GET['fixed_price2'], "SJIS", "UTF-8" ), 1, 0,'R');
$pdf->SetXY( 15, 155 );
$pdf->Cell($w1, 10, mb_convert_encoding( $_GET['course_name3'].$_GET['contract_part3'], "SJIS", "UTF-8" ), 1);
$pdf->Cell($w2, 10, mb_convert_encoding( $contract_period3, "SJIS", "UTF-8" ), 1, 0,'R');
$pdf->Cell($w3, 10, mb_convert_encoding( $_GET['fixed_price3'], "SJIS", "UTF-8" ), 1, 0,'R');
$pdf->SetXY( 15, 165 );
$pdf->Cell($w1, 10, mb_convert_encoding( $_GET['course_name4'].$_GET['contract_part4'], "SJIS", "UTF-8" ), 1);
$pdf->Cell($w2, 10, mb_convert_encoding( $contract_period4, "SJIS", "UTF-8" ), 1, 0,'R');
$pdf->Cell($w3, 10, mb_convert_encoding( $_GET['fixed_price4'], "SJIS", "UTF-8" ), 1, 0,'R');
$pdf->SetXY( 15, 175 );
$pdf->Cell($w1, 10, mb_convert_encoding( $_GET['course_name5'].$_GET['contract_part5'], "SJIS", "UTF-8" ), 1);
$pdf->Cell($w2, 10, mb_convert_encoding( $contract_period5, "SJIS", "UTF-8" ), 1, 0,'R');
$pdf->Cell($w3, 10, mb_convert_encoding( $_GET['fixed_price5'], "SJIS", "UTF-8" ), 1, 0,'R');
$pdf->SetXY( 15, 185 );
$pdf->Cell($w1, 10, mb_convert_encoding( "定価合計　", "SJIS", "UTF-8" ),  1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1, 0,'R');
$pdf->Cell($w3, 10, mb_convert_encoding( "￥".number_format($total_price), "SJIS", "UTF-8" ), 1, 0,'R');
$pdf->SetXY( 15, 195 );
$pdf->Cell($w1, 10, mb_convert_encoding( "値引金額　", "SJIS", "UTF-8" ),  1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1, 0,'R');
$pdf->Cell($w3, 10, mb_convert_encoding( "￥".number_format($_GET['discount']), "SJIS", "UTF-8" ), 1, 0,'R');

// 旧表示
// $pdf->SetXY( 15, 125 );
// $pdf->Cell(70, 10, mb_convert_encoding( "脱毛内容明細（コース名）", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(10, 10, mb_convert_encoding( "回数", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->SetFont( KOZMIN,'' , 7 );
// $pdf->Cell(15, 5, mb_convert_encoding( "1回のお手れ", "SJIS", "UTF-8" ), "LTR",0,"L");
// $pdf->SetFont( KOZMIN,'' , 10 );
// $pdf->Cell(40, 5, mb_convert_encoding( "定価", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(45, 5, mb_convert_encoding( "割引後金額", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->SetXY( 95, 130 );
// $pdf->SetFont( KOZMIN,'' , 7 );
// $pdf->Cell(15, 5, mb_convert_encoding( "時間(分)", "SJIS", "UTF-8" ), "LRB",0,"C");
// $pdf->SetFont( KOZMIN,'' , 10 );
// $pdf->Cell(20, 5, mb_convert_encoding( "単価(円)", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(20, 5, mb_convert_encoding( "料金(円)", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(20, 5, mb_convert_encoding( "単価(円)", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(25, 5, mb_convert_encoding( "料金(円)", "SJIS", "UTF-8" ), 1,0,"C");


// $pdf->SetXY( 15, 135 );
// $pdf->Cell(6, 10, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(64, 10, mb_convert_encoding( $_GET['course_name'], "SJIS", "UTF-8" ), 1,0,"L");
// $pdf->Cell(10, 10, mb_convert_encoding( $_GET['times'], "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(15, 10, mb_convert_encoding( $_GET['length'], "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(20, 10, mb_convert_encoding( number_format($_GET['per_fixed_price']), "SJIS", "UTF-8" ), 1,0,"R");
// $pdf->Cell(20, 10, mb_convert_encoding( number_format($_GET['fixed_price']), "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(20, 10, mb_convert_encoding( number_format($_GET['per_price']), "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(25, 10, mb_convert_encoding( number_format($_GET['fixed_price']-$_GET['discount']), "SJIS", "UTF-8" ), 1,0,"R");

// $pdf->SetXY( 15, 145 );
// $pdf->Cell(6, 10, mb_convert_encoding( "2", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(64, 10, mb_convert_encoding( $_GET['course_name2'], "SJIS", "UTF-8" ), 1,0,"L");
// $pdf->Cell(10, 10, mb_convert_encoding( $_GET['times2'], "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(15, 10, mb_convert_encoding( $_GET['length2'], "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(20, 10, mb_convert_encoding( number_format($_GET['per_fixed_price2']), "SJIS", "UTF-8" ), 1,0,"R");
// $pdf->Cell(20, 10, mb_convert_encoding( number_format($_GET['fixed_price2']), "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(20, 10, mb_convert_encoding( number_format($_GET['per_price2']), "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(25, 10, mb_convert_encoding( number_format($_GET['fixed_price2']-$_GET['discount2']), "SJIS", "UTF-8" ), 1,0,"R");

// $pdf->SetXY( 15, 155 );
// $pdf->Cell(6, 10, mb_convert_encoding( "3", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(64, 10, mb_convert_encoding( $_GET['course_name3'], "SJIS", "UTF-8" ), 1,0,"L");
// $pdf->Cell(10, 10, mb_convert_encoding( $_GET['times3'], "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(15, 10, mb_convert_encoding( $_GET['length3'], "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(20, 10, mb_convert_encoding( number_format($_GET['per_fixed_price3']), "SJIS", "UTF-8" ), 1,0,"R");
// $pdf->Cell(20, 10, mb_convert_encoding( number_format($_GET['fixed_price3']), "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(20, 10, mb_convert_encoding( number_format($_GET['per_price3']), "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(25, 10, mb_convert_encoding( number_format($_GET['fixed_price3']-$_GET['discount3']), "SJIS", "UTF-8" ), 1,0,"R");

// $pdf->SetXY( 15, 165 );
// $pdf->Cell(6, 10, mb_convert_encoding( "4", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(64, 10, mb_convert_encoding( $_GET['course_name4'], "SJIS", "UTF-8" ), 1,0,"L");
// $pdf->Cell(10, 10, mb_convert_encoding( $_GET['times4'], "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(15, 10, mb_convert_encoding( $_GET['length4'], "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(20, 10, mb_convert_encoding( number_format($_GET['per_fixed_price4']), "SJIS", "UTF-8" ), 1,0,"R");
// $pdf->Cell(20, 10, mb_convert_encoding( number_format($_GET['fixed_price4']), "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(20, 10, mb_convert_encoding( number_format($_GET['per_price4']), "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(25, 10, mb_convert_encoding( number_format($_GET['fixed_price4']-$_GET['discount4']), "SJIS", "UTF-8" ), 1,0,"R");

// $pdf->SetXY( 15, 175 );
// $pdf->Cell(6, 10, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(64, 10, mb_convert_encoding( $_GET['course_name5'], "SJIS", "UTF-8" ), 1,0,"L");
// $pdf->Cell(10, 10, mb_convert_encoding( $_GET['times5'], "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(15, 10, mb_convert_encoding( $_GET['length5'], "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(20, 10, mb_convert_encoding( number_format($_GET['per_fixed_price5']), "SJIS", "UTF-8" ), 1,0,"R");
// $pdf->Cell(20, 10, mb_convert_encoding( number_format($_GET['fixed_price5']), "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(20, 10, mb_convert_encoding( number_format($_GET['per_price5']), "SJIS", "UTF-8" ), 1,0,"C");
// $pdf->Cell(25, 10, mb_convert_encoding( number_format($_GET['fixed_price5']-$_GET['discount5']), "SJIS", "UTF-8" ), 1,0,"R");

$pdf->SetFillColor(238, 233, 233);
$pdf->Rect( 120 ,210 ,30 ,10, 'DF');
$pdf->SetXY( 120, 210);
$pdf->Cell(30, 10, mb_convert_encoding( "総支払合計", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(45, 10, mb_convert_encoding( number_format($total_price - $_GET['discount'])."円", "SJIS", "UTF-8" ), 1,0,"R");

$pdf->Rect(95 ,230 ,100 ,15);

$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 247  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "1/7" );


//2ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );

$pdf->Text( 73 , 24  , mb_convert_encoding( "エステティックサービス約款", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 45  , mb_convert_encoding( "第1条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 42.8 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(契約の成立)
お客様(以下「甲」といいます)は、本契約書の記載内容および約款の各条項を承諾の上、本日当サロン(以下「乙」いいます)に対して
、エステティックサービス(以下「役務」といいます)にお申し込みを行い、乙はこれを承諾しました。
2. 甲が未成年の場合は、親権者の同意が必要としますので、「親権者同意書」等の書面で親権者の同意を乙が確認した上で、本契約の
    成立となります。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 65  , mb_convert_encoding( "第2条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 62.2 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(役務内容)
乙は甲に対し、本契約書に記載するコースプランおよびその回数の役務を提供するものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 74  , mb_convert_encoding( "第3条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 71.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(役務の金額、支払方法、支払時期)
乙は、甲に提供する役務の対価、関連商品がある場合は、その代金その他甲が支払わなければならない金額を本契約に明記します。
2. 甲は、役務の支払い方法として、前払金の現金一括払いまたは乙と提携するクレジット会社の立替払いの中から甲の希望する方法
    を選択できるものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 90  , mb_convert_encoding( "第4条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 87.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(役務の提供期間)
役務提供期間は、本契約書に記載された購入日（有効期間開始日）から30日（購入日含まない）になります。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

// $pdf->SetDrawColor(255,0,0);
// $pdf->Rect(13 ,95 ,186 ,79);
// $pdf->SetFont( KOZMIN,'' , 8 );
// $pdf->SetTextColor(255, 0, 0);

// $pdf->SetFont( KOZMIN,'B' , 8 );
// $pdf->Text( 15 , 100  , mb_convert_encoding( "第5条", "SJIS", "UTF-8" ) );
// $pdf->SetXY( 25, 97.5 );
// $pdf->SetFont( KOZMIN,'' , 8 );
// $pdf->MultiCell(176, 3.2, mb_convert_encoding( "(クーリング・オフ)
// 甲は、契約書面を受領した日から起算して8日間以内であれば、関連商品を含め、書面により契約を解除することができます。尚、
// 関連商品のみのクーリング・オフはできません。また、乙が契約に関して甲より金銭を受領している場合は、速やかに全額を返金いたし
// ます。但し、関連商品を開封したり、その全部もしくは一部を消費した時は、当該商品に限りクーリング・オフすることはできません。
// 1. 1回に購入頂いた総額が5万円を超え、契約期間が1ヵ月を超える場合、クーリング・オフの対象となります。
// 2. 乙が甲に不実のことを告げ、または威迫等によりクーリング・オフが妨害された場合、甲は改めてクーリング・オフができる旨を記
//     載した書面を受領し、乙より説明を受けた日から起算して8日間以内であれば、書面によるクーリング・オフをすることができ
//     ます。
// 3. 関連商品の引き渡しが既に行われている場合は、当該関連商品の引き取りに要する費用は乙の負担とします。
// 4. クーリング・オフは、甲がクーリング・オフの書面を乙宛てに発信した時に、その効力が生じます。
// 5. クレジットを利用した契約の場合、甲は乙に契約の解除を申し出た旨をクレジット会社にも別途書面による通知をするものとします。
// ", "SJIS", "UTF-8" ) , 0, 'L', 0);

// $pdf->SetXY( 27, 136 );
// $pdf->SetFont( KOZMIN,'' , 8 );
// $pdf->MultiCell(160, 3.2, mb_convert_encoding( "
//   	                                                         クーリング・オフ(契約解除)の文例
// 平成○○年〇月○日、貴社〇〇店との間で締結したエステティックサービス契約について、約款第5条に基づき契約を解除し
// ます。つきましては、私が貴社に支払った代金○○○円を下記銀行口座に振り込んでください。（また、私が受け取った商品
// をお引き取りください）
// ○○銀行〇○支店　普通預金口座○○○○　口座名義人　○○○○
// 平成○○年〇月〇日
// 契約者 (住所)
//             (氏名)　　　　　　　　　    　印
// ".
// ($_GET['shop_id']==6 && $_GET['hope_date']<'2015-01-04' ? "株式会社CKR　代表者　大澤　美加" : "株式会社カレント　代表者　室伏　貴行")."殿

// ", "SJIS", "UTF-8" ) , 1, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 103  , mb_convert_encoding( "第5条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 100.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(クーリング・オフ)
本契約は、クーリング・オフの対象外となります。ご購入後の返金は致しかねます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetTextColor(0, 0, 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 113  , mb_convert_encoding( "第6条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 110.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(中途解約)
中途解約の対象外となります。ご購入後の返金は致しかねます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 124  , mb_convert_encoding( "第7条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 121.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(施術上の注意)
乙は、甲に役務提供するにあたり、事前に甲の体質（治療中の皮膚疾患・アレルギー・敏感肌・薬の服用の有無など）および体調を聴取
し、確認するものとします。甲の体調・体質により、甲への役務提供をお断りする場合があります。
2. 役務提供期間中、甲が体調を崩したり、施術部位に異常が生じた場合、甲はその旨を乙に伝えるものとします。この場合、乙は直ちに
    役務を中止します。その原因が乙の施術に起因する疑いがある場合は、一旦乙の負担で、甲に医師の診断を受けて頂く等の適切な処置
    を取ることとし、甲乙協議の上解決するものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 147  , mb_convert_encoding( "第8条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 144.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(抗弁権の接続)
甲は、信販を利用して支払う場合、割賦販売法により、乙との間で生じている事由をもって、信販会社からの請求を拒否出来ます
    （これを抗弁権の接続といいます。）。
", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 160  , mb_convert_encoding( "第9条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 157.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(別途協議)
本契約書に定める事項に疑義が生じた場合は、甲乙協議の上解決するものとします。
2. 本契約書に定めのない事項については、民法その他の法令によるものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "2/7" );


//3ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
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
・中途解約、クーリングオフの対象外となります。
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
$pdf->Text( 100 , 285  , "3/7" );

//4ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
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
$pdf->Text( 100 , 285  , "4/7" );


//5ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
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
$pdf->Text( 100 , 285  , "5/7" );


//6ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
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
$pdf->Rect( 16 ,147 ,88 ,4, 'F');
$pdf->Rect( 16 ,162 ,127 ,4, 'F');

$pdf->SetXY( 12, 132 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(190, 5, mb_convert_encoding( "
・施術後一時的に毛穴の赤みが起こる場合があります。患部を清潔な冷タオルで冷やし、掻いたり、こすらないようご注意ください。

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
$pdf->Text( 100 , 285  , "6/7" );

//7ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
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
□　各PREMIUMコースご契約の場合、本契約書記載の契約単価に利用回数をかけた金額を消化額といたします。
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
$pdf->MultiCell(190, 7, mb_convert_encoding("
□　クーリングオフ、中途解約対象外のプランです。ご契約後の返金は致し兼ねます。
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

$pdf->SetXY( 17, 207 );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 17 , 207  , mb_convert_encoding( "< PREMIUMコースをお申込みのご契約者様 >", "SJIS", "UTF-8" ) );

$pdf->SetFillColor(256, 256, 0);
$pdf->Rect( 23 ,210 ,123 ,4, 'F');
$pdf->Rect( 23 ,223 ,147 ,4, 'F');
$pdf->Rect( 23 ,232 ,155 ,4, 'F');

$pdf->SetXY( 17, 201 );
$pdf->SetFont( KOZMIN,'' , 9 );

$pdf->MultiCell(190, 7, mb_convert_encoding( "
□　PREMIUMコースの契約期間については返金の保証期間とさせていただいております。
□　PREMIUMコースの役務の提供は期間、回数共に無制限といたします。
□　PREMIUMコースは、契約期間(保証期間)過ぎて保証回数が残っている場合も返金の対象外になります。
□　各PREMIUMコースご契約の場合、本契約書記載の契約単価に利用回数をかけた金額を消化額といたします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 18 , 244  , mb_convert_encoding( substr($_GET['hope_date'], 0,4)." 年 ".substr($_GET['hope_date'], 5,2)." 月 ".substr($_GET['hope_date'], 8,2)." 日", "SJIS", "UTF-8" ) );
$pdf->Text( 18 , 249  , mb_convert_encoding( "上記内容を確認しました。", "SJIS", "UTF-8" ) );

$pdf->SetDrawColor(0,0,0);
$pdf->Rect(95 ,251 ,100 ,15);

$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 268  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "7/7" );



//$pdf->Output();
$pdf->Output($_GET['name'].$_GET['no'],"I");

?>
