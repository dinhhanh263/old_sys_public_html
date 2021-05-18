<?php
/*未契約者への送信.status=2*/
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

$alarm_date = date("Y-m-d");

//$sql = "SELECT r.id,r.customer_id,c.name,c.name_kana,c.mail,r.shop_id,r.hope_date,r.hope_time FROM reservation as r,customer as c WHERE r.customer_id=c.id and c.del_flg=0 and r.del_flg=0 and c.mail='ka@vielis.co.jp' limit 1";
$sql = "SELECT r.id,r.customer_id,c.name,c.name_kana,c.mail,r.shop_id,r.hope_date,r.hope_time FROM reservation as r,customer as c WHERE r.customer_id <> all (select distinct customer_id from contract where del_flg = 0 ) and  r.customer_id=c.id and r.del_flg=0 and c.del_flg=0 and r.type=1 and r.status=2 and r.hope_date='" . $alarm_date . "'";

$list = Get_Result_Sql_Array($sql);

//送信処理
if( $list = Get_Result_Sql_Array($sql) ){
	foreach ( $list as $key=>$data ) {
		$shop = Get_Table_Row("shop"," WHERE id = '".addslashes($data['shop_id'])."'");
		$name =  $data['name_kana'] ? $data['name_kana']."様" : "";

		$subject = '脱毛エステKIREIMO【'.$shop['name'].'】';
		$content  = $name."\r\n\r\n";
		$content .= '
全身脱毛サロン キレイモです！

この度は、キレイモの無料カウンセリングにご来店いただき、誠にありがとうございました。
一度ご自宅でじっくりとご検討ください。
再度ご検討いただき、「もう一度詳しく説明を聞きたい！」「気になるプランやキャンペーンがある！」
という方は、ぜひお気軽にご来店ください。

▼▼▼再来店のご予約はこちらから！▼▼▼
'.$home_url.'counseling/?id='.$data['customer_id'].'&rid='.$data['id'].'&act=change

'.$mail_campaign.'

▼▼▼▼▼再来店のご予約はこちらから！▼▼▼▼▼
'.$home_url.'counseling/?id='.$data['customer_id'].'&rid='.$data['id'].'&act=change


◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇

'.$name.'のご予約をスタッフ一同心よりお待ちしております。

その他、ご不明な点、ご質問等ございましたらお気軽に下記までご連絡ください。

◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇

全身脱毛サロンKIREIMO／キレイモ　'.$shop['name'].'
'.$home_url.'

お電話でのお問合せ／0120-444-680（11時～20時）

メールでのお問合せ／info@kireimo.jp（24時間OK）

◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇

※本メールは自動配信メールです。このメッセージに返信しないようお願いいたします。
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