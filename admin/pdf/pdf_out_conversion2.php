<?php
require_once(dirname(__FILE__) . '/../../php-lib/fpdf/mbfpdf.php');
require_once(dirname(__FILE__) . '/../../admin/library/pdf/pdf_out.php');

header('Content-type: application/pdf');
header('Content-disposition: attachment; filename="downloaded.pdf"');

$title = "プラン組替通知書";


$pdf=new MBFPDF();
$pdf->AddMBFont( GOTHIC , 'SJIS' );
$pdf->AddMBFont( PGOTHIC, 'SJIS' );
$pdf->AddMBFont( MINCHO , 'SJIS' );
$pdf->AddMBFont( PMINCHO, 'SJIS' );
$pdf->AddMBFont( KOZMIN , 'SJIS' );
$pdf->Open();


//1ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( GOTHIC,'' , 18 );
$pdf->Text( 80 , 27  , mb_convert_encoding( $title, "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetTextColor(0, 0, 0);

$pdf->SetXY( 15, 35 );
$pdf->Cell(30, 5, mb_convert_encoding( "お客様番号", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(60, 5, mb_convert_encoding( $customer['no'], "SJIS", "UTF-8" ), 1);
$pdf->Cell(30, 5, mb_convert_encoding( "組替日", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(60, 5, mb_convert_encoding( str_replace("-", "/", $old_cancel_date), "SJIS", "UTF-8" ), 1);
$pdf->SetXY( 15, 40 );
$pdf->MultiCell(6, 6.25, mb_convert_encoding( "ご契約者", "SJIS", "UTF-8" ), 1, 'CC', 0);
$pdf->SetXY( 21, 40 );
$pdf->Cell(24, 5, mb_convert_encoding( "フリガナ", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(150, 5, mb_convert_encoding( $customer['name_kana'], "SJIS", "UTF-8" ), 1);
$pdf->SetXY( 21, 45 );
$pdf->Cell(24, 5, mb_convert_encoding( "お名前", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(150, 5, mb_convert_encoding( $customer_name, "SJIS-win", "UTF-8" ), 1);
$pdf->SetXY( 21, 50 );
$pdf->Cell(24, 5, mb_convert_encoding( "生年月日", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(150, 5, mb_convert_encoding( $customer['birthday'], "SJIS", "UTF-8" ), 1);
$pdf->SetXY( 21, 55 );
$pdf->Cell(24, 5, mb_convert_encoding( "ご住所", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(150, 5, mb_convert_encoding( $customer['address'], "SJIS", "UTF-8" ), 1);
$pdf->SetXY( 21, 60 );
$pdf->Cell(24, 5, mb_convert_encoding( "ご連絡先", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(150, 5, mb_convert_encoding( str_replace("-", "- ", $customer['tel']), "SJIS", "UTF-8" ), 1);

$pdf->Text( 16 , 73  , mb_convert_encoding( "1．お申込みサービス", "SJIS", "UTF-8" ) );

$pdf->Text( 16 , 78  , mb_convert_encoding( "現在のコース", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 80 );
$pdf->Cell(110, 5, mb_convert_encoding( "旧　コース名", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "契約回数", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(45, 5, mb_convert_encoding( "既払金", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 85 );
$pdf->Cell(110, 5, mb_convert_encoding( $old_course['name'], "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(25, 5, mb_convert_encoding( $old_times, "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(45, 5, mb_convert_encoding( number_format($old_payed_price), "SJIS", "UTF-8" ), 1,0,"R");

$pdf->SetXY( 15, 90 );
$pdf->Cell(30, 10, mb_convert_encoding( "契約期間", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(80, 10, mb_convert_encoding( $old_contract_period , "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "未消化回数", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(45, 5, mb_convert_encoding( "単価(円)", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 125, 95 );
$pdf->Cell(25, 5, mb_convert_encoding( $old_remain_times, "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(45, 5, mb_convert_encoding( number_format($old_per_price), "SJIS", "UTF-8" ), 1,0,"R");

$pdf->Text( 16 , 108  , mb_convert_encoding( "組替後のコース", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 110 );
$pdf->Cell(110, 5, mb_convert_encoding( "新　コース名", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "契約回数", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(45, 5, mb_convert_encoding( "料金(円)", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 115 );
$pdf->Cell(110, 5, mb_convert_encoding( $course['name'], "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(25, 5, mb_convert_encoding( $times, "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(45, 5, mb_convert_encoding( number_format($contract['fixed_price'] - $contract['discount']), "SJIS", "UTF-8" ), 1,0,"R");

$pdf->SetXY( 15, 120 );
$pdf->Cell(30, 10, mb_convert_encoding( "契約期間", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(80, 10, mb_convert_encoding( $contract_period , "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "残回数", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(45, 5, mb_convert_encoding( "単価(円)", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 125, 125 );
$pdf->Cell(25, 5, mb_convert_encoding( $remain_times, "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(45, 5, mb_convert_encoding( number_format($per_price), "SJIS", "UTF-8" ), 1,0,"R");

$pdf->Text( 16 , 136  , mb_convert_encoding( "■特典明細", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 138 );
$pdf->Cell(130, 5, mb_convert_encoding( "特典明細", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "回数", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(30, 5, mb_convert_encoding( "特典価格(円)", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 143 );
$pdf->Cell(5, 5, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(125, 5, mb_convert_encoding( $option_name, "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( $option_times, "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(30, 5, mb_convert_encoding( $option_price, "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 148 );
$pdf->Cell(5, 5, mb_convert_encoding( "2", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(125, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(30, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 153 );
$pdf->Cell(5, 5, mb_convert_encoding( "3", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(125, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(30, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 158 );
$pdf->Cell(5, 5, mb_convert_encoding( "4", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(125, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(30, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 163 );
$pdf->Cell(5, 5, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(125, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(30, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->Text( 16 , 174  , mb_convert_encoding( "■割引", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 176 );
$pdf->Cell(30, 10, mb_convert_encoding( "割引対象コース", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(100, 10, mb_convert_encoding( "割引明細", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(50, 5, mb_convert_encoding( "割引金額", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 145, 181 );
$pdf->Cell(20, 5, mb_convert_encoding( "単価(円)", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(30, 5, mb_convert_encoding( "料金(円)", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 186 );
$pdf->Cell(5, 5, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( ($contract['discount'] ? $course['name'] : ""), "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(100, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( ($contract['discount'] ? ($times ? number_format($per_discount) : number_format($contract['discount'])) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(30, 5, mb_convert_encoding( ($contract['discount'] ? number_format($contract['discount']) : ""), "SJIS", "UTF-8" ), 1,0,"R");

$pdf->SetXY( 15, 191 );
$pdf->Cell(5, 5, mb_convert_encoding( "2", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(100, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(30, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 196 );
$pdf->Cell(5, 5, mb_convert_encoding( "3", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(100, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(30, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 201 );
$pdf->Cell(5, 5, mb_convert_encoding( "4", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(100, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(30, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 206 );
$pdf->Cell(5, 5, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(100, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(30, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Text( 16 , 218  , mb_convert_encoding( "2．支払方法", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 220 );
$pdf->SetLineWidth(0);
$pdf->Cell(135, 5, mb_convert_encoding( "お支払い方法", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "金額", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "入金日", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 225 );
$pdf->Cell(6, 5, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "現金", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($contract['payment_cash']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($contract['payment_cash'] ? str_replace("-", "/",$old_contract['cancel_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 230 );
$pdf->Cell(6, 5, mb_convert_encoding( "2", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "カード", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($contract['payment_card']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($contract['payment_card'] ? str_replace("-", "/",$old_contract['cancel_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 235 );
$pdf->Cell(6, 5, mb_convert_encoding( "3", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "銀行振込", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($contract['payment_transfer']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($contract['payment_transfer'] ? str_replace("-", "/",$old_contract['cancel_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 240 );
$pdf->Cell(6, 5, mb_convert_encoding( "4", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "ローン", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($contract['payment_loan']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($contract['payment_loan'] ? str_replace("-", "/",$old_contract['cancel_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 245 );
$pdf->Cell(6, 5, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( ($contract['balance'] ? "残金" : ""), "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( ($contract['balance'] ? number_format($contract['balance']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");

/*$pdf->SetFillColor(238, 233, 233);
$pdf->Rect( 15 ,145 ,165 ,5, 'DF');*/

$pdf->SetXY( 15, 250 );
$pdf->Cell(135, 5, mb_convert_encoding( "合計", "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($contract['payment_cash'] + $contract['payment_card'] + $contract['payment_transfer'] + $contract['payment_loan'] + $contract['balance']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( "-", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 255 );
$pdf->Cell(180, 5, mb_convert_encoding( "前払い金の保全措置：なし", "SJIS", "UTF-8" ), 1,0,"L");

// $pdf->SetFont( KOZMIN,'' , 8 );
// $pdf->Text( 17 , 1203  , mb_convert_encoding( "※クレジットご利用の場合、抗弁権の接続ができます。", "SJIS", "UTF-8" ) );

$pdf->SetXY( 110, 265 );
$pdf->SetFont( KOZMIN,'' , 12 );
$pdf->SetLineWidth(0.5);
$pdf->Cell(40, 10, mb_convert_encoding( "御支払額", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Cell(45, 10, mb_convert_encoding( number_format($contract['price']), "SJIS", "UTF-8" ), 1,0,"R");


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "1/6" );


//2ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Text( 15 , 15  , mb_convert_encoding( "3．代金のお支払いについて", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 18 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "プラン組替の代金については、組替日を含め30日以内にお支払いください。
また、組替日から起算して90日以内にサービス料金の総額を当社が領収できなかった場合については、新規契約の場合と同様、お客様は本契約を解約する意思表示をしたものとし、当社はこれを受け、本契約を解約いたします。

", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Text( 15 , 45  , mb_convert_encoding( "4．中途解約等について", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 48 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "プラン組替後のクーリング・オフは承っておりません。予めご了承ください。
（ただし、例外としてお客様によるクーリング・オフの意思表示が、初回新規契約の締結日から起算して8日以内である場合には、クーリング・オフが可能です。）
プラン組替後は当社所定の手続きにより契約を解約することができます。但し関連商品のみの解約はできません。
　解約時の返金額等の算出方法としては、プラン1回あたりの料金である補正単価（契約金額を契約回数で除した結果が割り切れない場合1円未満を四捨五入）に利用回数をかけた金額を消化額とし、支払総額から消化額を引いた 金額を残金とします。
解約手数料として残金の10%（最大￥20,000）を差し引き、精算金を算出いたします。 以下に、残金・解約手数料・精算金の算出方法を記載いたします。

　残　　　　　金　=　支払総額 - ( 1回あたりの料金 × 利用回数 ) 
解約手数料 = ｛ 支払総額 - ( 1回あたりの料金 × 利用回数 ) ｝×10% （最大￥20,000） 
清算金 = 残金 - 解約手数料 

※複数箇所でコース1回分とみなす契約（パックプラン、スペシャルプラン、月額プラン）につきましてはコースに含まれる箇所を1箇所でもお手入れした場合、コース1回分を消化したとみなし、解約時の返金対象とはなりません。
（例）キレイモ全身脱毛2年プラン（16回）契約時、ヒザ下1回のみお手入れ後、解約を希望した場合の返金対象は15回分となります。 
※現金の受け渡しによる返金は行っておりません。金融機関への振込とさせて頂きます。
※プラン組替後の単価で、解約手数料を計算いたします。

", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Text( 15 , 183  , mb_convert_encoding( "5．その他", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 185 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "本通知書に記載なき事項は、エステティック契約約款に準じます。
私は、上記内容および契約約款を確認し、上記プランに組替することに同意いたします。

", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 20 , 203  , mb_convert_encoding( substr($old_cancel_date, 0,4)." 年 ".substr($old_cancel_date, 5,2)." 月 ".substr($old_cancel_date, 8,2)." 日", "SJIS", "UTF-8" ) );


$pdf->Rect(95 ,203 ,100 ,15);
$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 220  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
//$pdf->Text( 17 , 190  , mb_convert_encoding( "上記内容を確かに受け取りました。", "SJIS", "UTF-8" ) );

$pdf->Image("../../img/stamp.png",155,224,24); //横幅のみ指定,24

$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Text( 107 , 232  , mb_convert_encoding( "会  社  名", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 122 , 232  , mb_convert_encoding( "株式会社　ヴィエリス", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Text( 107 , 236  , mb_convert_encoding( "所  在  地", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 236  , mb_convert_encoding( $company_zip_code, "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 239  , mb_convert_encoding( $company_address, "SJIS", "UTF-8" ) );

$pdf->Text( 107 , 243  , mb_convert_encoding( "電話番号", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 243  , mb_convert_encoding( "TEL: ".$company_tel_no."　FAX: ".$company_fax_no, "SJIS", "UTF-8" ) );

$pdf->Text( 107 , 247  , mb_convert_encoding( "代  表  者", "SJIS", "UTF-8" ) );
//$pdf->Text( 122 , 247  , mb_convert_encoding( "代表取締役社長　吉福　優", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 247  , mb_convert_encoding( "代表取締役社長　".$kireimo_ceo, "SJIS", "UTF-8" ) );

$pdf->Text( 107 , 251  , mb_convert_encoding( "サロン名", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 122 , 251  , mb_convert_encoding( "KIREIMO", "SJIS", "UTF-8" ) );

$pdf->Image("img/logo.png",20,240,40); //横幅のみ指定,24

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Text( 106 , 259  , mb_convert_encoding( "店  舗  名： ".$shop['name']."                                                              ", "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 264  , mb_convert_encoding( "店舗住所： ".$shop['address'], "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 269  , mb_convert_encoding( "電話番号： ".$shop_tel."                                               ", "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 274  , mb_convert_encoding( "担 当  者： ".$staff['name']."                                                         ", "SJIS-win", "UTF-8" ) );


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "2/6" );


//3ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
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
2.	パックプランにおいて、契約期間内に指定回数の本サービスが全て受けられなかった場合の措置として、契約期間終了日より契約期間と同等の期間（以下「保証延長期間」といいます）、残回数分の本サービスの提供を受けることができます。なお、保証延長期間中に解約される場合は、第7条の定めに従うものとし、契約期間満了時に未消化の役務については、返金の対象外となるとともに、保証延長期間中に当社が行なった施術に対する返金はいたしかねます。
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
$pdf->Text( 100 , 285  , "3/6" );


//3ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetXY( 26, 15 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->SetTextColor(255, 0, 0);
$pdf->MultiCell(162, 3.2, mb_convert_encoding( "
  	                                                        		クーリング・オフ(契約解除)の文例
西暦○○○○年○月○日、貴社〇〇店との間で締結したエステティックサービス契約について、約款第6条に基づき契約を解除します。つきましては、私が貴社に支払った代金○○○円を下記銀行口座に振り込んでください。
（また、私が受け取った商品をお引き取りください）

○○銀行○○支店　普通預金口座○○○○　口座名義人　○○○○

西暦○○○○年〇月〇日

契約者(住所)           
　　　(氏名)　　　　　　　　　    　印
						
".
	// ($shop['id']==6 && $old_cancel_date<'2015-01-04' ? "株式会社CKR　代表者　大澤　美加" : "株式会社ヴィエリス　代表者　".$kireimo_ceo)."殿
	"株式会社ヴィエリス　代表者　".$kireimo_ceo."殿

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
なお、変更後の約款に承諾できない場合は、第7条に基づき、解約手続きを行うものとします。
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
$pdf->Text( 100 , 285  , "4/6" );


//5ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
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
$pdf->Text( 100 , 285  , "5/6" );


//6ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
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
$pdf->Text( 18 , 177 , mb_convert_encoding( substr($old_cancel_date, 0,4)." 年 ".substr($old_cancel_date, 5,2)." 月 ".substr($old_cancel_date, 8,2)." 日", "SJIS", "UTF-8" ) );

$pdf->SetDrawColor(0,0,0);
$pdf->Rect(95 ,185 ,100 ,15);

$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 202  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "6/6" );


$pdf->Output($title."_".$customer_name.$customer['no'].".pdf","I");
?>
