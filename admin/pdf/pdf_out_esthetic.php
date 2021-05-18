<?php
require_once(dirname(__FILE__) . '/../../php-lib/FPDI/src/autoload.php');
require_once(dirname(__FILE__) . '/../../php-lib/TCPDF/tcpdf.php');
require_once(dirname(__FILE__) . '/../../admin/library/pdf/pdf_out.php');

// 文字コードをUTF-8に
mb_internal_encoding("UTF-8");

// クラスをインポート
use setasign\Fpdi\TcpdfFpdi;

/*
IPA Font (IPA Fonts 4 fonts package)   IPAfont00303.zip
|--Readme   Readme_IPAfont00303.txt
|--IPA Font License Agreement v1.0   IPA_Font_License_Agreement_v1.0.txt
|--IPAGothic   ipag.ttf
|--IPAPGothic   ipagp.ttf
|--IPAMincho   ipam.ttf
|--IPAPMincho   ipamp.ttf
*/

// フォントを登録
$font = new TCPDF_FONTS();
$fontMincho = $font->addTTFfont('../../php-lib/TCPDF/fonts/ipam.ttf');
$fontGothic = $font->addTTFfont('../../php-lib/TCPDF/fonts/ipagp.ttf');

// テンプレートPDFの読み込み
$base_pdf = './pdf-data/pdf_out_esthetic.pdf';

// ダウンロード時のファイル名の設定
$output_file_name = "$customer_name.pdf";

// 出力するPDFの初期設定
$pdf = new TcpdfFpdi('L', 'mm', 'A4');
$pdf->setPrintHeader( false );
$pdf->setPrintFooter( false );

// テンプレートPDFの読み込み
$pdf->setSourceFile($base_pdf);

//以下PDF---------------------------------------------------------------
//1ページ目--------------------------------------------------------------
$pdf->AddPage('P', 'A4');
$pdf->useTemplate($pdf->importPage(1));

//No.
$pdf->SetFont($fontMincho , '', 8,'',true);
$pdf->Text( 163 , 28  , substr($contract['contract_date'], 2,2).substr($contract['contract_date'], 5,2)."- ". $customer['no']."  " );
//日付
$pdf->SetFont($fontMincho , '', 9,'',true);
$pdf->Text( 20 , 50  , substr($contract['contract_date'], 0,4)." 年 ".substr($contract['contract_date'], 5,2)." 月 ".substr($contract['contract_date'], 8,2)." 日");

//会社情報
$pdf->SetFont($fontMincho , '', 8,'',true);
$pdf->Text( 119 , 50  , $company_address);										//所在地
$pdf->Text( 119 , 54  , "TEL: ".$company_tel_no."　FAX: ".$company_fax_no);	//本社Tel・Fax
$pdf->Text( 136 , 57.5  , $kireimo_ceo);											//代表者
$pdf->Text( 119 , 65.5  , $call_center_kireimo_premium);							//お客様相談室

$pdf->SetFont($fontMincho , '', 9,'',true);
//お客様名
$pdf->Text( 28 , 72  , $customer_name);
//店舗情報
$pdf->Text( 170 , 72.5  , $shop['name']);					//店舗名
$pdf->Text( 145 , 78.5  , $call_center_kireimo_premium);	//電話番号
$pdf->Text( 145 , 84  , $staff['name']);					//作成者

//■コース
$pdf->Text( 22 , 122  , $course['name']);																//コース名
$pdf->Text( 81 , 122  , $times);																		//回数
$pdf->Text( 94 , 122  , $length);																		//一回のお手入れ時間
$pdf->Text( 114 , 120  , ($times ? number_format($per_fixed_price) : 0));								//単価(定価)
$pdf->Text( 138 , 120  , number_format($contract['fixed_price']));									//料金(定価)
$pdf->Text( 162 , 120  , ($times ? number_format($per_price) : 0));									//単価(割引後)
$pdf->Text( 186 , 120  , number_format($contract['fixed_price'] - $contract['discount']));	//料金(割引後)
$pdf->Text( 138 , 124.5  , $contract_period);															//契約期間

$pdf->SetFont($fontMincho , '', 10.5,'',true);
//総支払合計
$pdf->Text( 166 , 198  , number_format($contract['fixed_price'] - $contract['discount']));															//契約期間

//2ページ目--------------------------------------------------------------
$pdf->AddPage();
$pdf->useTemplate($pdf->importPage(2));
$pdf->SetFont($fontMincho , '', 9,'',true);

//備考
$pdf->Text( 14 , 15  , $contract['memo']);

//②お支払いの方法・時期
$pdf->SetXY( 15, 41 );
$pdf->Cell(135, 5, "お支払い方法", 1,0,"C");
$pdf->Cell(20, 5, "金額", 1,0,"C");
$pdf->Cell(25, 5, "入金日", 1,0,"C");

