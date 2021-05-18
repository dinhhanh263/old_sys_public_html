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

$total_price = $_GET['fixed_price']+$_GET['fixed_price2']+$_GET['fixed_price3']+$_GET['fixed_price4'];
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
// コース金額
if($_GET['fixed_price']) $_GET['fixed_price']   = "￥".number_format($_GET['fixed_price']);
if($_GET['fixed_price2']) $_GET['fixed_price2'] = "￥".number_format($_GET['fixed_price2']);
if($_GET['fixed_price3']) $_GET['fixed_price3'] = "￥".number_format($_GET['fixed_price3']);
if($_GET['fixed_price4']) $_GET['fixed_price4'] = "￥".number_format($_GET['fixed_price4']);

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
$pdf->Cell($w1, 10, mb_convert_encoding( "定価合計　", "SJIS", "UTF-8" ),  1,0,'R');
$pdf->Cell($w2, 10, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1, 0,'R');
$pdf->Cell($w3, 10, mb_convert_encoding( "￥".number_format($total_price), "SJIS", "UTF-8" ), 1, 0,'R');
$pdf->SetXY( 15, 185 );
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
$pdf->Text( 100 , 285  , "1/2" );


//6ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
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
$pdf->Text( 100 , 285  , "2/2" );




//$pdf->Output();
$pdf->Output($_GET['name'].$_GET['no'],"I");

?>
