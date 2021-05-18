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
$pdf->Text( 100 , 285  , "1/7" );


//2ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Text( 15 , 15  , mb_convert_encoding( "3．代金のお支払いについて", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 18 );
$pdf->MultiCell(185, 6, mb_convert_encoding( "プラン組替の代金については、組替日を含め30日以内にお支払いください。
また、組替日から起算して90日以内にサービス料金の総額を当社が領収できなかった場合については、新規契約の場合と同様、お客様は本契約を解約する意思表示を行ったものとみなし、当社はこれを受け、本契約を解除いたします。
なお、この場合、解除日までにお客様が納付したプラン組換代金の一部相当額（手付金を含む。）については、一切返金致しかねますので、予めご了承ください。

", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Text( 15 , 55  , mb_convert_encoding( "4．中途解約等について", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 58 );
$pdf->MultiCell(185, 6, mb_convert_encoding( "プラン組替後のクーリング・オフは承っておりません。予めご了承ください。
（ただし、例外としてお客様によるクーリング・オフの意思表示が、初回新規契約の締結日から起算して8日以内である場合には、クーリング・オフが可能です。）
プラン組替後は当社所定の手続きにより、契約を解約することができます。但し関連商品のみの解約はできません。
 組替後の解約については、組替の回数、消化の進捗などにより返金額等の算出方法が複雑になります。以下に例を記載しておりますが、個別の返金額に関しましては、KIREIMOコールセンターまでお問い合わせください。

 【例】
 初回プラン：キレイモ全身脱毛お試しプラン（指定回数4回、￥87,780）
 役務消化（施術）を3回行った後に組替
 組替後プラン：キレイ全身脱毛15回プラン（指定回数15回、￥308,000）
 組替後、役務消化（施術）を10回行った後に中途解約

1.)	初回プラン金額÷回数＝組替前1回消化単価（￥87,780÷4＝￥21,945）
2.)	組替前1回消化単価×消化回数＝組替前消化金額（￥21,945×3＝￥65,835　）
3.)	組替後プラン金額-組替前消化金額＝組替後残金額（￥308,000-￥65,835＝￥242,165）　 
4.)	組替後残金額÷組替後残回数＝組替後1回消化単価（￥242,165÷(15-3)＝￥20,180）　 
5.)	組替後1回消化単価×組替後消化回数＝組替後消化金額（￥20,180×10＝￥201,800）
6.)	組替後プラン金額-（組替前消化金額+組替後消化金額）＝残金（￥308，000-（￥65,835+￥201,800）				         ＝￥40,365）
7.)	残金-解約手数料10％＝返金金額（￥40,365-￥4,037＝￥36,328）

※複数箇所でコース1回分とみなす契約（パックプラン、無制限プラン、月額プラン）につきましてはコースに含まれる箇所を1箇所でもお手入れした場合、コース1回分を消化したとみなし、解約時の返金対象とはなりません。
（例）キレイモ全身脱毛15回プラン契約時、ヒザ下1回のみお手入れ後、解約を希望した場合の返金対象は14回分となります。 
※現金の受け渡しによる返金は行っておりません。金融機関への振込とさせて頂きます。

", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Text( 15 , 230  , mb_convert_encoding( "5．その他", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 233 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "本通知書に記載なき事項は、エステティックサービス約款に準じます。

", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "2/7" );


//3ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();
$pdf->SetFont( KOZMIN,'' , 10 );

