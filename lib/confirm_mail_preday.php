<?php

/*ご予約の確認に関して(1日前の20時に自動送信),前日電話OKの場合、送信しない */

$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/config/config.php';
require_once LIB_DIR . 'function.php';
require_once LIB_DIR . 'db.php';
require_once LIB_DIR . 'KagoyaSendMail.php';

$alarm_date = date("Y-m-d",mktime(0, 0, 0, date("m") , date("d")+1, date("Y")));
//$sql = "SELECT c.name,c.name_kana,c.mail,r.shop_id,r.hope_date,r.hope_time FROM reservation as r,customer as c WHERE r.customer_id=c.id and r.del_flg=0 and c.del_flg=0 and r.type=1 and r.hope_date='" . $alarm_date . "'";
//$sql = "SELECT r.id,r.customer_id,c.name,c.name_kana,c.mail,r.shop_id,r.hope_date,r.hope_time FROM reservation as r,customer as c WHERE r.customer_id=c.id and c.mail='ka@vielis.co.jp' limit 1";


$sql = "SELECT r.id,r.customer_id,c.name,c.name_kana,c.mail,r.shop_id,r.hope_date,r.hope_time FROM reservation as r,customer as c WHERE r.customer_id=c.id and r.del_flg=0 and c.del_flg=0 and r.type=1 and r.preday_status<>1 and r.hope_date='" . $alarm_date . "'";

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

		// $name = $data['name'] ? $data['name']."様" : ($data['name_kana'] ? $data['name_kana']."様" : "");
		$name =  $data['name_kana'] ? $data['name_kana']."様" : "";

		$subject = "脱毛エステ【KIREIMO】カウンセリング前日の予約確認です";
		$content  = $name."\r\n\r\n";
		$content .= '全身脱毛サロン キレイモです！

この度は、無料カウンセリングにお申込いただき誠にありがとうございます。
カウンセリングご予約前日となりましたので、ご連絡させていただきます。
ご予約内容を再度ご確認いただき、ご来店ください。

＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝

【ご予約内容】
カウンセリング予約

【日時】

予約日：'.$md.$yobi.$gTime2[$data['hope_time']].'～（所要時間約60〜90分）

※ご予約の時間をすぎる場合には、
 コールセンター(0120-444-680)へご連絡をお願いいたします。

【店舗名】
'.$shop['address'].'

'.$gPref[$shop['pref']].$shop['address'].'
'.$home_url.'saloninfo/'.$shop['url'].'.html#shop_flow

＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
'.$mail_attention.'
'.$mail_campaign.'


※ご予約日時の変更、キャンセルは下記URLより受付しております。

▼ご予約の変更の場合はこちら▼
https://kireimo.jp/counseling/?id='.$data['customer_id'].'&rid='.$data['id'].'&act=change

▼ご予約のキャンセルの場合はこちら▼
https://kireimo.jp/counseling/?id='.$data['customer_id'].'&rid='.$data['id'].'&act=cancel

◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇
'.($name ? $name."の" : "").'ご来店、スタッフ一同心よりお待ち申し上げております。
その他、ご不明な点、ご質問等ございましたらお気軽に下記までご連絡ください。

'.str_replace("店舗名",$shop['name'],$mail_footer)
;

		//自動送信
		mb_language("ja");
		mb_internal_encoding("UTF-8");
		$returnpath = "-f ".SEND_MAIL_ADDRESS;
		$from = "From:".SEND_MAIL_ADDRESS."\n";
		$from.="cc: ".MAIL_KAKUNIN."\n";
		// if($data['mail']) mb_send_mail($data['mail'] , $subject,$content,$from,$returnpath);
		if ($data['mail']) {
            $kagoya = new KagoyaSendMail();
            $kagoya->send($data['mail'] , $subject,$content,$from,$returnpath);
        }
		
		//if($data['mail']) sendMailHtml($data['mail'], $subject,$content,MAIL_KAKUNIN,'',MAIL_KAKUNIN,MAIL_KAKUNIN);
		//if($data['mail']) sendMailHtmlUTF8($data['mail'] , $subject,$content,MAIL_KAKUNIN);
		//if($data['mail']) sendMailHtmlISO($data['mail'] , $subject,$content,MAIL_KAKUNIN,'',MAIL_KAKUNIN,MAIL_KAKUNIN);


		//achel.xoxo@docomo.ne.jp//岸のmail
		sleep(10);

	}
}	