$pdf->SetXY( 15, 46 );
$pdf->Cell(6, 5, "1", 1,0,"C");
$pdf->Cell(129, 5, "現金", 1,0,"L");
$pdf->Cell(20, 5, number_format($contract['payment_cash']), 1,0,"R");
$pdf->Cell(25, 5, ($contract['payment_cash'] ? str_replace("-", "/",$contract['contract_date']) : ""), 1,0,"C");

$pdf->SetXY( 15, 51 );
$pdf->Cell(6, 5, "2", 1,0,"C");
$pdf->Cell(129, 5, "カード", 1,0,"L");
$pdf->Cell(20, 5, number_format($contract['payment_card']), 1,0,"R");
$pdf->Cell(25, 5, ($contract['payment_card'] ? str_replace("-", "/",$contract['contract_date']) : ""), 1,0,"C");

$pdf->SetXY( 15, 56 );
$pdf->Cell(6, 5, "3", 1,0,"C");
$pdf->Cell(129, 5, "銀行振込", 1,0,"L");
$pdf->Cell(20, 5, number_format($contract['payment_transfer']), 1,0,"R");
$pdf->Cell(25, 5, ($contract['payment_transfer'] ? str_replace("-", "/",$contract['contract_date']) : ""), 1,0,"C");

$pdf->SetXY( 15, 61 );
$pdf->Cell(6, 5, "4", 1,0,"C");
$pdf->Cell(129, 5, "ローン", 1,0,"L");
$pdf->Cell(20, 5, number_format($contract['payment_loan']), 1,0,"R");
$pdf->Cell(25, 5, ($contract['payment_loan'] ? str_replace("-", "/",$contract['contract_date']) : ""), 1,0,"C");

$pdf->SetXY( 15, 66 );
if($contract['payment_coupon']){
	$pdf->Cell(6, 5, "5", 1,0,"C");
	$pdf->Cell(129, 5, "クーポン", 1,0,"L");
	$pdf->Cell(20, 5, number_format($contract['payment_coupon']), 1,0,"R");
	$pdf->Cell(25, 5, ($contract['payment_coupon'] ? str_replace("-", "/",$contract['contract_date']) : ""), 1,0,"C");

	$pdf->SetXY( 15, 71 );
	$pdf->Cell(6, 5, "6", 1,0,"C");
	$pdf->Cell(129, 5, ($contract['balance'] ? "残金" : ""), 1,0,"L");
	$pdf->Cell(20, 5, ($contract['balance'] ? number_format($contract['balance']) : ""), 1,0,"R");
	$pdf->Cell(25, 5, "", 1,0,"L");

	$pdf->SetXY( 15, 76 );
	$pdf->SetFillColor(238, 233, 233);
	$pdf->Rect( 15 ,76 ,135 ,5, 'DF');

	$pdf->Cell(135, 5, "合計", 1,0,"R");
	$pdf->Cell(20, 5, number_format($contract['payment_cash']+$contract['payment_card']+$contract['payment_transfer']+$contract['payment_loan']+$contract['payment_coupon']+$contract['balance']), 1,0,"R");
	$pdf->Cell(25, 5, "-", 1,0,"C");

	$pdf->SetXY( 15, 81 );

}else{
	$pdf->Cell(6, 5, "5", 1,0,"C");
	$pdf->Cell(129, 5, ($contract['balance'] ? "残金" : ""), 1,0,"L");
	$pdf->Cell(20, 5, ($contract['balance'] ? number_format($contract['balance']) : ""), 1,0,"R");
	$pdf->Cell(25, 5, "", 1,0,"L");

	$pdf->SetXY( 15, 71 );
	$pdf->SetFillColor(238, 233, 233);
	$pdf->Rect( 15 ,71 ,135 ,5, 'DF');

	$pdf->Cell(135, 5, "合計", 1,0,"R");
	$pdf->Cell(20, 5, number_format($contract['payment_cash']+$contract['payment_card']+$contract['payment_transfer']+$contract['payment_loan']+$contract['payment_coupon']+$contract['balance']), 1,0,"R");
	$pdf->Cell(25, 5, "-", 1,0,"C");

	$pdf->SetXY( 15, 76 );
}

$pdf->SetFont($fontMincho , '', 9,'',true);
//日付
$pdf->Text( 22 , 260  , substr($contract['contract_date'], 0,4)." 年 ".substr($contract['contract_date'], 5,2)." 月 ".substr($contract['contract_date'], 8,2)." 日");

//3ページ目--------------------------------------------------------------
$pdf->AddPage();
$pdf->useTemplate($pdf->importPage(3));

