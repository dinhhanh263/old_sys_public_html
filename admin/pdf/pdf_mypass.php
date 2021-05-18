<?php
require_once(dirname(__FILE__) . '/../../php-lib/fpdf/mbfpdf.php');
require_once(dirname(__FILE__) . '/../../admin/library/pdf/pdf_mypass.php');

header('Content-type: application/pdf');
header('Content-disposition: attachment; filename="downloaded.pdf"');

$title = "会員ページのアカウント情報";
$msg = "KIREIMOサービスをご利用頂き、誠にありがとうございます。\n下記の内容が会員ページのユーザーアカウントになります。\nご確認くださいますよう、お願いいたします。";
$shop = "https://kireimo.jp\n\n株式会社ヴィエリス\nKIREIMO ".$shop['name']."\n".$shop['address']."\nTEL:0120-444-680\nEmail:info@kireimo.jp";

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
//$pdf->SetFont( GOTHIC, 'U', 20 );//underline
//$pdf->SetFont( GOTHIC, 'A', 20 );//download

$pdf->Rect(15 ,18 ,180 ,13);
$pdf->SetFont( GOTHIC,'' , 18 );
//$pdf->Write( 10, mb_convert_encoding( $msg, "SJIS", "UTF-8" ) );
$pdf->Text( 63 , 27  , mb_convert_encoding( $title, "SJIS", "UTF-8" ) );

$pdf->SetFont( GOTHIC,'' , 14 );
$pdf->Text( 24 , 45  , mb_convert_encoding( $customer['name']."　様", "SJIS-win", "UTF-8" ) );

$pdf->SetXY( 24, 60 );
$pdf->SetFont( GOTHIC,'' , 8 );
$pdf->MultiCell( 80 , 4  , mb_convert_encoding( $msg, "SJIS", "UTF-8" ) ,0);


$pdf->Image("img/logo.png",140,40,20); //横幅のみ指定

$pdf->SetXY( 140, 50 );
$pdf->SetFont( GOTHIC,'' , 8 );
$pdf->MultiCell( 80 , 4  , mb_convert_encoding( $shop, "SJIS", "UTF-8" ) ,0);


$pdf->Image("img/QRcode.gif",140,80,20); //横幅のみ指定

$pdf->SetFillColor(238, 233, 233);
//$pdf->Rect( 15 ,105 ,180 ,10, 'DF');
$pdf->SetFont( GOTHIC, '', 16 );
//$pdf->Text( 20 , 112  , mb_convert_encoding( "ユーザーアカウント情報", "SJIS", "UTF-8" ) );

$pdf->SetXY( 15, 140 );
$w1 = 55;
$w2 = 125;
$pdf->Cell(180, 13, mb_convert_encoding( "ユーザーアカウント情報", "SJIS", "UTF-8" ), 1,0,'C',1);
$pdf->SetXY( 15, 153 );
$pdf->Cell($w1, 13, mb_convert_encoding( "お客様コード", "SJIS", "UTF-8" ), 1);
$pdf->Cell($w2, 13, mb_convert_encoding( wordwrap($customer['no'],1,' ',true), "SJIS", "UTF-8" ), 1, 1,'L');

$pdf->SetXY( 15, 166 );
$pdf->SetFont( GOTHIC, '', 16 );
$pdf->Cell($w1, 13, mb_convert_encoding( "仮パスワード", "SJIS", "UTF-8" ), 'L');
$pdf->SetFont( GOTHIC, '', 14 );
$pdf->Cell($w2, 13, mb_convert_encoding( wordwrap($customer['password'],1,' ',true), "SJIS", "UTF-8" ), 1, 1,'L');

