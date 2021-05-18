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

$part =  array( 0 => "全身" , 	1 => "上半身", 	2 => "下半身");
$alarm_date = date("Y-m-d",mktime(0, 0, 0, date("m") , date("d")+1, date("Y")));

$sql = "SELECT r.id,r.customer_id,c.name,c.name_kana,c.mail,r.shop_id,r.hope_date,r.hope_time,r.length,r.part,c2.type,c2.new_flg FROM reservation as r,customer as c,course as c2 WHERE r.course_id=c2.id and r.customer_id=c.id and r.del_flg=0 and c.del_flg=0 and r.type=2 and r.status=0 and r.hope_date='" . $alarm_date . "'";
// テスト時コメントアウトを解除して下記SQLを使って確認する
// $sql = "SELECT r.id,r.customer_id,c.name,c.name_kana,c.mail,r.shop_id,r.hope_date,r.hope_time,r.length,r.part FROM reservation as r,customer as c WHERE  r.customer_id=c.id and r.del_flg=0 and c.del_flg=0 and c.mail='ka@vielis.co.jp' limit 3";

$list = Get_Result_Sql_Array($sql);

//未送信があれば送信処理
if( $list = Get_Result_Sql_Array($sql) ){
	foreach ( $list as $key=>$data ) {

		$part_msg = "";
		$shop = Get_Table_Row("shop"," WHERE id = '".addslashes($data['shop_id'])."'");
		$hope_date = strtotime($data['hope_date']);
		$md = date("n月j日", $hope_date);
		$d = date("j", $hope_date);
		$yobi = getYobi($data['hope_date'],2);
		
		$reservation_condition = branchTypeForReservation($data['type'],$data['new_flg'],$gForSendMail); //パックまたは新月額の場合 キャンセルに関する文章を分岐

		// 店舗別注意文言追加　2017/08/02 add by shimada
		$attention_message ="";
		// 川崎店
		if($shop['id']==31){
		    $attention_message =
		"・川崎店は日曜・祝日に限り、正面入り口が19時で閉鎖されます。\n19時以降のご来店はスタッフがお迎えに参りますので、正面入り口の左側へ回っていただき、裏口に到着いたしましたら、一度コールセンターにお電話をお願いいたします。\r\n\r\n";
		}

		// $name = $data['name'] ? $data['name']."様" : ($data['name_kana'] ? $data['name_kana']."様" : "");
		$name =  $data['name_kana'] ? $data['name_kana']."様" : "";

		$subject = "【キレイモ】お手入れ予約の前日です";
		$content  = $name."\r\n";
		$content .= '
全身脱毛サロン キレイモです！

いつも、キレイモをご利用いただき誠にありがとうございます。
お手入れのご予約の前日となりましたのでお知らせさせていただきます。
下記ご予約情報を再度、ご確認ください。

＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝


【ご予約内容】
お手入れ予約

【ご予約日時】
'.$md.$yobi.$gTime2[$data['hope_time']].'～

【ご予約の店舗】
'.$shop['name'].'

'.$shop['address'].'
'.$home_url.'saloninfo/'.$shop['url'].'.html#shop_flow

'.$gForSendMail['reservation_note'].'
'.$gForSendMail['reservation_contraindications'].'
'.$attention_message.'
'.$mail_mypage_common.'

'.($name ? $name."の" : "").'ご来店をスタッフ一同心よりお待ち申し上げております。

その他、ご不明な点、ご質問等ございましたらお気軽に下記までご連絡ください。

＊＊＊＊＊＊＊
本メールは本日18時半頃の時点でご予約がある方に配信しています。
ご予約の変更・キャンセルをした時刻によっては、本メールが行き違いに送信される場合がございます。ご了承ください。
＊＊＊＊＊＊＊

◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇
'.$gForSendMail['home_url'].'

'.$gForSendMail['contacts_for_reservation'].'

◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇

※このメールは送信専用アドレスからお送りしています。
　ご返信いただいてもお答えできませんのでご了承ください。
';


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

		sleep(5);

	}
}
?>