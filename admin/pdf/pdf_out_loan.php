<?php
ini_set( 'include_path', dirname(__FILE__) . "/../../php-lib/fpdf" );
require_once( 'mbfpdf.php');
require_once( '../../config/config.php');

header('Content-type: application/pdf');
header('Content-disposition: attachment; filename="'.$_GET['name'].'.pdf"');

$pdf=new MBFPDF();
$pdf->AddMBFont( GOTHIC , 'SJIS' );
$pdf->AddMBFont( PGOTHIC, 'SJIS' );
$pdf->AddMBFont( MINCHO , 'SJIS' );
$pdf->AddMBFont( PMINCHO, 'SJIS' );
$pdf->AddMBFont( KOZMIN , 'SJIS' );
$pdf->Open();

//1ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage(L);
$pdf->Image("../../img/loan/LoanApplication01.png",0,0,297); //横幅のみ指定,24

//2ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage(L);
$pdf->Image("../../img/loan/LoanApplication02.png",0,0,297); //横幅のみ指定,24

//3ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage(L);
$pdf->Image("../../img/loan/LoanApplication03.png",0,0,297); //横幅のみ指定,24

//4ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage(L);
$pdf->Image("../../img/loan/LoanApplication04.png",0,0,297); //横幅のみ指定,24

//5ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage(L);