//契約者情報
$pdf->Text(54, 31.5,  $customer['no']);														//会員No.
$pdf->Text(162, 31.5,  str_replace("-", "/",$contract['contract_date']));		//契約日
$pdf->Text(54, 36.5,  $customer['name_kana']);												//フリガナ
$pdf->Text(54, 41.5,  $customer_name);														//お名前
$pdf->Text(54, 47,  $customer['birthday']);													//生年月日
$pdf->Text(54, 52,  $customer['address']);													//ご住所
$pdf->Text(54, 57,  str_replace("-", "- ",$customer['tel']));					//ご連絡先

//契約情報
$pdf->Text( 22 , 82  , $course['name']);																//コース名
$pdf->Text( 81 , 82  , $times);																		//回数
$pdf->Text( 94 , 82  , $length);																		//一回のお手入れ時間
$pdf->Text( 114 , 79  , ($times ? number_format($per_fixed_price) : 0));								//単価(定価)
$pdf->Text( 138 , 79  , number_format($contract['fixed_price']));										//料金(定価)
$pdf->Text( 162 , 79  , ($times ? number_format($per_price) : 0));									//単価(割引後)
$pdf->Text( 186 , 79  , number_format($contract['fixed_price'] - $contract['discount']));	//料金(割引後)
$pdf->Text( 138 , 84  , $contract_period);															//契約期間

$pdf->SetFont($fontMincho , '', 10.5,'',true);
//総支払合計
$pdf->Text( 166 , 151  , number_format($contract['fixed_price'] - $contract['discount']));


//4ページ目--------------------------------------------------------------
$pdf->AddPage();
$pdf->useTemplate($pdf->importPage(4));

$pdf->SetFont($fontMincho , '', 9,'',true);
//②お支払いの方法・時期
$pdf->SetXY( 15, 19 );
$pdf->Cell(135, 5, "お支払い方法", 1,0,"C");
$pdf->Cell(20, 5, "金額", 1,0,"C");
$pdf->Cell(25, 5, "入金日", 1,0,"C");

$pdf->SetXY( 15, 24 );
$pdf->Cell(6, 5, "1", 1,0,"C");
$pdf->Cell(129, 5, "現金", 1,0,"L");
$pdf->Cell(20, 5, number_format($contract['payment_cash']), 1,0,"R");
$pdf->Cell(25, 5, ($contract['payment_cash'] ? str_replace("-", "/",$contract['contract_date']) : ""), 1,0,"C");

$pdf->SetXY( 15, 29 );
$pdf->Cell(6, 5, "2", 1,0,"C");
$pdf->Cell(129, 5, "カード", 1,0,"L");
$pdf->Cell(20, 5, number_format($contract['payment_card']), 1,0,"R");
$pdf->Cell(25, 5, ($contract['payment_card'] ? str_replace("-", "/",$contract['contract_date']) : ""), 1,0,"C");

$pdf->SetXY( 15, 34 );
$pdf->Cell(6, 5, "3", 1,0,"C");
$pdf->Cell(129, 5, "銀行振込", 1,0,"L");
$pdf->Cell(20, 5, number_format($contract['payment_transfer']), 1,0,"R");
$pdf->Cell(25, 5, ($contract['payment_transfer'] ? str_replace("-", "/",$contract['contract_date']) : ""), 1,0,"C");

$pdf->SetXY( 15, 39 );
$pdf->Cell(6, 5, "4", 1,0,"C");
$pdf->Cell(129, 5, "ローン", 1,0,"L");
$pdf->Cell(20, 5, number_format($contract['payment_loan']), 1,0,"R");
$pdf->Cell(25, 5, ($contract['payment_loan'] ? str_replace("-", "/",$contract['contract_date']) : ""), 1,0,"C");

