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

require_once LIB_DIR . 'KagoyaSendMail.php';


//DBに接続してデータ登録
$conn = mysqli_connect(HOST_NAME, DB_UESR, DB_PW);
$GLOBALS['mysqldb']->select_db(DB_NAME,$conn);
$GLOBALS['mysqldb']->query('SET NAMES utf8');


$gItems  = array(
	'name'			=> array( "name" =>'お名前',					"exist_check" => true,  "item_name" => true,	"confirm_item" => true,		"enter_check" => true),
	'name_kana'		=> array( "name" =>'お名前(カナ)',				"exist_check" => true,  "item_name" => true,	"confirm_item" => true,		"enter_check" => true),
	'birthday'		=> array( "name" =>'生年月日',				"exist_check" => true,  "item_name" => true,	"confirm_item" => true,		"enter_check" => true),
	'tel'			=> array( "name" =>'電話番号',				"exist_check" => true,  "item_name" => true,	"confirm_item" => false,	"enter_check" => true),
	'mail'   		=> array( "name" =>'メールアドレス',				"exist_check" => true,  "item_name" => true,	"confirm_item" => false,	"enter_check" => true),
	'shop'   		=> array( "name" =>'ご予約の店舗',				"exist_check" => true,  "item_name" => true,	"confirm_item" => true,		"enter_check" => true,		"before" => "KIREIMO　"),
	'hope_date'		=> array( "name" =>'ご希望日時',				"exist_check" => true, 	"item_name" => true,	"confirm_item" => true,		"enter_check" => false,		"additional" => " "),
	'hope_time'		=> array( "name" =>'ご希望時間',				"exist_check" => true, 	"item_name" => false,	"confirm_item" => true,		"enter_check" => true,		"additional" => ""),
	'pair_flg'		=> array( "name" =>'サロンでの脱毛経験',			"exist_check" => true, 	"item_name" => true,	"confirm_item" => false,	"enter_check" => true),
	'hope_campaign'	=> array( "name" =>'ご希望のキャンペーン特典',		"exist_check" => false, "item_name" => true,	"confirm_item" => false,	"enter_check" => true),
	'persons'   	=> array( "name" =>'ご利用人数',				"exist_check" => true,  "item_name" => true,	"confirm_item" => true,		"enter_check" => true,		"param" => $gPersons),
	'echo'			=> array( "name" =>'お申込のきっかけになった広告',	"exist_check" => false, "item_name" => true,	"confirm_item" => true,		"enter_check" => true),
	'mag'			=> array( "name" =>'お申込のきっかけになった雑誌名',	"exist_check" => false, "item_name" => true,	"confirm_item" => true,		"enter_check" => true),
	'hope_time_range'=> array( "name" =>'ご連絡希望時間帯',		"exist_check" => false, "item_name" => true,	"confirm_item" => false,	"enter_check" => true),
	'memo'			=> array( "name" =>'備考',					"exist_check" => false, "item_name" => true,	"confirm_item" => true,		"enter_check" => true),
);

$mail_list = array("negishi8989@vielis.co.jp");
$sql = "SELECT r.id,r.customer_id,c.name,c.name_kana,c.mail,r.shop_id,r.hope_date,r.hope_time,c.referer_url,c.user_agent,c.adcode FROM reservation as r,customer as c WHERE r.customer_id=c.id and c.mail in (" . $mail_list . ")";

$list = Get_Result_Sql_Array($sql);

//送信処理
mb_language("ja");
mb_internal_encoding("UTF-8");
$returnpath = "-f ".SEND_MAIL_ADDRESS;
$from = "From:".SEND_MAIL_ADDRESS."\n";


