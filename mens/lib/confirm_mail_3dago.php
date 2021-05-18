<?php

/*ご予約の確認に関して(3日前の12時に自動送信),予約時電話OKの場合、送信しない */

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
//4日前20：30配信に変更
$alarm_date = date("Y-m-d",mktime(0, 0, 0, date("m") , date("d")+4, date("Y")));
//$sql = "SELECT c.name,c.name_kana,c.mail,r.shop_id,r.hope_date,r.hope_time FROM reservation as r,customer as c WHERE r.customer_id=c.id and r.del_flg=0 and c.del_flg=0 and r.type=1 and r.hope_date='" . $alarm_date . "'";

//$sql = "SELECT r.id,r.customer_id,c.name,c.name_kana,c.mail,r.shop_id,r.hope_date,r.hope_time FROM reservation as r,customer as c WHERE r.customer_id=c.id and c.mail='ka@vielis.co.jp' limit 1";
$sql = "SELECT r.id,r.customer_id,c.name,c.name_kana,c.mail,r.shop_id,r.hope_date,r.hope_time FROM reservation as r,customer as c WHERE r.customer_id=c.id and r.del_flg=0 and c.del_flg=0 and r.type=1 and r.today_status<>1 and r.hope_date='" . $alarm_date . "'";

$list = Get_Result_Sql_Array($sql);

//未送信があれば送信処理
if( $list = Get_Result_Sql_Array($sql) ){
	foreach ( $list as $key=>$data ) {
		//if($data['mail']=="zaimyma@yahoo.co.jp") continue;
		
		$shop = Get_Table_Row("shop"," WHERE id = '".addslashes($data['shop_id'])."'");
		$staff_name = Get_Table_Col("staff","name"," WHERE status=2 and shop_id = '".addslashes($data['shop_id'])."' order by type limit 1");
		$staff_name = str_replace("　"," ",$staff_name);
		list($staff,$last_name) = explode(" ",$staff_name);

		$hope_date = strtotime($data['hope_date']);
		$md = date("n月j日", $hope_date);
		$d = date("j", $hope_date);
		$yobi = getYobi($data['hope_date'],2);

		$name = $data['name'] ? $data['name']."様" : ($data['name_kana'] ? $data['name_kana']."様" : "");

		$subject = "脱毛エステ【KIREIMO】ご予約確認";
		$content  = $name."\r\n\r\n";
		$content .= 'こんばんは。KIREIMO'.$shop['name'].'の'.$staff.'です。

'.$md.$yobi.'のご予約ありがとうございます。

--
予約日：'.$md.$yobi.$gTime2[$data['hope_time']].'～　
内　容：KIREIMO'.$shop['name'].' 無料カウンセリング

'.$shop['address'].'
0120-444-680
'.$home_url.'img/map_lp/'.$shop_map[$shop['id']].'

詳しい地図はこちらを御覧ください。
'.$home_url.'img/map_lp/'.$shop_map2[$shop['id']].'

●●●●●ご予約の変更・キャンセル●●●●●

ご予約の変更の場合はこちら
http://kireimo.jp/counseling/?id='.$data['customer_id'].'&rid='.$data['id'].'&act=change

ご予約のキャンセルの場合はこちら
http://kireimo.jp/counseling/?id='.$data['customer_id'].'&rid='.$data['id'].'&act=cancel

※ご予約の時間をすぎる場合はコールセンター(0120-444-680)へご連絡をお願い致します。

'.($name ? $name."の" : "").'ご来店、スタッフ一同心よりお待ち申し上げております。

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
	sleep(5);
}	
?>