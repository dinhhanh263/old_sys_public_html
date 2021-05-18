<?php

$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/mens/config/config.php';
require_once LIB_DIR . 'KagoyaSendMail.php';


/*来店なしへの送信*/

$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/mens/config/config.php';
include_once( "./function.php" );


//DBに接続してデータ登録
$conn = mysqli_connect(HOST_NAME, DB_UESR, DB_PW);
$GLOBALS['mysqldb']->select_db(DB_NAME,$conn);
$GLOBALS['mysqldb']->query('SET NAMES utf8');

$alarm_date = date("Y-m-d");

//$sql = "SELECT c.name,c.name_kana,c.mail,r.shop_id,r.hope_date,r.hope_time FROM reservation as r,customer as c WHERE r.customer_id=c.id and r.del_flg=0 and c.del_flg=0 and r.status<=1 and r.type=1 AND r.hope_date>='2014-03-07' AND r.hope_date<='2014-03-17'";
//$sql = "SELECT r.id,r.customer_id,c.name,c.name_kana,c.mail,r.shop_id,r.hope_date,r.hope_time FROM reservation as r,customer as c WHERE r.customer_id=c.id and c.del_flg=0 and (c.mail='ka@vielis.co.jp' or c.mail='saeki@vielis.co.jp') ";
$sql = "SELECT r.id,r.customer_id,c.name,c.name_kana,c.mail,r.shop_id,r.hope_date,r.hope_time FROM reservation as r,customer as c WHERE r.customer_id <> all (select distinct customer_id from contract where del_flg = 0 ) and r.customer_id=c.id and r.del_flg=0 and c.del_flg=0 and r.type=1 and r.status=1 and r.hope_date='" . $alarm_date . "'";

$list = Get_Result_Sql_Array($sql);

//送信処理
if( $list = Get_Result_Sql_Array($sql) ){
	foreach ( $list as $key=>$data ) {
		//if($data['mail']=="zaimyma@yahoo.co.jp") continue;
		
		$shop = Get_Table_Row("shop"," WHERE id = '".addslashes($data['shop_id'])."'");
		$name = $data['name'] ? $data['name']."様" : ($data['name_kana'] ? $data['name_kana']."様" : "");

		$staff_name = Get_Table_Col("staff","name"," WHERE status=2 and shop_id = '".addslashes($data['shop_id'])."' order by type limit 1");
		$staff_name = str_replace("　"," ",$staff_name);
		list($staff,$last_name) = explode(" ",$staff_name);

		$subject = '脱毛エステKIREIMO【'.$shop['name'].'】';
		$content  = $name."\r\n\r\n";
		$content .= '脱毛サロンKIREIMO'.$shop['name'].'の'.$staff.'です。

この度は、数ある脱毛店の中からKIREIMOにお申込みいただき、誠にありがとうございました。

今回は残念ながらご都合が合わず、ご来店いただくことが叶いませんでしたが、
KIREIMOではすべてのお客様に満足いただけるよう、様々なキャンペーンなどを企画しておりますので、今後もしお時間がありましたら、ぜひ一度無料のカウンセリングにお越し頂ければ幸いでございます。

＜次回ご来店のご予約方法＞

【お電話】もしくは【こちらのメールに返信】にて、ご連絡をお願い致します。

◆お電話の場合（11時〜21時）
フリーダイヤル
0120-444-680

◆メールの場合
info@kireimo.jp

ご予約の際は下記再予約URLよりご予約下さいませ。
http://kireimo.jp/counseling/?id='.$data['customer_id'].'&rid='.$data['id'].'&act=change

'.($name ? $name."の" : "").'ご来店を、スタッフ一同お待ち致しております。

KIREIMO'.$shop['name'].'　'.$staff.'

◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇

脱毛サロンKIREIMO／キレイモ　'.$shop['name'].'
http://kireimo.jp/

お電話でのお問合せ（11時～20時）
フリーダイヤル：0120-444-680

メールでのお問合せ（24時間OK）
info@kireimo.jp

◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇

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