$pdf->SetXY( 15, 179 );
$pdf->SetFont( GOTHIC, '', 12 );
$pdf->Cell($w1, 13, mb_convert_encoding( "", "SJIS", "UTF-8" ), 'L');
$pdf->SetFont( GOTHIC, '', 9 );
// 4行表示
if ( 226 < mb_strwidth($mp_kana, "UTF-8")) {
	$pdf->MultiCell( $w2 , 3.3  , '( '.mb_convert_encoding( $mp_kana.' )', "SJIS", "UTF-8" ) ,1);
} // 3行表示
else if( 149 < mb_strwidth($mp_kana, "UTF-8")){
	$pdf->MultiCell( $w2 , 4.4  , '( '.mb_convert_encoding( $mp_kana.' )', "SJIS", "UTF-8" ) ,1);
} // 2行表示
else if( 74 < mb_strwidth($mp_kana, "UTF-8")){
	$pdf->MultiCell( $w2 , 6.5  , '('.mb_convert_encoding( $mp_kana.' )', "SJIS", "UTF-8" ) ,1);
} else {
	$pdf->Cell($w2, 13, '( '.mb_convert_encoding( $mp_kana.' )', "SJIS", "UTF-8" ),1);
}


$pdf->SetXY( 15, 192 );
$pdf->SetFont( GOTHIC,'' , 11 );
$pdf->Cell(180, 13, mb_convert_encoding( "会員ページ".wordwrap("URL: https://mypage.kireimo.jp/",1,' ',true), "SJIS", "UTF-8" ), 1);
//$pdf->SetFont( GOTHIC,'' , 10 );
//$pdf->Text( 15 , 210  , mb_convert_encoding( "※ログインできない場合、次の入力をご確認ください。", "SJIS", "UTF-8" ) );
//$pdf->Text( 15 , 215  , mb_convert_encoding( "「1」(数字イチ)と「ｌ」(小文字エル)と「Ｉ」(大文字アイ)、「0」(数字ゼロ)と「o」(小文字オー)など。", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "1/3" );

//2ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 16 , 20  , mb_convert_encoding( "■設定手順", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Text( 16 , 26  , mb_convert_encoding( "step1.「カメラ」アプリを起動する", "SJIS", "UTF-8" ) );
$pdf->Image("../../zstepimg/step1.png",15,30,65); //横幅のみ指定

$pdf->Text( 110 , 26  , mb_convert_encoding( "step2.「カメラ」アプリでQRコードを読み取る", "SJIS", "UTF-8" ) );
$pdf->Image("../../zstepimg/step2.png",110,30,65); //横幅のみ指定

$pdf->Text( 16 , 155  , mb_convert_encoding( "step3.読み取ったURLを開く", "SJIS", "UTF-8" ) );
$pdf->Image("../../zstepimg/step3.png",15,160,65); //横幅のみ指定

$pdf->Text( 110 , 155  , mb_convert_encoding( "step4.会員ページが表示されたら「共有」アイコンをタップ", "SJIS", "UTF-8" ) );
$pdf->Image("../../zstepimg/step4.png",110,160,65); //横幅のみ指定

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "2/3" );

//2ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 16 , 20  , mb_convert_encoding( "■設定手順", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Text( 16 , 26  , mb_convert_encoding( "step5.共有メニューから「ホーム画面に追加」を選んでタップ", "SJIS", "UTF-8" ) );
$pdf->Image("../../zstepimg/step5.png",15,30,65); //横幅のみ指定

$pdf->Text( 110 , 26  , mb_convert_encoding( "step6.ホーム画面から追加されたアイコンをタップ", "SJIS", "UTF-8" ) );
$pdf->Image("../../zstepimg/step6.png",110,30,65); //横幅のみ指定

$pdf->Text( 16 , 155  , mb_convert_encoding( "step7.お客様コードとパスワードを入力してログイン", "SJIS", "UTF-8" ) );
$pdf->Image("../../zstepimg/step7.png",15,160,65); //横幅のみ指定

$pdf->Text( 110 , 155  , mb_convert_encoding( "step8.お客様情報の「確認・変更」でパスワードを変更", "SJIS", "UTF-8" ) );
$pdf->Image("../../zstepimg/step8.png",110,160,65); //横幅のみ指定

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "3/3" );

////////////////////////////////////////////////////
$pdf->Output();
?>
