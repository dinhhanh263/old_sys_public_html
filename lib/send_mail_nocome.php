<?php
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

// $sql = "SELECT r.id,r.customer_id,c.name,c.name_kana,c.mail,r.shop_id,r.hope_date,r.hope_time FROM reservation as r,customer as c WHERE r.customer_id=c.id and c.del_flg=0 and r.del_flg=0 and c.mail='ka@vielis.co.jp' limit 1";

$sql = "SELECT r.id, r.customer_id, c.name, c.name_kana, c.mail, r.shop_id, r.hope_date, r.hope_time
FROM reservation AS r, customer AS c,
(
 SELECT customer_id,max(status) AS status FROM reservation WHERE del_flg =0
 AND type =1
 AND hope_date =  '" . $alarm_date . "'
 GROUP by customer_id
) AS v
WHERE r.customer_id <>
ALL (
 SELECT DISTINCT customer_id
 FROM contract
 WHERE del_flg =0
)
AND r.customer_id = c.id
AND v.customer_id = c.id
AND r.del_flg =0
AND r.type =1
AND r.hope_date =  '" . $alarm_date . "'
AND c.del_flg =0
AND r.status=v.status
AND r.status =1";

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

この度は数ある全身脱毛サロンの中からキレイモをご予約いただき、誠にありがとうございました。
ご予約いただいたお時間にご来店が確認できませんでしたので、
今回はご予約キャンセルとさせていただきました。

＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝

【ご予約内容】
カウンセリング予約

【日時】

'.str_replace("-","/",$data['hope_date']).'

'.$gTime2[$data['hope_time']].'～ （所要時間約60分）

【店舗名】

'.$shop['name'].'

'.$shop['address'].'
'.$home_url.'saloninfo/'.$shop['url'].'.html#shop_flow

＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝

予約したことをすっかり忘れてしまっていた…！
予約の日時を間違えてしまっていた…！
急な予定でキャンセルができなかった…！
体調不良で行けなくなってしまった…！

そんな時は…下記URLより再予約を受け付けております。

▼▼▼お気軽にご予約ください▼▼▼
'.$home_url.'counseling/?id='.$data['customer_id'].'&rid='.$data['id'].'&act=change

'.$mail_campaign.'

┏☆━━━━━━━━━━━━━━━━━━━━━━━━★┓
★　　        　　　　キレイモのお約束　　　　  　　　　    ☆
┃　　…………………………………………………………　　┃
☆　　   　　〜初めてご来店されるお客様へ〜　　　       　 ★
┗★━━━━━━━━━━━━━━━━━━━━━━━━☆┛

1.お客様を第一に快適なサロンにこだわり、専門のカウンセラーがあなたのムダ毛に関する悩みに応えます。

2.カウンセリング時にヒアリングさせていただいた内容をもとに、お一人おひとりに最適なプランをご提案。

3.カウンセリング時にご納得いただけない場合は、ご提案内容をお持ち帰りいただきご検討ください。後日再予約が可能です。

※※※キレイモは全身脱毛サロンです。脱毛メニューとは異なるエステメニューなどの勧誘は一切行っておりません。※※※

◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇

'.$name.'のご予約をスタッフ一同心よりお待ちしております。

その他、ご不明な点、ご質問等ございましたらお気軽に下記までご連絡ください。

'.str_replace("店舗名",$shop['name'],$mail_footer)
;

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