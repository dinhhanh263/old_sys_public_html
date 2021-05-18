<?php

/*未契約者への送信.status=2*/

$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/mens/config/config.php';
include_once( "./function.php" );
require_once LIB_DIR . 'KagoyaSendMail.php';



//DBに接続してデータ登録
$conn = mysqli_connect(HOST_NAME, DB_UESR, DB_PW);
$GLOBALS['mysqldb']->select_db(DB_NAME,$conn);
$GLOBALS['mysqldb']->query('SET NAMES utf8');

$alarm_date = date("Y-m-d");

//$sql = "SELECT r.id,r.customer_id,c.name,c.name_kana,c.mail,r.shop_id,r.hope_date,r.hope_time FROM reservation as r,customer as c WHERE r.customer_id=c.id and r.del_flg=0 and c.del_flg=0 and r.status<=1 and r.type=1 AND r.hope_date>='2014-03-07' AND r.hope_date<='2014-03-17'";
//$sql = "SELECT r.id,r.customer_id,c.name,c.name_kana,c.mail,r.shop_id,r.hope_date,r.hope_time FROM reservation as r,customer as c WHERE r.customer_id=c.id and c.mail='ka@vielis.co.jp' limit 1";
$sql = "SELECT r.id,r.customer_id,c.name,c.name_kana,c.mail,r.shop_id,r.hope_date,r.hope_time FROM reservation as r,customer as c WHERE r.customer_id <> all (select distinct customer_id from contract where del_flg = 0 ) and  r.customer_id=c.id and r.del_flg=0 and c.del_flg=0 and r.type=1 and r.status=2 and r.hope_date='" . $alarm_date . "'";

$list = Get_Result_Sql_Array($sql);

//送信処理
if( $list = Get_Result_Sql_Array($sql) ){
	foreach ( $list as $key=>$data ) {
		//if($data['mail']=="zaimyma@yahoo.co.jp") continue;
		
		$shop = Get_Table_Row("shop"," WHERE id = '".addslashes($data['shop_id'])."'");
		$name = $data['name'] ? $data['name']."様" : ($data['name_kana'] ? $data['name_kana']."様" : "");
		$tantou = Get_Table_Col("staff","name"," where type=7 and shop_id=".$shop['id']);
		$tantou = str_replace("　", " ", $tantou);
		list($tantou1,$tantou2) = explode(" ",$tantou);

		$subject = '脱毛エステKIREIMO【'.$shop['name'].'】';
		$content  = $name."\r\n\r\n";
		$content .= 'KIREIMO'.$shop['name'].'の'.$tantou1.'です。

本日は、お忙しい中ご来店いただきまして、誠にありがとうございました。

'.$name.'のご希望に沿う事が出来ず申し訳ございませんでした。

KIREIMOでは、脱毛に関する無料相談窓口を設けておりますので、何かお困りの事がございましたら、ご連絡下さいませ。

本日はお忙しい中、ありがとうございました。

・再予約の際は下記再予約フォームよりご予約下さいませ。
http://kireimo.jp/counseling/?id='.$data['customer_id'].'&rid='.$data['id'].'&act=change

その他、ご不明な点、ご質問等ございましたらお気軽に下記までご連絡ください。 

KIREIMOコールセンターフリーダイヤル
0120-444-680

KIREIMO'.$shop['name'].'　
店長 '.$tantou1.'

KIREIMO　HP
http://kireimo.jp/

';

		//自動送信
		mb_language("ja");
		mb_internal_encoding("UTF-8");
		$returnpath = "-f ".SEND_MAIL_ADDRESS;
		$from = "From:".SEND_MAIL_ADDRESS."\n";
		$from.="cc: ".MAIL_KAKUNIN."\n";

		//$from .= "Disposition-notification-to:".MAIL_KAKUNIN."\n"; //開封通知
		// if($data['mail']) mb_send_mail($data['mail'] , $subject,$content,$from,$returnpath);
		if ($data['mail']) {
            $kagoya = new KagoyaSendMail();
            $kagoya->send($data['mail'] , $subject,$content,$from,$returnpath);
        }
		sleep(5);

	}

}	
?>