$pdf->SetXY( 15, 18 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "
私は、上記内容および約款を確認し、上記プランに組替することに同意いたします。

", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 20 , 45  , mb_convert_encoding( substr($old_cancel_date, 0,4)." 年 ".substr($old_cancel_date, 5,2)." 月 ".substr($old_cancel_date, 8,2)." 日", "SJIS", "UTF-8" ) );

$pdf->Rect(95 ,56 ,100 ,15);
$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 75  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
//$pdf->Text( 17 , 190  , mb_convert_encoding( "上記内容を確かに受け取りました。", "SJIS", "UTF-8" ) );

$pdf->Image("../../img/stamp.png",155,78,24); //横幅のみ指定,24

$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Text( 107 , 90  , mb_convert_encoding( "会  社  名", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 122 , 90  , mb_convert_encoding( "株式会社　ヴィエリス", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Text( 107 , 95  , mb_convert_encoding( "所  在  地", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 95  , mb_convert_encoding( $company_zip_code, "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 99  , mb_convert_encoding( $company_address, "SJIS", "UTF-8" ) );

$pdf->Text( 107 , 104  , mb_convert_encoding( "電話番号", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 104  , mb_convert_encoding( "TEL: ".$company_tel_no."　FAX: ".$company_fax_no, "SJIS", "UTF-8" ) );

$pdf->Text( 107 , 109  , mb_convert_encoding( "代  表  者", "SJIS", "UTF-8" ) );
//$pdf->Text( 122 , 247  , mb_convert_encoding( "代表取締役社長　吉福　優", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 109  , mb_convert_encoding( "代表取締役社長　".$kireimo_ceo, "SJIS", "UTF-8" ) );

$pdf->Text( 107 , 114  , mb_convert_encoding( "サロン名", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 122 , 114  , mb_convert_encoding( "KIREIMO", "SJIS", "UTF-8" ) );

$pdf->Image("img/logo.png",20,100,40); //横幅のみ指定,24

$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Text( 106 , 122  , mb_convert_encoding( "店  舗  名： ".$shop['name']."                                                              ", "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 127  , mb_convert_encoding( "店舗住所： ".$shop['address'], "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 132  , mb_convert_encoding( "電話番号： ".$shop_tel."                                               ", "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 137  , mb_convert_encoding( "担 当  者： ".$staff['name']."                                                         ", "SJIS-win", "UTF-8" ) );


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "3/7" );


//4ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 73 , 10  , mb_convert_encoding( "エステティックサービス約款", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 14 , mb_convert_encoding( "第1条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 11.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(本約款の適用範囲）
株式会社ヴィエリス（以下「当社」といいます）は、エステティックサービス約款（以下「本約款」といいます）に基づき、エステティックサービス（以下「本サービス」といいます）を提供するものとします。
2.	当社が本約款以外に定める「概要書面（事前説明書）」、「エステティックサービス契約書」、「KIREIMOのご案内」、「エステティッ　クサービス契約　ご契約内容チェックシート」、「除毛・減毛トリートメント同意書」およびその他、当社が定めるもの（以下これらを　総称して「個別約款」といいます）は、本約款の一部を構成するものとし、本約款と個別約款の定めが異なる場合、別段の定めがない　限り、個別約款の定めが優先して適用されるものとします。
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
（1）月額プランでは、当社の定める2ヶ月の期間内に1度（以下「当該期間」といいます）、本サービスの提供を受けることができます。
（2）パックプランでは、本契約書に定める契約期間（返金保証期間と同義）中に、指定する回数の本サービスの提供を受けることができま　　す。
（3）無制限プランは、パックプランの一種であり、本契約書に定める契約期間中に指定回数の本サービスの提供を受けられることに加え、　　契約期間終了後も、当社の定める条件の下で引き続き本サービスの提供を受けることができます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY( 25, 76 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "
2.	当社はお客様に対し、新たなプランや付加的なオプションを提供することがあります。その詳細は本約款または個別約款に定め、お客　様へ説明を行うものとします。
3.	本サービスの提供を受けるために予約が必要となりますので、当社所定の方法により予約手続きをしていただきます。なお、ご希望の予　約日が月末、繁忙期と重なる場合、ご希望する日時の予約が取れず、前項第1号の当該期間内に本サービスを提供ができない場合がござい　ますので、予めご了承ください。
4.	また、ご契約プランの内容や役務消化の進捗状況等に応じて、予約取得の周期、予約可能な曜日または時間帯等に制限のある場合があり　ますので、予めご了承ください。
5.	当社は、本サービスの提供に用いる機器類の性能や脱毛技術の状況、お客様のご来店機会やサービス満足度に関する調査結果等を考慮　し、本サービスの1回あたりの所要時間（施術時間）を変更させていただく場合がございます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 111  , mb_convert_encoding( "第4条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 108.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(本サービスの料金、支払時期、支払方法)
お客様に提供する本サービスの料金、および販売する関連商品等の料金は本契約書に明記します。
2.	本サービスの料金の支払方法は以下のとおりといたします。
（1）	月額プランは、1回目の本サービス料金を本契約の契約日にお支払いいただきます。2回目以降のお支払いは、契約時に当社所定の継続手続きを行っていただき、契約月の翌月より毎月クレジットカード決済もしくは金融機関口座振替払いのいずれかになります。
（2）	パックプランは、現金払い、クレジットカード払い、当社指定金融機関への振込、もしくは当社と提携するローン会社の立替払いをご選択いただけます。また、複数の支払方法を併用することも可能です。
3.	前項第１号に定める支払方法のうち、金融機関口座振替払いの方で金融機関の決済が取れなかった場合、月末までにお支払いがない　　　限り、すでに予約されている次回以降の予約が取り消しとなります。
4.	お客様は、原則として契約の締結日に本サービス料金の一部を手付金として納付するものとし、残額は本契約の契約日より90日以内に支　払うものとします。
5.	契約日から起算して90日以内にサービス料金の総額を当社が領収できなかった場合、お客様は本契約を解約する意思表示をしたものとし、　当社は、第9条1項に基づき、本契約を解除いたします。また、この場合、解除日までにお客様が納付した本サービス料金の一部相当額　（手付金を含む。以下「手付金等」といいます）の返還を放棄したものとみなし、手付金等は変換いたしません。なお、本号の解除が成立し　た場合、お客様がお支払いされた手付金等を充当して元のプランに復帰するなどの対応は一切いたしかねますのでご注意ください。
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
2.	パックプランにおいて、契約期間内に指定回数の本サービスが全て受けられなかった場合の措置として、契約期間終了日より契約期間　と同等の期間（以下「保証延長期間」といいます）、残回数分の本サービスの提供を受けることができます。なお、保証延長期間中に解　約される場合は、第8条3項の定めに従うものとし、契約期間満了時に未消化の役務については、返金の対象外となります。また、保証延　長期間中に当社が行なった施術に対する返金はいたしかねます。
3.	原則として、保証延長期間の延長（再延長）は致しかねますので、予めご了承ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 186 , mb_convert_encoding( "第6条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 183.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(契約の変更・追加)
お客様は、当社の定める条件の下で、お申出により契約内容を変更すること、もしくは新たな契約を追加することができます。但し、ご契約中のプランの種類や役務消化の進捗、お客様の状況（ご年齢・お支払状況等）により、ご希望に沿えない場合がございますので、予めご了承ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetDrawColor(255,0,0);
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->SetTextColor(255, 0, 0);
$pdf->Rect(15 ,199.5 ,181 ,47);
$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 16 , 203  , mb_convert_encoding( "第7条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 200.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(クーリング・オフ)
お客様は、契約書面を受領した日から起算して8日間以内であれば、書面により本契約を解除することができます。
2.	当社がお客様に不実のことを告げ、または威迫等によりクーリング・オフが妨害された場合、当社は改めてクーリング・オフが
　できる旨を記載した書面を受領し、当社より説明を受けた日から起算して8日間以内であれば、書面によるクーリング・オフをする
　ことができます。
3.	前二項に基づく解除がなされた場合、関連商品販売契約についても、その契約を解除することができます。但し、関連商品を開
　封したり、その全部もしくは一部を消費したりした場合、当該商品に限りクーリング・オフすることはできません。関連商品の引
　き渡しが既に行われている場合は、当該関連商品の引き取りに要する費用は当社の負担とします。
4.	クーリング・オフは、お客様がクーリング・オフの書面を当社宛てに発信した時に、その効力が生じます。クレジットを利用した
　契約の場合、お客様は当社に契約の解除を申し出た旨をクレジット会社にも別途書面による通知をしていただく必要がございます。
5.	本条による契約解除については、違約金及び利用した本サービスの料金の支払いは不要とし、当社はお客様から現金一括払い・
　クレジットカード決済・金融機関口座振替等により受領した前受金及び関連商品販売に関し金銭を受領している場合には、当該金
　銭につき速やかにお客様の金融機関口座に振り込みにより返還するものとします。なお、当該金銭を返還する際の費用は当社の負
　担とします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "4/7" );


//5ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
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
	// ($shop['id']==6 && $old_cancel_date<'2015-01-04' ? "株式会社CKR　代表者　大澤　美加" : "株式会社ヴィエリス　代表者　".$kireimo_ceo)."殿
    "株式会社ヴィエリス　代表者　".$kireimo_ceo."殿
    
", "SJIS", "UTF-8" ) , 1, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->SetTextColor(0, 0, 0);
$pdf->Text( 15 , 68 , mb_convert_encoding( "第8条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 65.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(中途解約・返品）
本契約は、クーリング・オフ期間を過ぎても、関連商品を含め、以下に定める方法により中途解約することができます。
（1）	月額プランの場合、最終施術希望期間開始月の前月末日までに、当社所定の方法により解約手続きを行うものとします。なお、金融　　機関の都合により、解約の申し出時点でクレジット決済または銀行口座振替の中止ができない場合がございますので、その際は当該金額　を金融機関より受領後、すみやかに全額返金します。なお、返金は金融機関口座への振込とし、それにかかる手数料は、お客様負担とな　ります。
（2）	パックプランの場合、原則として、契約期間内に、当社所定の方法により解約手続きを行うものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY( 25, 84.5 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "
2.	パックプランをご契約のお客様が、契約期間中に本契約を中途解約した場合、解約手数料として本サービスの未消化分相当金額の10％（契約金額を契約回数で除した結果が割り切れない場合1円未満を四捨五入）をお支払いいただきます。但し、解約手数料の上限額は2万円と　します。
3.	パックプランをご契約のお客様が、契約期間終了後、保証延長期間中の解約（未消化分の役務提供を受ける権利の放棄）を行なった場　合、契約期間満了時に未消化の役務については、返金の対象外となります。 
4.	中途解約により当社より返金がある場合、本サービスの未消化分の金額より、前項により算出した解約手数料を差し引いた金額を返金い　たします。なお、返金方法は、金融機関口座への振込払いになり、振込にかかる手数料は、お客様負担となります。なお、返金金額が振　込手数料金額以下の場合、返金は行わず、また振込手数料も請求しないものといたします。
5.	関連商品は、当該商品を開封したり、その全部もしくは一部を消費したりした場合は、返品できないものとします。但し、未使用の場　合であっても、保存方法により著しく商品価値が損なわれている場合は、返品不可となります。なお、返品にあたっての返送費用およ　びお客様へ返金がある場合、返金方法は金融機関口座への振込払いとし、それにかかる手数料は、お客様の負担とします。
6.	お支払い方法がクレジットカード払いの場合、本条における返金方法は金融機関口座への振込払いとなり、それにかかる手数料は、お　客様の負担とします。なお、返金金額が振込手数料金額以下の場合、返金は行わず、また振込手数料も請求しないものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 132 , mb_convert_encoding( "第9条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 130 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(契約解除)
当社は、お客様が以下のいずれかに該当した場合には、何らの催告なしに本契約を解除することができるものとします。
（1）	本契約に違反し、当社より催告されたにも関わらず、是正されていないと判断された場合
（2）	本契約における代金の支払いが複数回にわたり遅滞した場合
（3）	差押え、仮差押え、仮処分その他の強制執行または滞納処分の申し立てを受けた場合
（4）	破産手続、民事再生手続、会社更生手続等の開始申立を受け、若しくは自らこれらの申立をなしたとき
（5）	お客様の体質的に起因して、本サービスの提供の継続が困難だと判断した場合
（6）	お客様の信用状態に重大な変化が生じた場合
（7）	契約日から起算して90日以内にサービス料金の総額を当社が領収できなかった場合
（8）	プラン組替を行い、組替日から起算して90日以内にサービス料金の総額を当社が領収できなかった場合
", "SJIS", "UTF-8" ) , 0, 'L', 0);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY( 25, 159.5 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "
2. 前項に基づき当社が本契約を解除したことにより、お客様に生じた不利益、損害について、当社は一切の責任を負わないものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 169.5 , mb_convert_encoding( "第10条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 167 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.5, mb_convert_encoding( "(施術上の注意)
当社は、お客様に本サービスを提供するにあたり、事前にお客様の体質（治療中の皮膚疾患・アレルギー・敏感肌・薬の服用の有無など）および体調を聴取し、確認するものとします。お客様の体調・体質により、お客様への本サービスの提供をお断りする場合があります。
2.	本サービス提供期間中、お客様が体調を崩したり、施術部位に異常が生じたりした場合、お客様はその旨を当社に伝えるものとします。　この場合、当社は直ちに役務を中止します。その原因が当社の施術に起因する疑いがある場合は、一旦当社の負担で、お客様に医師の診断を受　けて頂く等の適切な処置を取ることとし、当事者間の協議の上解決するものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 190.5  , mb_convert_encoding( "第11条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 188.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(抗弁権の接続)
お客様は、信販を利用して支払う場合、割賦販売法により、当社との間で生じている事由をもって、信販会社からの請求を拒否することができます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 201  , mb_convert_encoding( "第12条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 198.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.5, mb_convert_encoding( "(別途協議)
本約款に定める事項に疑義が生じた場合もしくは本約款に定めのない事項が生じた場合は、本契約当事者間にて誠意をもってこれを協議の上、解決するものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 212 , mb_convert_encoding( "第13条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 210 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(174, 3.2, mb_convert_encoding( "(個人情報の取り扱いについて)
本約款に基づき取得した個人情報は、本サービスを提供するために利用し、お客様本人の承諾なく第三者に開示、提供を行わないこととします。
2.	当社は、個人情報の保護に関する法律、関係各庁が定めるガイドラインならびに各種プライバシーに関する法令を遵守するものとしま　す。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 228.5 , mb_convert_encoding( "第14条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 226.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(174, 3.2, mb_convert_encoding( "(約款の改訂)
当社は、お客様の承諾を得ることなく、本約款を変更することができるものとし、当社およびお客様は、変更後の本約款に拘束されるものとします。
なお、変更後の約款に承諾できない場合は、第8条に基づき、解約手続きを行うものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 242.5 , mb_convert_encoding( "第15条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 240.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(174, 3.2, mb_convert_encoding( "(管轄裁判所)
本約款に起因した紛争の解決については、東京地方裁判所を第一審の専属的管轄裁判所とします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "5/7" );


//6ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 70 , 30  , mb_convert_encoding( "無制限プランに関する同意書", "SJIS", "UTF-8" ) );

$pdf->SetXY( 17, 35 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "本書は、キレイモ全身脱毛無制限プラン、および平日とく得無制限プラン（以下、「無制限プラン」といいます）に関する諸注意事項等を明記したものになります。本書は、概要書面及びエステティックサービス約款に付随し、一体となって契約内容
となります。以下を確認のうえ、同意いただいた上でお申し込みください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 45 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(181, 7.5, mb_convert_encoding( "
□　支払い方法は、現金払い、クレジットカード払い、当社指定金融機関へのお振込、もしくは当社と提携するローン会社の
　　立替払いをご選択いただけます。また、複数の方法を組み合わせてお支払いいただくことも可能です。
□　無制限プランの契約期間（返金保証期間）は、「エステティックサービス契約書」記載の契約期間とし、期間満了をもっ
　　て終了となります。
□　無制限プランでは、施術（無断キャンセル・当日キャンセルによる消化を含む）と施術との間隔を以下の通りとさせてい
　　ただきます。
		1回目〜12回目：45日以上
		13回目〜18回目：60日以上
		19回目以降：90日以上
　　　		例）13回目の施術予約は、12回目の施術日から61日後より取得可能

□　無断キャンセル・当日キャンセル等により役務消化扱いとなった場合、原則として次回のご予約日は、キャンセル対象日
　　の翌日から上記の規定日数経過日以降となりますのでご注意ください。
□　無制限プランをご契約のお客様に対しては、契約期間終了後も、当社の定める条件の下で、期間・回数とも無制限、かつ
　　無償の役務（以下、「SPサービス」という）を提供いたします。
□　平日とく得プランに適用される制限時間帯（予約取得ができない時間帯）は、SPサービスのご予約についても適用されま
　　すので、予めご了承ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 175 );
$pdf->MultiCell(181, 7.5, mb_convert_encoding( "
【SPサービス概要】
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 181 );
$pdf->MultiCell(181, 7.5, mb_convert_encoding( "
□　SPサービスとは、無制限プランをご契約のお客様に対し、契約期間満了後も、当社の定める条件の下で、期間・回数とも
　　無制限、かつ無償の役務を提供するサービスです。
□　SPサービスは、フォトフェイシャルのみでのご利用をお断りさせていただいております。
□　契約書に定める役務の指定回数が未消化の状態で契約期間満了となったお客様については、契約期間満了日の翌日から900
　　日間に限り、規定の周期でSPサービスをご利用いただけます。
□　契約期間満了日の翌日から900日が経過した後については、最短で90日に１回のご利用周期となりますので、その旨ご了承
　　ください。
□　SPサービスのご予約を当日キャンセル・無断キャンセルされた場合、それらの累計回数（※）に応じて以下の対応をさせ
　　ていただきます。
", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "6/7" );


//7ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetXY( 17, 10 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 7, mb_convert_encoding( "
・	累計1回の場合、マイページ等で予約取得を行なった日から90日間の予約不可
・	累計2回の場合、180日間の予約不可（起算日については同上）
・	累計3回の場合、エステティックサービス利用契約の解除
　※当日キャンセル1回、無断キャンセル1回の場合、累計回数は2回となります。

□　また、SPサービスについては、最終の施術日から起算して365日間、継続的に予約のない場合、エステティックサービス利
　　用契約を解除させていただきます。
□　出産等の事情により365日以上の期間にわたって（SPサービスを含む）施術を受けられない場合には、店舗スタッフまたは
　　KIREIMOコールセンターまでお申し出下さい。
□　契約期間満了後の役務提供は、契約クレジット、ローンの対象債務とはなりません。仮に役務提供が受けられなくても、
　　クレジット契約、ローン契約に基づく支払い停止の抗弁や既払金の返金原因とはなりません（お支払いの方法として
　　クレジット、ローンをご契約のお客様のみ対象となります。）のでご了承下さい。
□　無制限プランは契約期間中の中途解約が可能です。なお、中途解約における解約手数料等は以下のとおりとなります。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 105 );
$pdf->MultiCell(180, 7, mb_convert_encoding( "
              残              金　 =　支払総額 - （1回あたりの料金 × 利用回数）
              解約手数料金額 =　残金×10％（最大￥20,000）
              精      算      金   =　残金 - 解約手数料金額
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 130 );
$pdf->MultiCell(180, 7, mb_convert_encoding( "
□　無制限プランの契約期間満了後に解約された場合、契約期間満了時に未消化の役務については返金の対象外となります
　　のでご了承ください。
□　繁忙期等については、予約が立て込み予約がとりにくくなる場合がございますので、予めご了承ください。
□　施術の効果には個人差がございます。本サービスは特定の効果を保証するものではございません。
　　また、お支払いいただく代金は施術に対するものであり、特定の効果に対するものではございません。
□　本サービスご利用中における損害や怪我、その他の事故について、当社に故意または過失がない場合、その損害に対す
　　る一切の責任を負いません。
□　本書に記載なき事項は、エステティックサービス約款に準拠いたします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->Text( 18 , 210  , mb_convert_encoding( "私は上記の諸注意事項について確認し、同意の上で無制限プランに申込みいたします。
", "SJIS", "UTF-8" ) );
$pdf->Text( 18 , 217 , mb_convert_encoding( substr($old_cancel_date, 0,4)." 年 ".substr($old_cancel_date, 5,2)." 月 ".substr($old_cancel_date, 8,2)." 日", "SJIS", "UTF-8" ) );

$pdf->SetDrawColor(0,0,0);
$pdf->Rect(95 ,225 ,100 ,15);

$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 245  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "7/7" );


$pdf->Output($title."_".$customer_name.$customer['no'].".pdf","I");
?>
