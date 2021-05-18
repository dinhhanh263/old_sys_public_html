<?php

ini_set( 'include_path', dirname(__FILE__) . "/../../php-lib/fpdf" );
require_once( 'mbfpdf.php');

header('Content-type: application/pdf');
header('Content-disposition: attachment; filename="'.$_GET['name'].'.pdf"');

$pdf=new MBFPDF();
//$pdf->FPDF(Portrait ,mm,A4);//Landscape
$pdf->AddMBFont( GOTHIC , 'SJIS' );
$pdf->AddMBFont( PGOTHIC, 'SJIS' );
$pdf->AddMBFont( MINCHO , 'SJIS' );
$pdf->AddMBFont( PMINCHO, 'SJIS' );
$pdf->AddMBFont( KOZMIN , 'SJIS' );
$pdf->Open();

$today = date("Y年   n月   j日");

//1ページ目---------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont( KOZMIN,'B' , 14 );

$pdf->Text( 73 , 34  , mb_convert_encoding( "特例トリートメント同意書", "SJIS", "UTF-8" ) );


$pdf->SetXY( 15, 55 );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->MultiCell(190, 6, mb_convert_encoding( "ご契約者様（以下、甲という）と株式会社ヴィエリス（以下、乙という）とは別紙のとおり、事前に受けた説明に
基づき、甲乙間において終結されるエステティックサービス（以下、本サービス）に承諾、また同意頂くものであ
るが、本サービスを受ける障害となる事由をお持ちのお客様からの強い要望により、特例で提供を行う為本同意書
を締結するものとする。	", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 15 , 90  , mb_convert_encoding( "第1条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 86.2 );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->MultiCell(186, 6, mb_convert_encoding( "(サービスの提供)
乙は、本サービスを受ける障害となる事由にあたる甲に対し、乙が提供するエステティックサービスを甲が
強く希望した場合行うものとする。
	", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 15 , 113  , mb_convert_encoding( "第2条", "SJIS", "UTF-8" ) );
$pdf->SetXY( 25, 109.2 );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->MultiCell(200, 6, mb_convert_encoding( "(確認、告知)
1. 乙は本サービス提供にあたり、甲の持病、皮膚疾患、治療経歴、アレルギー体質、敏感肌、服用している
   薬、その他本サービスを受ける障害となる事由の有無及び程度をヒアリングし、確認しなければならない。

2. 乙は前項のヒアリングに対して詳細かつ正確に告知しなければならないものとし、禁忌事項（※脱毛・減
   毛トリートメント同意書に記載）に当てはまる本サービスを受ける際に障害となる事由がある場合でも甲
   が強く希望する場合乙は本サービスを提供するものとする。

3. 本サービスを受ける際に障害となる事由にあたる甲により本サービス提供を強く希望された際は、本サー
   ビスにおける一切の異議申し立てをしないものとし、また、本サービス提供後の料金の払い戻し請求を行
   わないものとする。また、乙は甲に対して一切の責任を負わないものとする。
	", "SJIS", "UTF-8" ) , 0, 'L', 0);

$pdf->SetXY( 15, 185 );
$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->MultiCell(208, 6, mb_convert_encoding( "本同意書を一通作成し、乙が原本を甲がその写しを各１通保持する。", "SJIS", "UTF-8" ) , 0, 'L', 0);


$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 160 , 225  , mb_convert_encoding( "{$today}","SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 15 , 240  , mb_convert_encoding( " お客様住所： 〒{$_GET['zip']}  {$_GET['address']}  ","SJIS", "UTF-8" ) );

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 15 , 260  , mb_convert_encoding( " (甲) ","SJIS", "UTF-8" ) );
$pdf->Rect( 15 , 250  ,100 ,15);

$pdf->SetFont( KOZMIN,'B' , 10 );
$pdf->Text( 130 , 260  , mb_convert_encoding( " (乙) 株式会社ヴィエリス ","SJIS", "UTF-8" ) );


//$pdf->Output();
$pdf->Output('特例_'.$_GET['customer_no'].'_'.$_GET['name'],"I");

?>