if( $list = Get_Result_Sql_Array($sql) ){
	foreach ( $list as $key=>$data ) {
		foreach( $gItems as $key => $value ){
			$data[$key] = preg_replace('/＼/','ー',$data[$key]);
			if($value['type']!="checkbox") $data[$key] = htmlspecialchars($data[$key]);
			if (get_magic_quotes_gpc())  $data[$key] = stripslashes($data[$key]);
			$body = "";
			//お申込み内容
			if($value['item_name']) 	$body .= "【".$value['name']."】\r\n"; // 項目名表示
			if($value['param']) 		$body .= $value['param'][$data[$key]]; 
			elseif($value['type']=="checkbox" && is_array($data[$key])) 	
										$body .= implode(",",$data[$key]); // 複数選択結合
			else 						$body .= $value['before'].$data[$key];
				 						$body .= $value['additional']; // "～"、"-"、"歳"等を付加
			
			if($value['enter_check']) 	$body .= "\r\n\r\n";

			//簡易文、社内確認用
			if($value['confirm_item']) $mail_content1 .= $body;

			if($key=="shop"){
				$body .= $address = $gPref[$shop['pref']].$shop['address']."\r\n";
				$body .= $shop['tel']."\r\n\r\n";
 		    
 			    $body .= $home_url."img/map_lp/".$shop_map[$shop['id']]."\r\n\r\n";	
	  			$body .= "詳しい地図はこちらを御覧ください。"."\r\n";
				$body .= $home_url."img/map_lp/".$shop_map2[$shop['id']]."\r\n\r\n";
			} 	

			$mail_content .= $body;
		}


		$shop = Get_Table_Row("shop"," WHERE id = '".addslashes($data['shop_id'])."'");
		// $name = $data['name'] ? $data['name']."様" : ($data['name_kana'] ? $data['name_kana']."様" : "");
		$name =  $data['name_kana'] ? $data['name_kana']."様" : "";

		$from = "From:".SEND_MAIL_ADDRESS."\n";
   		$from.= "cc: ".MAIL_YOYAKU.",".$shop['mail']."\n";
	    $subject = "【KIREIMO】カウンセリングご予約日時変更完了メール";
	
		if($data['memo']) $subject .="【備考アリ！】";
			$subject .= "申込報告メール";

			
			$content .= "お申込内容\r\n";
			$content .= "無料カウンセリング\r\n\r\n";
			$content .= $mail_content;
			$content .= "\r\n"; 

			$content .= '■ご注意ください
・KIREIMOは女性専用サロンとなっております。
・初回はカウンセリングのみのご予約となり、施術のご予約はお取り出来かねますのでご了承ください。
・ご予約日の前日に確認のお電話をさせて頂く場合がございます。
・カウンセリングは、60～90分のお時間をいただきます。
その際、ご本人様確認が出来る身分証明書のご持参をお願い致します。
（身分証明として免許証・健康保険証・パスポート・住基カードなど）

～未成年の方へ～

https://kireimo.jp/minor/index.html

お客様の体質・体調・肌の状態により脱毛できる部位に制限がございます。
詳しくは下記KIREIMO公式HPをご覧ください。

https://kireimo.jp/qa/index.html

ご予約の変更・キャンセルに関しましては下記URLから変更をお願い致します。

・ご予約の変更の場合はこちら。
https://kireimo.jp/counseling/?id='.$data['customer_id'].'&rid='.$data_ID.'&act=change

・ご予約のキャンセルの場合はこちら。
https://kireimo.jp/counseling/?id='.$data['customer_id'].'&rid='.$data_ID.'&act=cancel

'.($name ? $name."の" : "").'ご来店、スタッフ一同心よりお待ち申し上げております。

その他、ご不明な点、ご質問等ございましたらお気軽に下記までご連絡ください。

◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇

脱毛サロンKIREIMO／キレイモ　'.$shop['name'].'
https://kireimo.jp/

お電話でのお問合せ（11時～20時）
フリーダイヤル：0120-444-680

メールでのお問合せ（24時間OK）
info@kireimo.jp

◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇

※本メールは自動配信メールです。このメッセージに返信しないようお願いいたします。
';

			//管理者へ送信	
			$content1  = $mail_content1;	
			$content1 .= "【参照元】\r\n".$data['referer_url']."\r\n";	
			$content1 .= "【ブラウザ】\r\n".$data['user_agent']."\r\n";
			$content1 .= "【広告ID】\r\n".$data['adcode']."\r\n";			
			$from1 = "From:".SEND_MAIL_ADDRESS."\n";
			
			$from1.= "cc: ".$shop['mail']."\n";
			if($data['rebook_flg']==2) $from1.= "Bcc: support@kireimo.jp\n";

			// mb_send_mail(MAIL_YOYAKU, $subject, $content1, $from1,$returnpath);
            $kagoya = new KagoyaSendMail();
            $kagoya->send(MAIL_YOYAKU, $subject, $content1, $from1,$returnpath);

			$subject2 = "【KIREIMO】お申込受付完了メール（自動返信）";
			$content2 .= $data['name']."　様\r\n\r\n";
			$content2 .= "数ある脱毛サロンの中からKIREIMO無料カウンセリングにお申込いただき誠にありがとうございます。\r\n\r\n";

			$content2 .= "ご予約内容の確認のため、KIREIMOコールセンター（0120-444-680）よりお電話をさせていただく場合がございます。\r\n";
			$content2 .= "お忙しい中大変申し訳ございませんがご協力よろしくお願いいたします。\r\n\r\n";

			$content2 .= "--\r\n";
			$content2 .= $data['name']."　様のご予約情報\r\n\r\n";

			$content2 .= $content;

			//自動返信
			$from = "From:".SEND_MAIL_ADDRESS."\n";
			$from.= "cc: ".MAIL_KAKUNIN;//未使用

			//$from .= "Disposition-notification-to:".MAIL_YOYAKU; //開封通知
			$kagoya->send($data['mail'],$subject2,$content2,$from,$returnpath);
		sleep(120);

	}

}	
?>