$pdf->SetXY( 15, 44 );
if($contract['payment_coupon']){
	$pdf->Cell(6, 5, "5", 1,0,"C");
	$pdf->Cell(129, 5, "クーポン", 1,0,"L");
	$pdf->Cell(20, 5, number_format($contract['payment_coupon']), 1,0,"R");
	$pdf->Cell(25, 5, ($contract['payment_coupon'] ? str_replace("-", "/",$contract['contract_date']) : ""), 1,0,"C");

	$pdf->SetXY( 15, 49 );
	$pdf->Cell(6, 5, "6", 1,0,"C");
	$pdf->Cell(129, 5, ($contract['balance'] ? "残金" : ""), 1,0,"L");
	$pdf->Cell(20, 5, ($contract['balance'] ? number_format($contract['balance']) : ""), 1,0,"R");
	$pdf->Cell(25, 5, "", 1,0,"L");

	$pdf->SetXY( 15, 54 );
	$pdf->SetFillColor(238, 233, 233);
	$pdf->Rect( 15 ,54 ,135 ,5, 'DF');

	$pdf->Cell(135, 5, "合計", 1,0,"R");
	$pdf->Cell(20, 5, number_format($contract['payment_cash']+$contract['payment_card']+$contract['payment_transfer']+$contract['payment_loan']+$contract['payment_coupon']+$contract['balance']), 1,0,"R");
	$pdf->Cell(25, 5, "-", 1,0,"C");

	$pdf->SetXY( 15, 58 );

}else{
	$pdf->Cell(6, 5, "5", 1,0,"C");
	$pdf->Cell(129, 5, ($contract['balance'] ? "残金" : ""), 1,0,"L");
	$pdf->Cell(20, 5, ($contract['balance'] ? number_format($contract['balance']) : ""), 1,0,"R");
	$pdf->Cell(25, 5, "", 1,0,"L");

	$pdf->SetXY( 15, 49 );
	$pdf->SetFillColor(238, 233, 233);
	$pdf->Rect( 15 ,49 ,135 ,5, 'DF');

	$pdf->Cell(135, 5, "合計", 1,0,"R");
	$pdf->Cell(20, 5, number_format($contract['payment_cash']+$contract['payment_card']+$contract['payment_transfer']+$contract['payment_loan']+$contract['payment_coupon']+$contract['balance']), 1,0,"R");
	$pdf->Cell(25, 5, "-", 1,0,"C");

	$pdf->SetXY( 15, 54 );
}

//備考
$pdf->Text( 14 , 97  , $contract['memo']);

$pdf->SetFont($fontMincho , '', 8,'',true);
//会社情報
$pdf->Text( 122 , 192  , $company_address);										//本社住所
$pdf->Text( 122 , 196  , "TEL: ".$company_tel_no."　FAX: ".$company_fax_no);	//本社Tel・Fax
$pdf->Text( 137 , 200  , $kireimo_ceo);											//CEO
$pdf->Text( 122 , 208  , $call_center_kireimo_premium);							//コールセンター

//店舗情報
$pdf->Text( 157 , 215.5  , $shop['name']);					//店舗名
$pdf->Text( 135 , 221.5  , $call_center_kireimo_premium);		//電話番号
$pdf->Text( 135 , 227  , $staff['name']);						//作成者


//5ページ目--------------------------------------------------------------
$pdf->AddPage();
$pdf->useTemplate($pdf->importPage(5));


//6ページ目--------------------------------------------------------------
$pdf->AddPage();
$pdf->useTemplate($pdf->importPage(6));

$pdf->SetFont($fontMincho , '', 11,'',true);
$pdf->SetTextColor(255, 0, 0);
$pdf->Text( 69 , 181.5  , $kireimo_ceo);

//7ページ目--------------------------------------------------------------
$pdf->AddPage();
$pdf->useTemplate($pdf->importPage(7));

//8ページ目--------------------------------------------------------------
$pdf->AddPage();
$pdf->useTemplate($pdf->importPage(8));

//9ページ目--------------------------------------------------------------
$pdf->AddPage();
$pdf->useTemplate($pdf->importPage(9));

//日付
$pdf->SetTextColor(0, 0, 0);
$pdf->Text( 23 , 226  , substr($contract['contract_date'], 0,4)." 年 ".substr($contract['contract_date'], 5,2)." 月 ".substr($contract['contract_date'], 8,2)." 日");

//10ページ目--------------------------------------------------------------
$pdf->AddPage();
$pdf->useTemplate($pdf->importPage(10));

//日付
$pdf->SetFont($fontGothic , 'B', 10,'',true);
$pdf->Text( 23 , 220  , substr($contract['contract_date'], 0,4)." 年 ".substr($contract['contract_date'], 5,2)." 月 ".substr($contract['contract_date'], 8,2)." 日");

//11ページ目--------------------------------------------------------------
$pdf->AddPage();
$pdf->useTemplate($pdf->importPage(11));

//12ページ目--------------------------------------------------------------
$pdf->AddPage();
$pdf->useTemplate($pdf->importPage(12));

//コールセンター
$pdf->SetFont($fontMincho , '', 9,'',true);
$pdf->Text( 38 , 66.5  , $call_center_kireimo_premium);
//日付
$pdf->Text( 24 , 120  , substr($contract['contract_date'], 0,4)." 年 ".substr($contract['contract_date'], 5,2)." 月 ".substr($contract['contract_date'], 8,2)." 日");


//13ページ目--------------------------------------------------------------
$pdf->AddPage();
$pdf->useTemplate($pdf->importPage(13));

$pdf->SetFont($fontMincho , '', 11,'',true);

$pdf->Text( 128 , 37  , $company_address);										//本社住所
$pdf->Text( 20 , 202  , $call_center_kireimo_premium);							//コールセンター


// ファイル出力-----------------------------------------------------------
$pdf->Output($output_file_name, 'I');



