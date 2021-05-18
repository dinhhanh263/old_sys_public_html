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

$pdf->SetXY( 110, 135 );
$pdf->SetFont( KOZMIN,'' , 12 );
$pdf->SetLineWidth(0.5);
$pdf->Cell(40, 10, mb_convert_encoding( "御支払額", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Cell(45, 10, mb_convert_encoding( number_format($contract['price']), "SJIS", "UTF-8" ), 1,0,"R");


$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Text( 16 , 159  , mb_convert_encoding( "2．支払方法", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 160 );
$pdf->SetLineWidth(0);
$pdf->Cell(135, 5, mb_convert_encoding( "お支払い方法", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "金額", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "入金日", "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 165 );
$pdf->Cell(6, 5, mb_convert_encoding( "1", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "現金", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($contract['payment_cash']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($contract['payment_cash'] ? str_replace("-", "/",$old_contract['cancel_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 170 );
$pdf->Cell(6, 5, mb_convert_encoding( "2", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "カード", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($contract['payment_card']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($contract['payment_card'] ? str_replace("-", "/",$old_contract['cancel_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 175 );
$pdf->Cell(6, 5, mb_convert_encoding( "3", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "銀行振込", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($contract['payment_transfer']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($contract['payment_transfer'] ? str_replace("-", "/",$old_contract['cancel_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 180 );
$pdf->Cell(6, 5, mb_convert_encoding( "4", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "ローン", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($contract['payment_loan']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($contract['payment_loan'] ? str_replace("-", "/",$old_contract['cancel_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");

$pdf->SetXY( 15, 185 );
$pdf->Cell(6, 5, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( ($contract['balance'] ? "残金" : ""), "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( ($contract['balance'] ? number_format($contract['balance']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");

/*$pdf->SetFillColor(238, 233, 233);
$pdf->Rect( 15 ,145 ,165 ,5, 'DF');*/

$pdf->SetXY( 15, 190 );
$pdf->Cell(135, 5, mb_convert_encoding( "合計", "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($contract['payment_cash'] + $contract['payment_card'] + $contract['payment_transfer'] + $contract['payment_loan'] + $contract['balance']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( "-", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 195 );
$pdf->Cell(180, 5, mb_convert_encoding( "前払い金の保全措置：なし", "SJIS", "UTF-8" ), 1,0,"L");

$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Text( 17 , 1203  , mb_convert_encoding( "※クレジットご利用の場合、抗弁権の接続ができます。", "SJIS", "UTF-8" ) );


$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->Text( 15 , 215  , mb_convert_encoding( "3．中途解約について", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 218 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "プラン組替後、当社所定の手続きにより契約を解約することができます。但し関連商品のみの解約はできません。
解約時の返金額等の算出方法としては、プラン1回あたりの料金である補正単価（契約金額を契約回数で除した結果が割り切れない場合1円未満を四捨五入）に利用回数をかけた金額を消化額とし、支払総額から消化額を引いた
金額を残金とします。解約手数料金額として残金の10%（最大￥20,000）を差し引き、精算金を算出いたします。


", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "1/5" );


//2ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 5.5, mb_convert_encoding( "
以下に、残金・解約手数料・精算金の算出方法を記載いたします。

    残　　　　　金　=　支払総額 - ( 1回あたりの料金 × 利用回数 )
    解約手数料金額  = ｛ 支払総額  -  ( 1回あたりの料金   ×   利用回数 )  ｝×10% （最大￥20,000）
    清算金  =  残金  -  解約手数料金額

お支払いの方法としてローンをご契約のお客様につきましては、中途解約の場合、上記解約手数料金額に加えて、
ローンキャンセル手数料がお客様のご負担となります。なお、この場合の精算金は以下になります。

    精　　算　　金　=　残金 - 解約手数料金額 - ローンキャンセル手数料
    
※ローンキャンセル手数料の算出方法につきましては、ローン会社の規定に従い算出させていただきます。
※複数箇所でコース1回分とみなす契約（全身パック、月額プラン、キャンペーン時におけるセットのコース）に
　つきましてはコースに含まれる箇所を1箇所でもお手入れした場合、コース1回分を消化したとみなし、解約時の
　返金対象とはなりません。
（例）全身コース6回契約時、ヒザ下1回のみお手入れ後、解約を希望した場合の返金対象は、全身5回分となり
　　　ます。
※現金の受け渡しによる返金は行っておりません。金融機関への振込とさせて頂きます。
※プラン組替後の単価で、解約手数料を計算いたします。

4．その他
本通知書に記載なき事項は、エステティック契約約款に準じます。

私は、上記内容および契約約款を確認し、上記プランに組替することに同意いたします。

", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 20 , 155  , mb_convert_encoding( substr($old_cancel_date, 0,4)." 年 ".substr($old_cancel_date, 5,2)." 月 ".substr($old_cancel_date, 8,2)." 日", "SJIS", "UTF-8" ) );

$pdf->Rect(95 ,160 ,100 ,15);
$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 177  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
//$pdf->Text( 17 , 190  , mb_convert_encoding( "上記内容を確かに受け取りました。", "SJIS", "UTF-8" ) );

$pdf->Image("../../img/stamp.png",155,200,24); //横幅のみ指定,24

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
$pdf->Text( 100 , 285  , "2/5" );


//3ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 73 , 10  , mb_convert_encoding( "エステティックサービス約款", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 15  , mb_convert_encoding( "第1条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 12.8 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(本約款の適用範囲）
株式会社ヴィエリス（以下「当社」といいます）は、エステティックサービス約款（以下「本約款」といいます）に基づき、エステ
ティックサービス（以下「本サービス」といいます）を提供するものとします。
2．当社が本約款以外に定める「概要書面（事前説明書）」、「エステティックサービス契約書」、「KIREIMOのご案内」、「エス
　テティックサービス契約　ご契約内容チェックシート」、「除毛・減毛トリートメント同意書」およびその他、当社が定めるもの
　（以下これらを総称して「個別約款」といいます）は、本約款の一部を構成するものとし、本約款と個別約款の定めが異なる場合、
　別段の定めがない限り、個別約款の定めが優先して適用されるものとします。

", "SJIS", "UTF-8" ) , 0, 'L', 0);
$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 40  , mb_convert_encoding( "第2条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 37.2 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(契約の成立)
お客様が当社の定めるエステティックサービス契約書（以下「本契約書」といいます）の記載内容、本約款および個別約款に承諾の上、
本サービスにお申し込みをし、当社がこれを承諾したことによって、エステティックサービス契約（以下「本契約」といいます）が成
立いたします。
2. お客様が未成年の場合は、前項の手続きに合わせて当社所定の親権者同意書にて親権者の同意を当社が確認した上で、本契約の成立
　となります。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 61  , mb_convert_encoding( "第3条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 58.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(役務内容)
当社はお客様に対し、本契約書に記載する月額プランまたはパックプランおよびその回数の本サービスを提供するものとします。なお、
月額プラン、パックプランの詳細は以下のとおりとします。
（1）月額プランとは、2ヶ月間に1度（以下「当該期間」といいます）、本サービスの提供を受けることができるコースです。
（2）パックプランとは、本契約書に定める契約期間中に指定する回数の本サービスの提供を受けることができるコースです。
2. 本サービスの提供を受けるために予約が必要となりますので、当社所定の方法により予約手続きをしていただきます。なお、ご希望
　の予約日が月末、繁忙期と重なる場合、ご希望する日時の予約が取れず、前項第1号の当該期間内に本サービスを提供ができない場
　合がございますので、予めご了承ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 89  , mb_convert_encoding( "第4条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 86.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(本サービスの料金、支払時期、支払方法)
お客様に提供する本サービスの料金、および販売する関連商品等の料金は本契約書に明記します。
2. 本サービスの料金の支払方法は以下のとおりといたします。
（1）月額プランは、1回目の本サービス料金を本契約の契約日にお支払いいただきます。
2回目以降の支払いは、契約時に当社所定の継続手続きを行っていただき、ご契約の翌月より毎月クレジットカード決済もしくは金融機関
口座振替払いのいずれかになります。
（2）パックプランは、現金一括払い、クレジットカード一括払いもしくは当社と提携するローン会社の立替払いのいずれかになります。
3．前項に定める支払方法のうち、金融機関口座振替払いの方で金融機関の決済が取れなかった場合、その月末までにお支払いがない限
　り、すでに予約されている次回以降の予約が取り消しとなります。
4. お客様が第2項第2号において、現金一括払いを選択した場合、本契約の契約日に本サービス料金総額のうちの一部の手付金を納付す
　るものとし、残額は本契約の契約日より90日以内に支払うものとします。また、契約日から起算して90日以内にお支払いがない場合、
　お客様は本契約を解約する意思表示を示したものとし、当社はこれを受け、本契約を解約いたします。また、この場合、お客様が納
　付した手付金の返還を放棄したものとみなします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 133  , mb_convert_encoding( "第5条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 130.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(176, 3.2, mb_convert_encoding( "(本サービスの提供期間および期間終了後の措置)
本サービスの提供期間は、本契約書に記載された契約期間とします。なお、月額プランは、契約期間終了日の2ヶ月前の末日までに、
当社へ契約終了の申し出がない場合、本契約は更に2ヶ月間更新し、以後も同様とします。
2．パックプランにおいて、契約期間内に本サービスの提供が全て受けられなかった場合の措置として、当社所定の方法により申請して
　いただいたお客様に限り、契約期間終了日より2年間（以下「保証延長期間」といいます）、残回数分の本サービス提供を受けること
　ができます。
　なお、保証延長期間中に解約される場合は、第7条の定めに従うものとします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetDrawColor(255,0,0);
$pdf->Rect(13 ,155 ,188 ,84);
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->SetTextColor(255, 0, 0);
$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 15 , 159  , mb_convert_encoding( "第6条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 156.5 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(180, 3.2, mb_convert_encoding( "(クーリング・オフ)
お客様は、契約書面を受領した日から起算して8日間以内であれば、書面により本契約を解除することができます。ただし、プラン組替
の場合は、本条は適用外となります。
2. 当社がお客様に不実のことを告げ、または威迫等によりクーリング・オフが妨害された場合、当社は改めてクーリング・オフができる
 旨を記載した書面を受領し、当社より説明を受けた日から起算して8日間以内であれば、書面によるクーリング・オフをすること
 ができます。
3. 前二項に基づく解除がなされた場合、関連商品販売契約についても、その契約を解除することができます。但し、関連商品を開封した
　り、その全部もしくは一部を消費したりした場合、当該商品に限りクーリング・オフすることはできません。関連商品の引き渡しが既
 に行われている場合は、当該関連商品の引き取りに要する費用は当社の負担とします。
4. クーリング・オフは、お客様がクーリング・オフの書面を当社宛てに発信した時に、その効力が生じます。クレジットを利用した契
　約の場合、お客様は当社に契約の解除を申し出た旨をクレジット会社にも別途書面による通知をしていただく必要がございます。
5. 本条による契約解除については、違約金及び利用した本サービスの料金の支払いは不要とし、当社はお客様から現金一括払い・クレ
ジットカード決済・金融機関口座振替等により受領した前受金及び関連商品販売に関し金銭を受領している場合には、当該金銭につき
速やかにお客様の金融機関口座に振り込みにより返還するものとします。なお、当該金銭を返還する際の費用は当社の負担とします。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 26, 202 );
$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->MultiCell(162, 3.2, mb_convert_encoding( "
                                                             クーリング・オフ(契約解除)の文例
20○○年〇月○日、貴社〇〇店との間で締結したエステティックサービス契約について、約款第6条に基づき契約を解除し
ます。つきましては、私が貴社に支払った代金○○○円を下記銀行口座に振り込んでください。（また、私が受け取った商品
をお引き取りください）
○○銀行〇○支店　普通預金口座○○○○　口座名義人　○○○○
20○○年〇月〇日
契約者 (住所)
            (氏名)　　　　　　　　　    　印
".
	// ($shop['id']==6 && $old_cancel_date<'2015-01-04' ? "株式会社CKR　代表者　大澤　美加" : "株式会社ヴィエリス　代表者　".$kireimo_ceo)."殿
       "株式会社ヴィエリス　代表者　".$kireimo_ceo."殿

", "SJIS", "UTF-8" ) , 1, 'L', 0);


$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "3/5" );


//4ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 60 , 30  , mb_convert_encoding( "SP（スペシャル）プランに関する同意書", "SJIS", "UTF-8" ) );

$pdf->SetXY( 17, 40 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 5, mb_convert_encoding( "本書は、SP（スペシャル）プラン（以下「本プラン」という）に関する諸注意事項等を明記したものになります。
本書は、概要書面およびエステティックサービス利用約款に付随し、一体となって契約内容となります。以下を確認のうえ、本プランに同意ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 56 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(181, 7, mb_convert_encoding( "
□　本プランの契約期間は、「エステティックサービス契約書」記載の契約期間とし、期間満了をもって終了となります。
       本プランは、契約期間終了後も期間・回数とも無制限で、無償にて役務提供いたします。但し、当該役務提供を受
       けることができる対象者は、現金一括払い、クレジットカード一括払いもしくは当社と提携するローン会社の立替払い
       のいずれかの支払が完了しており、かつ、概要書面およびエステティックサービス契約書に定める契約期間中に指定す
       る回数の本サービスの提供を受けたお客様のみになります。
□　本プランは、現金一括払い、クレジットカード一括払いもしくは当社と提携するローン会社の立替払いのいずれかに
       なります。
□　契約期間満了後の役務提供は、契約クレジット、ローン会社の対象期間とはなりませんので、仮に役務提供が受けられ
       なくても、クレジット契約、ローン契約に基づく支払い停止の抗弁や既払金の返金原因とはなりません。
       （お支払いの方法としてクレジット、ローンをご契約のお客様のみ対象となります。）
□　本プランは契約期間中の中途解約は可能になります。なお、中途解約における解約手数料等は以下のとおりとなります。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 143 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 7, mb_convert_encoding( "
              残              金　 =　支払総額金 - （1回あたりの料金 × 利用回数）
              解約手数料金額 =　残金×10％（最大￥20,000）
              精      算      金   =　残金 - 解約手数料金額
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 17, 170 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(180, 7, mb_convert_encoding( "
□　本プランは、契約期間が過ぎた場合、契約書に定める役務の指定回数が未消化であっても、返金の対象外となります。
□　契約期間内までは最短2ヶ月に1回マイページにて予約、契約期間終了後の来店の場合は3ヶ月に1回電話予約とさせて
       いただきます。
       なお、契約期間終了後の来店予約は90日空けて予約いただきます。
□　繁忙期等については、予約が立て込み予約がとりにくくなる場合がございますので、予めご了承ください。
□　契約期間終了後以降はパーツでの施術が可能です。
       契約期間内まではパーツ施術の場合、全身1回分消化となりますので、全身の施術でのご来店をお勧めいたします。
□　本書に記載なき事項は、エステティックサービス利用約款に準拠いたします。

", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 17 , 243  , mb_convert_encoding( "<ローンをお申込みのご契約者様>", "SJIS", "UTF-8" ) );

$pdf->SetXY( 17, 239 );
$pdf->SetFont( KOZMIN,'' , 9 );

$pdf->MultiCell(182, 7, mb_convert_encoding( "
□　初回の施術はご契約の店舗にご来店くださいますようお願いいたします。
□　初回の施術時までに口座情報と銀行お届け印をご持参いただけなかった場合、当日の施術が出来かねますのでご了承ください。
", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "4/5" );


//5ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetXY( 17, 20 );
$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->MultiCell(181, 7, mb_convert_encoding( "
□　ローンはローン会社の審査により、契約ができない場合がございます。予めご了承ください。
□　ローンをお申込みの場合、別途分割手数料を頂戴します。
□　ローンをお申込みの場合、支払い方法、回数の変更は致しかねます。
□　ご指定頂いた日時にお申込みのローン会社から契約内容、ご本人確認の電話がございますのでご対応お願いいたします。
□　ローンを中途解約される場合、ローンキャンセル手数料はお客様のご負担となります。
", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 18 , 70  , mb_convert_encoding( "以上、同意のうえ、私はSP（スペシャル）プランに申込みいたします。", "SJIS", "UTF-8" ) );
$pdf->Text( 18 , 75  , mb_convert_encoding( substr($old_cancel_date, 0,4)." 年 ".substr($old_cancel_date, 5,2)." 月 ".substr($old_cancel_date, 8,2)." 日", "SJIS", "UTF-8" ) );

$pdf->SetDrawColor(0,0,0);
$pdf->Rect(95 ,85 ,100 ,15);

$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 102  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );


$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "5/5" );


$pdf->Output($title."_".$customer_name.$customer['no'].".pdf","I");
?>
