<?php

/*ご予約の確認に関して(1日前の20時に自動送信),前日電話OKの場合、送信しない */

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

$part =  array( 0 => "全身" , 	1 => "上半身", 	2 => "下半身");
$alarm_date = date("Y-m-d",mktime(0, 0, 0, date("m") , date("d")+1, date("Y")));
//$sql = "SELECT r.id,r.customer_id,c.name,c.name_kana,c.mail,r.shop_id,r.hope_date,r.hope_time,r.length,r.part,c2.type FROM reservation as r,customer as c, course as c2 WHERE r.course_id=c2.id and r.customer_id=c.id and r.type=2 and c.del_flg=0 and c.mail='ka@vielis.co.jp' limit 1";

$sql = "SELECT r.id,r.customer_id,c.name,c.name_kana,c.mail,r.shop_id,r.hope_date,r.hope_time,r.length,r.part,c2.type FROM reservation as r,customer as c,course as c2 WHERE r.course_id=c2.id and r.customer_id=c.id and r.del_flg=0 and c.del_flg=0 and r.type=2 and r.route=5 and r.hope_date='" . $alarm_date . "'";

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
		$times = 30*$data['length'];

		if($data['type'] && $data['part'] )$part_msg = '【お手入れ箇所】
'.$part[$data['part']];
		elseif(!$data['type']  )$part_msg = '【お手入れ箇所】
全身';


		$name = $data['name'] ? $data['name']."様" : ($data['name_kana'] ? $data['name_kana']."様" : "");

		$subject = "【KIREIMO】お手入れ前日ご予約確認メール";
		$content  = $name."\r\n";
		$content .= '
数ある脱毛サロンの中からKIREIMOにお通い頂き誠にありがとうございます。
下記内容でお手入れのご予約を承っております。
--
'.$name.'のご予約内容

【ご予約内容】
お手入れ予約

【ご予約日時】
'.$md.$yobi.$gTime2[$data['hope_time']].'（所要時間約'.$times.'分）

【ご予約の店舗】
'.$shop['name'].'

'.$shop['address'].'
'.$home_url.'img/map_lp/'.$shop_map[$shop['id']].'

'.$part_msg.'

■お知らせ
サロン状況により、複数名でお手入れに入る場合がございます。
お手入れ時間が短くなりましてもサービスの質には影響ございません。
今後とも引き続きより多くのお客様に満足頂けるよう、サービス向上に努めてまいります。

■ご予約について
ご予約のキャンセル・ご変更は前日の20時までにKIREIMO会員サイトまたはコールセンターよりお願いします。
会員サイトURL
コールセンター　0120-444-680
営業時間11:00~21：00
※月額ご予約のお客様はキャンセル・ご予約変更は出来かねますのでご了承下さい。

■ご注意下さい
下記の場合、お手入れをお断りさせて頂く場合がございます。
・お手入れ箇所のシェービングを2～3日前（前日まで）に行っていない場合。
※【背中・ヒップ・Oライン・襟足】は店舗でお手伝いさせていただきます。
・お肌が著しく乾燥している、または日焼けをしている場合

・市販のお薬1週間、処方のお薬1か月間服用されてから空いていない場合。
・12時間以内に飲酒されている場合
・14日以内に予防接種を受けている、またはお手入れ後14日以内に受ける予定がある
・お手入れ箇所にシェービングによる赤み等がある

'.($name ? $name."の" : "").'ご来店をスタッフ一同心よりお待ち申し上げております。

その他、ご不明な点、ご質問等ございましたらお気軽に下記までご連絡ください。

＊＊＊＊＊＊＊
本メールは本日6時頃の時点でご予約がある方に配信しています。
予約変更・キャンセルした時刻によっては、本メールが行き違いに送信される場合があります。
あしからずご了承くださいませ。
＊＊＊＊＊＊＊

脱毛サロンKIREIMO／キレイモ　四条河原町店
http://kireimo.jp/

お電話でのお問合せ（11時～20時）
フリーダイヤル：0120-444-680

メールでのお問合せ（24時間OK）
info@kireimo.jp

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

		sleep(10);

	}
}	
?>