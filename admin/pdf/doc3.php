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



//8ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );
$pdf->Text( 73 , 24  , mb_convert_encoding( "個人情報のお取扱いについて", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'B' , 12 );
$pdf->Text( 15 , 35  , mb_convert_encoding( "≪個人情報保護方針≫", "SJIS", "UTF-8" ) );
$pdf->SetXY( 17, 38 );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->MultiCell(180, 6, mb_convert_encoding( "株式会社ヴィエリス（以下「当社」といいます）は、お客様に安全で快適なエステティックサービスを提供するにあたり「個人情報の保護に関する法律」他、関係諸法令並びに本方針と当社の各規程を遵守し、お客様の個人情報保護の徹底に努めます。
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

会社名：株式会社ヴィエリス 
屋　号：ＫＩＲＥＩＭＯ 
代表者：代表取締役　".$kireimo_ceo." 
本　社：".$company_address."

お客様相談室：電話　０３－６７２１－１６４１
※ 受付時間は10：00～18：00（土・日・祝日・年末年始を除く）とさせて頂いております。
※ お客様から頂いたお電話は内容確認のため録音させて頂いております。



", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 20 , 240  , mb_convert_encoding( substr($_GET['hope_date'], 0,4)." 年 ".substr($_GET['hope_date'], 5,2)." 月 ".substr($_GET['hope_date'], 8,2)." 日", "SJIS", "UTF-8" ) );


$pdf->SetDrawColor(0,0,0);
$pdf->Rect(95 ,260 ,100 ,15);

$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 277  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );

//$pdf->SetFont( KOZMIN,'' , 9 );
//$pdf->Text( 100 , 285  , "8/9" );

$pdf->Output($_GET['name'].$_GET['no']."(個人情報)","I");
//$pdf->Output();
?>
