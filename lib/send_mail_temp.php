<?php

/*来店なしへの送信*/

$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/config/config.php';
include_once LIB_DIR . 'function.php';
require_once LIB_DIR . 'KagoyaSendMail.php';


//DBに接続してデータ登録
$conn = mysqli_connect(HOST_NAME, DB_UESR, DB_PW);
$GLOBALS['mysqldb']->select_db(DB_NAME,$conn);
$GLOBALS['mysqldb']->query('SET NAMES utf8');

//$alarm_date = date("Y-m-d",mktime(0, 0, 0, date("m") , date("d")+3, date("Y")));

//$sql = "SELECT c.name,c.name_kana,c.mail,r.shop_id,r.hope_date,r.hope_time FROM reservation as r,customer as c WHERE r.customer_id=c.id and r.del_flg=0 and c.del_flg=0 and r.status<=1 and r.type=1 AND r.hope_date>='2014-03-07' AND r.hope_date<='2014-03-17'";
//$sql = "SELECT c.name,c.mail,r.shop_id,r.hope_date,r.hope_time FROM reservation as r,customer as c WHERE r.customer_id=c.id and r.del_flg=0 and c.del_flg=0 and r.type=1 and c.mail='kateiki@i.softbank.jp'";

$list = Get_Result_Sql_Array($sql);

//送信処理
if( $list = Get_Result_Sql_Array($sql) ){
	foreach ( $list as $key=>$data ) {

		$name = $data['name'] ? $data['name']."様" : ($data['name_kana'] ? $data['name_kana']."様" : "");

		$subject = "脱毛エステKireimo【新宿店】";
		$content  = $name."\r\n\r\n";
		$content .= 'KIREIMO新宿店の石井です。

この度は、お申込みをいただき、誠にありがとうございました。
今回は残念ながらご都合が合わず、ご来店いただくことが叶いませんでしたが、
今後もしお時間がありましたら、ぜひ一度無料のカウンセリングにお越し頂ければ幸いでございます。


＜次回ご来店のご予約方法＞

【お電話】もしくは【こちらのメールに返信】にて、ご連絡をお願い致します。

◆お電話の場合（11時〜21時）
フリーダイヤル
0120-567-144

'.($name ? $name."の" : "").'ご来店を、スタッフ一同お待ち致しております。


KIREIMO新宿店　石井


ーーーーーーーーーーー
KIREIMO新宿店
http://kireimo.jp/

お電話でのお問合せ（11時〜20時）
フリーダイヤル：0120-567-144

メールでのお問合せ（24時間OK）
info@kireimo.jp

※本メールは自動配信メールです。このメッセージに返信しないようお願いいたします。
';

		//自動送信
		mb_language("ja");
		mb_internal_encoding("UTF-8");
		$returnpath = "-f ".SEND_MAIL_ADDRESS;
		$from = "From:".SEND_MAIL_ADDRESS."\n";
		$from.="Bcc: ".SEND_MAIL_ADDRESS."\n";

		//$from .= "Disposition-notification-to:".SEND_MAIL_ADDRESS."\n"; //開封通知
		// if($data['mail']) mb_send_mail($data['mail'] , $subject,$content,$from,$returnpath);
		if ($data['mail']) {
            $kagoya = new KagoyaSendMail();
            $kagoya->send($data['mail'] , $subject,$content,$from,$returnpath);
        }
		sleep(5);

	}

}	
?>