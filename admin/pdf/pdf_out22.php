<?php

ini_set( 'include_path', dirname(__FILE__) . "/../../php-lib/fpdf" );
require_once( 'mbfpdf.php');
require_once( '../../config/config.php');

header('Content-type: application/pdf');
header('Content-disposition: attachment; filename="download.pdf"');

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

//2ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 16 , 20  , mb_convert_encoding( "■備考", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 22);
$pdf->MultiCell(180, 60, mb_convert_encoding( $_GET['memo'], "SJIS", "UTF-8" ), 1,"T");


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
$pdf->Cell(129, 5, mb_convert_encoding( "ローン", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['payment_loan']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($_GET['payment_loan'] ? str_replace("-", "/",$_GET['hope_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 110 );
if($_GET['payment_coupon']){
$pdf->Cell(6, 5, mb_convert_encoding( "4", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "クーポン", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['payment_coupon']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($_GET['payment_coupon'] ? str_replace("-", "/",$_GET['hope_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 115 );
$pdf->Cell(6, 5, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( $balance_name, "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( ($_GET['balance'] ? number_format($_GET['balance']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
}else{
$pdf->Cell(6, 5, mb_convert_encoding( "4", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( $balance_name, "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( ($_GET['balance'] ? number_format($_GET['balance']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 115 );
$pdf->Cell(6, 5, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");	
}

$pdf->SetXY( 15, 120 );
$pdf->Cell(6, 5, mb_convert_encoding( "6", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 125 );
$pdf->Cell(6, 5, mb_convert_encoding( "7", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 130 );
$pdf->Cell(6, 5, mb_convert_encoding( "8", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 135 );
$pdf->Cell(6, 5, mb_convert_encoding( "9", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 140 );
$pdf->Cell(6, 5, mb_convert_encoding( "10", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");

$pdf->SetFillColor(238, 233, 233);
$pdf->Rect( 15 ,145 ,135 ,5, 'DF');

$pdf->SetXY( 15, 145 );
$pdf->Cell(135, 5, mb_convert_encoding( "合計", "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['price']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( "-", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 150 );
$pdf->Cell(180, 5, mb_convert_encoding( "前払い金の保金処置：なし", "SJIS", "UTF-8" ), 1,0,"L");


$pdf->SetFont( KOZMIN,'' , 7 );
$pdf->Text( 17 , 158  , mb_convert_encoding( "※クレジットご利用の場合、抗弁権の接続ができます。詳細については契約書を良くお読み下さい。", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 162  , mb_convert_encoding( "前受け金の保全措置はありません。", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 17 , 170  , mb_convert_encoding( "3.特約事項：なし", "SJIS", "UTF-8" ) );
$pdf->Text( 17 , 180  , mb_convert_encoding( "4.契約の解除に関する事項：クーリングオフ並びに中途解約につきましては、概要書面の該当欄をご確認ください。", "SJIS", "UTF-8" ) );


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
$pdf->Text( 100 , 285  , "2/9" );

//5ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 16 , 20  , mb_convert_encoding( "■備考", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'' , 10 );
$pdf->SetXY( 15, 22);
$pdf->MultiCell(180, 60, mb_convert_encoding( $_GET['memo'], "SJIS", "UTF-8" ), 1,"T");


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
$pdf->Cell(129, 5, mb_convert_encoding( "ローン", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['payment_loan']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($_GET['payment_loan'] ? str_replace("-", "/",$_GET['hope_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 110 );
if($_GET['payment_coupon']){
$pdf->Cell(6, 5, mb_convert_encoding( "4", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "クーポン", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['payment_coupon']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( ($_GET['payment_coupon'] ? str_replace("-", "/",$_GET['hope_date']) : ""), "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 115 );
$pdf->Cell(6, 5, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( $balance_name, "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( ($_GET['balance'] ? number_format($_GET['balance']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
}else{
$pdf->Cell(6, 5, mb_convert_encoding( "4", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( $balance_name, "SJIS", "UTF-8" ), 1,0,"L");
$pdf->Cell(20, 5, mb_convert_encoding( ($_GET['balance'] ? number_format($_GET['balance']) : ""), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 115 );
$pdf->Cell(6, 5, mb_convert_encoding( "5", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");	
}

$pdf->SetXY( 15, 120 );
$pdf->Cell(6, 5, mb_convert_encoding( "6", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 125 );
$pdf->Cell(6, 5, mb_convert_encoding( "7", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 130 );
$pdf->Cell(6, 5, mb_convert_encoding( "8", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 135 );
$pdf->Cell(6, 5, mb_convert_encoding( "9", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");
$pdf->SetXY( 15, 140 );
$pdf->Cell(6, 5, mb_convert_encoding( "10", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(129, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(20, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->Cell(25, 5, mb_convert_encoding( "", "SJIS", "UTF-8" ), 1,0,"L");

$pdf->SetFillColor(238, 233, 233);
$pdf->Rect( 15 ,145 ,135 ,5, 'DF');

$pdf->SetXY( 15, 145 );
$pdf->Cell(135, 5, mb_convert_encoding( "合計", "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(20, 5, mb_convert_encoding( number_format($_GET['price']), "SJIS", "UTF-8" ), 1,0,"R");
$pdf->Cell(25, 5, mb_convert_encoding( "-", "SJIS", "UTF-8" ), 1,0,"C");
$pdf->SetXY( 15, 150 );
$pdf->Cell(180, 5, mb_convert_encoding( "前払い金の保金処置：なし", "SJIS", "UTF-8" ), 1,0,"L");

$pdf->Rect(95 ,160 ,100 ,15);
$pdf->SetFont( KOZMIN,'U' , 8 );
$pdf->Text( 80 , 177  , mb_convert_encoding( "署名欄    　　　　　　　　　　　    　　　　　　                                                                  ", "SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 17 , 190  , mb_convert_encoding( "上記内容にてを確かに受け取りました。", "SJIS", "UTF-8" ) );


$pdf->Image("../../img/stamp.png",155,200,24); //横幅のみ指定,24

$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Text( 103 , 208  , mb_convert_encoding( "(乙)会  社  名", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 122 , 208  , mb_convert_encoding( "株式会社　ヴィエリス", "SJIS", "UTF-8" ) ); 

$pdf->SetFont( KOZMIN,'' , 8 );
$pdf->Text( 107 , 212  , mb_convert_encoding( "所  在  地", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 212  , mb_convert_encoding( $company_zip_code, "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 215  , mb_convert_encoding( $company_address, "SJIS", "UTF-8" ) );

$pdf->Text( 107 , 219  , mb_convert_encoding( "電話番号", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 219  , mb_convert_encoding( "TEL: ".$company_tel_no."　FAX: ".$company_fax_no, "SJIS", "UTF-8" ) );

$pdf->Text( 107 , 223  , mb_convert_encoding( "代  表  者", "SJIS", "UTF-8" ) );
$pdf->Text( 122 , 223  , mb_convert_encoding( "代表取締役社長　".$kireimo_ceo, "SJIS", "UTF-8" ) );

$pdf->Text( 107 , 227  , mb_convert_encoding( "サロン名", "SJIS", "UTF-8" ) );
$pdf->SetFont( KOZMIN,'B' , 8 );
$pdf->Text( 122 , 227  , mb_convert_encoding( "KIREIMO", "SJIS", "UTF-8" ) );

$pdf->Image("img/logo.png",20,230,40); //横幅のみ指定,24

$pdf->SetFont( KOZMIN,'U' , 10 );
$pdf->Text( 106 , 235  , mb_convert_encoding( "店  舗  名： ".$_GET['shop_name']."                                                              ", "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 240  , mb_convert_encoding( "電話番号： 0120- 567- 144                                               ", "SJIS", "UTF-8" ) );
$pdf->Text( 106 , 245  , mb_convert_encoding( "担 当  者： ".$_GET['staff']."                                                         ", "SJIS-win", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'' , 9 );
$pdf->Text( 100 , 285  , "5/9" );


$pdf->Output($_GET['name'].$_GET['no'],"I");
//$pdf->Output();
?>