$pdf->SetFont( KOZMIN,'B' , 9 );
$pdf->SetTextColor(255, 0, 0);
$pdf->SetDrawColor(255, 0, 0);
$pdf->SetLineWidth(0.4);
$pdf->SetXY( 15, 5 );
$pdf->MultiCell(85, 4, mb_convert_encoding( "申込者は、本申込書、裏面の「お申込の内容」に関する条
項および「個人情報の取扱いに関する同意条項」をよく読んだ上で、同意し、申込をします。
", "SJIS", "UTF-8" ), 1, 'L', 0);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetLineWidth(0.2);

$pdf->SetFont( GOTHIC,'B' , 14 );
$pdf->Text( 110 , 10  , mb_convert_encoding( "お申込の内容(クレジット申込書お客様控え)", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'B' , 9 );
$pdf->SetXY( 230, 5 );
$pdf->MultiCell(60, 4, mb_convert_encoding( "お客様がお申込される会社名", "SJIS", "UTF-8" ), 1, 'L', 0);
$pdf->SetXY( 230, 9 );
$pdf->MultiCell(60, 4, mb_convert_encoding( "株式会社".$_GET['loan_company_name']."
〒105-0003　東京都港区西新橋3-13-7
VORT虎ノ門south 11階
TEL：03-6450-1672
", "SJIS", "UTF-8" ), 1, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetTextColor(0, 0, 0);

$pdf->SetXY( 15, 20 );
$pdf->Cell(30, 5, mb_convert_encoding( "お申込年月日", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(60, 5, mb_convert_encoding( str_replace("-", "/",$_GET['application_date']), "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 25 );
$pdf->Cell(90, 5, mb_convert_encoding( "特定商取引に関する法律第42条第2項、又は第3項書面", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(30, 5, mb_convert_encoding( "受領年月日", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(30, 5, mb_convert_encoding( str_replace("-", "/",$_GET['contract_date']), "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 30 );
$pdf->Cell(30, 5, mb_convert_encoding( "フリガナ", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Cell(60, 5, mb_convert_encoding(  $_GET['name_kana'], "SJIS", "UTF-8" ), 1);
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Cell(30, 5, mb_convert_encoding( "お名前", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Cell(30, 5, mb_convert_encoding(  $_GET['name'], "SJIS-win", "UTF-8" ), 1);
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 35 );
$pdf->Cell(30, 5, mb_convert_encoding( "郵便番号", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(60, 5, mb_convert_encoding(  $_GET['zip'], "SJIS", "UTF-8" ), 1);
$pdf->Cell(30, 5, mb_convert_encoding( "生年月日", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(30, 5, mb_convert_encoding(  str_replace("-", "/",$_GET['birthday']), "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 40 );
$pdf->Cell(30, 10, mb_convert_encoding( "ご住所", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetFont( KOZMIN,'' , 9.4 );
if(mb_strlen($_GET['address'])>=37){
	$pdf->SetXY( 45, 40 );

	$pdf->MultiCell(120, 5, mb_convert_encoding(  $_GET['address'], "SJIS", "UTF-8" ), 1);

}else{
	$pdf->Cell(120, 10, mb_convert_encoding(  $_GET['address'], "SJIS", "UTF-8" ), 1);
}
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 50 );
$pdf->Cell(30, 5, mb_convert_encoding( "ご連絡先", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(120, 5, mb_convert_encoding( str_replace("-", "- ",$_GET['tel']), "SJIS", "UTF-8" ), 1);

$pdf->SetXY( 15, 55 );
$pdf->Cell(30, 5, mb_convert_encoding( "メールアドレス", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(120, 5, mb_convert_encoding(  $_GET['mail'], "SJIS", "UTF-8" ), 1);

$pdf->SetXY( 15, 65 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Cell(30, 5, mb_convert_encoding( "役務(商品・権利)名", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Cell(90, 5, mb_convert_encoding(  $_GET['course_name'], "SJIS", "UTF-8" ), 1);
$pdf->Cell(15, 5, mb_convert_encoding( "回数", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(15, 5, mb_convert_encoding(  $_GET['times'], "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 70 );
$pdf->Cell(40, 5, mb_convert_encoding( "①申込時商品金額(税込)", "SJIS-win", "UTF-8" ), 1,0,"L");
$pdf->Cell(110, 5, mb_convert_encoding( "￥".number_format($_GET['price']), "SJIS", "UTF-8" ), 1,0,"R");

$pdf->SetXY( 15, 75 );
$pdf->Cell(40, 5, mb_convert_encoding( "②頭金", "SJIS-win", "UTF-8" ), 1,0,"L");
$pdf->Cell(110, 5, mb_convert_encoding( "￥".number_format($_GET['initial_payment']), "SJIS", "UTF-8" ), 1,0,"R");

$pdf->SetXY( 15, 80 );
$pdf->Cell(40, 5, mb_convert_encoding( "③残金(申込金額)[①-②]", "SJIS-win", "UTF-8" ), 1,0,"L");
$pdf->Cell(110, 5, mb_convert_encoding( "￥".number_format($_GET['payment_loan']), "SJIS", "UTF-8" ), 1,0,"R");

$pdf->SetXY( 15, 85 );
$pdf->Cell(40, 5, mb_convert_encoding( "④分割払手数料", "SJIS-win", "UTF-8" ), 1,0,"L");
$pdf->Cell(110, 5, mb_convert_encoding( "￥".number_format($_GET['total_installment_commission']), "SJIS", "UTF-8" ), 1,0,"R");

$pdf->SetXY( 15, 90 );
$pdf->Cell(40, 5, mb_convert_encoding( "⑤分割支払金合計[③+④]", "SJIS-win", "UTF-8" ), 1,0,"L");
$pdf->Cell(110, 5, mb_convert_encoding( "￥".number_format($_GET['payment_loan']+$_GET['total_installment_commission']), "SJIS", "UTF-8" ), 1,0,"R");

$pdf->SetXY( 15, 95 );
$pdf->Cell(40, 5, mb_convert_encoding( "⑥支払総額[②+⑤]", "SJIS-win", "UTF-8" ), 1,0,"L");
$pdf->Cell(110, 5, mb_convert_encoding( "￥".number_format($_GET['initial_payment']+$_GET['payment_loan']+$_GET['total_installment_commission']), "SJIS", "UTF-8" ), 1,0,"R");

$pdf->SetXY( 15, 105 );
$pdf->Cell(40, 5, mb_convert_encoding( "支払回数", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(50, 5, mb_convert_encoding( $_GET['number_of_payments'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(30, 5, mb_convert_encoding( "お支払日", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(30, 5, mb_convert_encoding( "毎月26日", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 110 );
$pdf->Cell(40, 5, mb_convert_encoding( "支払期間", "SJIS-win", "UTF-8" ), 1,0,"L");
$pdf->Cell(110, 5, mb_convert_encoding( $_GET['first_payment_year']."  年  ".$_GET['first_payment_month']."  月  ～    ".$_GET['expected_end_year']."  年  ".$_GET['expected_end_month']."  月", "SJIS", "UTF-8" ), 1);

$pdf->SetXY( 15, 115 );
$pdf->Cell(40, 5, mb_convert_encoding( "お支払方法", "SJIS-win", "UTF-8" ), 1,0,"L");
$pdf->Cell(110, 5, mb_convert_encoding( $_GET['transfer_status'], "SJIS", "UTF-8" ), 1);

$pdf->SetXY( 15, 120 );
$pdf->Cell(40, 5, mb_convert_encoding( "第１回分割支払金", "SJIS-win", "UTF-8" ), 1,0,"L");
$pdf->Cell(110, 5, mb_convert_encoding( "￥".number_format($_GET['installment_amount_1st']), "SJIS", "UTF-8" ), 1,0,"R");

$pdf->SetXY( 15, 125 );
$pdf->Cell(40, 5, mb_convert_encoding( "第２回以降分割支払金", "SJIS-win", "UTF-8" ), 1,0,"L");
$pdf->Cell(110, 5, mb_convert_encoding( "￥".number_format($_GET['installment_amount_2nd']), "SJIS", "UTF-8" ), 1,0,"R");

$pdf->SetXY( 15, 135 );
$pdf->Cell(40, 5, mb_convert_encoding( "家賃・住宅ローン負担", "SJIS-win", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( $_GET['payment_lent'], "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(30, 5, mb_convert_encoding( "お住まい", "SJIS-win", "UTF-8" ), 1,0,"C");
$pdf->Cell(60, 5, mb_convert_encoding( $_GET['house_type'], "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 140 );
$pdf->Cell(90, 5, mb_convert_encoding( "私の収入で生活を営んでいる人数(同一生計人数)", "SJIS-win", "UTF-8" ), 1,0,"L");
$pdf->Cell(60, 5, mb_convert_encoding( $_GET['same_living_count']."人", "SJIS", "UTF-8" ), 1,0,"R");

$pdf->SetXY( 15, 145 );
$pdf->Cell(40, 5, mb_convert_encoding( "年収", "SJIS-win", "UTF-8" ), 1,0,"L");
$pdf->Cell(50, 5, mb_convert_encoding( $_GET['annual_income']."万円", "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(30, 5, mb_convert_encoding( "生活費", "SJIS-win", "UTF-8" ), 1,0,"C");
$pdf->Cell(30, 5, mb_convert_encoding( $_GET['living_grant'], "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 150 );
$pdf->Cell(40, 5, mb_convert_encoding( "本人確認書類", "SJIS-win", "UTF-8" ), 1,0,"L");
$pdf->Cell(110, 5, mb_convert_encoding( $_GET['identification_type'], "SJIS", "UTF-8" ), 1);

$pdf->SetXY( 15, 155 );
$pdf->Cell(40, 5, mb_convert_encoding( $_GET['identification_type']."番号", "SJIS-win", "UTF-8" ), 1,0,"L");
$pdf->Cell(110, 5, mb_convert_encoding( $_GET['identification_number'], "SJIS", "UTF-8" ), 1);

$pdf->SetXY( 15, 165 );
$pdf->Cell(150, 5, mb_convert_encoding( "商品(役務)等のお問合せ先", "SJIS-win", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 170 );
$pdf->MultiCell(6, 5.37, mb_convert_encoding( "販売店", "SJIS", "UTF-8" ), 1, 'CC', 0);
$pdf->SetXY( 21, 170 );
$pdf->MultiCell(144, 4, mb_convert_encoding( "株式会社ヴィエリス　　代表取締役：".$kireimo_ceo."
".$company_zip_code."　".$company_address."
TEL: ".$company_tel_no."　FAX: ".$company_fax_no."
KIREIMOコールセンター　TEL：0120-444-680
", "SJIS", "UTF-8" ), 1, 'L', 0);

// 右側
$pdf->SetXY( 180, 40 );
$pdf->Cell(110, 5, mb_convert_encoding( "売買契約・役務提供契約年月日", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 180, 45 );
$pdf->Cell(110, 5, mb_convert_encoding( "  ".str_replace("-", "/",$_GET['contract_date']), "SJIS", "UTF-8" ), 1,0,"L");

$pdf->SetXY( 180, 55 );
$pdf->Cell(110, 5, mb_convert_encoding( "役務提供期間、商品の引渡時期、権利の移転時期", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 180, 60 );
$pdf->Cell(110, 5, mb_convert_encoding( "  ".$_GET['contract_date_year']."  年".$_GET['contract_date_month']."  月  ～  ".$_GET['end_date_year']."  年  ".$_GET['end_date_month']."  月", "SJIS", "UTF-8" ), 1,0,"L");


$pdf->SetXY( 180, 70 );
$pdf->Cell(110, 5, mb_convert_encoding( "※クレジット契約の契約締結年月日は、", "SJIS", "UTF-8" ), 0,0,"L");
$pdf->SetXY( 180, 75 );
$pdf->Cell(110, 5, mb_convert_encoding( "後日あらためてお知らせいたします。", "SJIS", "UTF-8" ), 0,0,"L");

$pdf->SetXY( 180, 90 );
$pdf->MultiCell(110, 4, mb_convert_encoding( "本書面はクレジット契約成立後、割賦販売法第３５条の３の８及び
第３５条の３の９の一部、特定商取引法第５条、第１９条、第
３７条第２項、第４２条第２項又は第５５条第２項のいずれかの規
定に基づく書面となります。
", "SJIS", "UTF-8" ), 0, 'L', 0);

/*
$pdf->SetXY( 180, 85 );
$pdf->Cell(110, 5, mb_convert_encoding( "電話連絡可能時間帯", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 180, 90 );

$pdf->MultiCell(110, 4, mb_convert_encoding( "
 ".$_GET['call_timezone']."
	", "SJIS", "UTF-8" ), 1, 'L', 0);

$pdf->MultiCell(110, 4, mb_convert_encoding( "第１候補：  ".($_GET['verify_datetime_date1'] ? $_GET['verify_datetime_date1'] : '    月        日')."  （".($_GET['verify_datetime_yobi1'] ? $_GET['verify_datetime_yobi1'] : '   ')."）  ".($_GET['verify_datetime_time1'] ? $_GET['verify_datetime_time1'] : '          ')."      ～  ".($_GET['verify_datetime_time2'] ? (substr($_GET['verify_datetime_time1'], 0,2)+1).substr($_GET['verify_datetime_time1'], 2,3) : '')."

第２候補：  ".($_GET['verify_datetime_date2'] ? $_GET['verify_datetime_date2'] : '    月        日')."  （".($_GET['verify_datetime_yobi2'] ? $_GET['verify_datetime_yobi2'] : '   ')."）  ".($_GET['verify_datetime_time2'] ? $_GET['verify_datetime_time2'] : '          ')."      ～  ".($_GET['verify_datetime_time2'] ? (substr($_GET['verify_datetime_time2'], 0,2)+1).substr($_GET['verify_datetime_time2'], 2,3) : '')."

第３候補：  ".($_GET['verify_datetime_date3'] ? $_GET['verify_datetime_date3'] : '    月        日')."  （".($_GET['verify_datetime_yobi3'] ? $_GET['verify_datetime_yobi3'] : '   ')."）  ".($_GET['verify_datetime_time3'] ? $_GET['verify_datetime_time3'] : '          ')."      ～  ".($_GET['verify_datetime_time3'] ? (substr($_GET['verify_datetime_time3'], 0,2)+1).substr($_GET['verify_datetime_time3'], 2,3) : '')."
", "SJIS", "UTF-8" ), 1, 'L', 0);
*/



$pdf->SetXY( 180, 115 );
$pdf->Cell(110, 5, mb_convert_encoding( "申込店舗", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 180, 120 );
$pdf->MultiCell(110, 4, mb_convert_encoding( "KIREIMO　".$_GET['shop_name']."
〒".$_GET['shop_zip']."
".$_GET['shop_address']."
", "SJIS", "UTF-8" ), 1, 'L', 0);

$pdf->SetXY( 180, 132 );
$pdf->Cell(20, 5, mb_convert_encoding( "担当者名", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(90, 5, mb_convert_encoding( $_GET['staff'], "SJIS", "UTF-8" ), 1,0,"L");

$pdf->SetXY( 180, 142 );
$pdf->Cell(110, 5, mb_convert_encoding( "※審査の結果、ご希望に添えない場合もございますが、審査内容につ", "SJIS", "UTF-8" ), 0,0,"L");
$pdf->SetXY( 180, 147 );
$pdf->Cell(110, 5, mb_convert_encoding( "いてはご回答できかねますので、予めご了承ください。", "SJIS", "UTF-8" ), 0,0,"L");

$pdf->SetXY( 180, 170 );
$pdf->Cell(110, 5, mb_convert_encoding( "私は、上記申込内容を確認し同意の上、クレジットを申込します。", "SJIS", "UTF-8" ), 0,0,"R");
$pdf->SetXY( 180, 180 );
$pdf->Cell(110, 5, mb_convert_encoding( substr($_GET['application_date'], 0,4)." 年 ".substr($_GET['application_date'], 5,2)." 月 ".substr($_GET['application_date'], 8,2)." 日", "SJIS", "UTF-8" ), 0,0,"R");
/*
$pdf->SetXY( 180, 166 );
$pdf->Cell(20, 20, mb_convert_encoding( "署名欄", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(90, 20, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
*/
//6ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage(L);
$pdf->Image("../../img/loan/LoanApplication06-1P.png",0,0,297); //横幅のみ指定,24

//6ページ目2---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage('L');
//$pdf->Image("img/loan_slenda/LoanApplication06-2P.png",0,0,297); //横幅のみ指定,24
$pdf->Image("../../img/loan/LoanApplication06-2P.png",0,0,297); //横幅のみ指定,24

//7ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage(L);
$pdf->Image("../../img/loan/LoanApplication07.png",0,0,297); //横幅のみ指定,24

//$pdf->Output();
$pdf->Output($_GET['name'].$_GET['no'].".pdf","I");

?>
