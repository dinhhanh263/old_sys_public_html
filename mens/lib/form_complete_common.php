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


/*$gItems  = array(
	'hope_date'		=> array( "name" =>'ご希望日時',				"exist_check" => true, 	"item_name" => true,	"confirm_item" => true,		"enter_check" => false,		"additional" => " "),
	'hope_time'		=> array( "name" =>'ご希望時間',				"exist_check" => true, 	"item_name" => false,	"confirm_item" => true,		"enter_check" => true,		"additional" => ""),
	'shop'   		=> array( "name" =>'ご予約の店舗',				"exist_check" => true,  "item_name" => true,	"confirm_item" => true,		"enter_check" => true,		"before" => "KIREIMO　"),
	'name'			=> array( "name" =>'お名前',					"exist_check" => true,  "item_name" => true,	"confirm_item" => true,		"enter_check" => true),
	'name_kana'		=> array( "name" =>'お名前(カナ)',				"exist_check" => true,  "item_name" => true,	"confirm_item" => true,		"enter_check" => true),
	'birthday'		=> array( "name" =>'生年月日',				"exist_check" => true,  "item_name" => true,	"confirm_item" => true,		"enter_check" => true),
	'tel'			=> array( "name" =>'電話番号',				"exist_check" => true,  "item_name" => true,	"confirm_item" => false,	"enter_check" => true),
	'mail'   		=> array( "name" =>'メールアドレス',			"exist_check" => true,  "item_name" => true,	"confirm_item" => false,	"enter_check" => true),
);*/
$gItems  = array(
	'hope_date'		=> array( "name" =>'ご希望日時',				"exist_check" => true, 	"item_name" => true,	"confirm_item" => true,		"enter_check" => false,		"additional" => " "),
	'hope_time'		=> array( "name" =>'ご希望時間',				"exist_check" => true, 	"item_name" => false,	"confirm_item" => true,		"enter_check" => true,		"additional" => ""),
	'shop'   		=> array( "name" =>'ご予約の店舗',				"exist_check" => true,  "item_name" => true,	"confirm_item" => true,		"enter_check" => true,		"before" => "KIREIMO　"),
	);

// echo '<pre>';
// var_dump($_POST);
// echo '</pre>';


#----------------------------------------------------------------------------------------------------------------------#
# メール送信                                                                                                             #
#----------------------------------------------------------------------------------------------------------------------#
$_POST['shop_id'] = $_POST['shop_id'] ? $_POST['shop_id'] : 1;
if( $_POST['shop_id'] ) $shop = Get_Table_Row("shop"," WHERE del_flg = 0  AND status=2 and id = '".addslashes($_POST['shop_id'])."'");

if($_POST['mode']=="send"){
	foreach( $gItems as $key => $value ){
		$_POST[$key] = preg_replace('/＼/','ー',$_POST[$key]);
		if($value['type']!="checkbox") $_POST[$key] = htmlspecialchars($_POST[$key]);
		if (get_magic_quotes_gpc())  $_POST[$key] = stripslashes($_POST[$key]);
		$body = "";
		//お申込み内容
		if($value['item_name']) 	$body .= "【".$value['name']."】\r\n"; // 項目名表示
		if($value['param']) 		$body .= $value['param'][$_POST[$key]];
		elseif($value['type']=="checkbox" && is_array($_POST[$key]))
									$body .= implode(",",$_POST[$key]); // 複数選択結合
		else 						$body .= $value['before'].$_POST[$key];
			 						$body .= $value['additional']; // "～"、"-"、"歳"等を付加

		if($value['enter_check']) 	$body .= "\r\n\r\n";

		//簡易文、社内確認用
		if($value['confirm_item']) $mail_content1 .= $body;

		if($key=="shop"){
			// $for_mail_shop_name_list = array("新宿","池袋","渋谷","横浜","町田","名古屋");
			// str_replace($for_mail_shop_name_list, $for_mail_shop_name_list, $_POST[$key],$count);
			// if($count !== 0){
			// 	$body .=  "※近隣に別店舗がございます。ご来店の際はお間違いの無いようご注意ください。"."\r\n\r\n";
			// }
			// if($messange) $body .=  "※近隣に別店舗がございます。ご来店の際はお間違いの無いようご注意ください。"."\r\n\r\n";

			$body .= $address = $gPref[$shop['pref']].$shop['address']."\r\n";
			//$body .= $shop['tel']."\r\n\r\n";

		  ///////////////////////////////////////////////////////////////


		  	$body .= $mens_home_url."/salon/"/*.$shop_map[$shop['id']]*/."\r\n\r\n";
		  //if($_POST['shop_id']<>17 && $_POST['shop_id']<>19){
			// $body .= "詳しい地図はこちらを御覧ください。"."\r\n";
			// $body .= $home_url."img/map_lp/".$shop_map2[$shop['id']]."\r\n";
		  //}
		}

		$mail_content .= $body;

	}

	//同名、同メールアドレス、同日で予約制限?
	//if(!($user = Get_Table_Row("customer"," WHERE del_flg=0 AND ( mail ='{$_POST['mail']}' OR tel ='{$_POST['tel']}' OR session_id ='".session_id()."' ) " )) ){
	if( $_SESSION['ACT']=="change" || !($user = Get_Table_Row("customer"," WHERE del_flg=0 AND ( session_id ='".session_id()."' ) " )) ){
	//if($_POST['mail']<>'rarirure_0925@docomo.ne.jp'){
		//空き確認
		$table = "reservation";
		$_POST['hope_time'] = array_search($_POST['hope_time'], $gTime2);
		if(!$_POST['persons']){
			$_POST['length'] = 2;
		}else{
			$_POST['length'] = ($_POST['persons']>1) ? 3 : 2;
		}
		// $_POST['length'] = ($_POST['hope_campaign_checked']>1) ? 4 : 2;

    	$room_list = array();
    	for ($i = 1; $i <= $shop['counseling_rooms'] ; $i++) $room_list[] = "1".$i;
    	for ($i = 1; $i <= $shop['vip_rooms']; $i++)    $room_list[] = "2".$i;

      	$is_empty = false;

		foreach ($room_list as $key3 => $value3) {
			//カウンセリング1,早番、遅番,新宿店,池袋店除外
          	//if( !($_POST['shop_id'] <= 2 && $_POST['hope_date']>="2014/08/08" ) && $value3==11 && ($_POST['hope_time']<4 || $_POST['hope_time']>16) )continue;
			//if(($_POST['shop_id'] > 2 && $value3 == 11 && (in_array($_POST['hope_time'], array(2, 4, 6, 8 ,10, 12, 14, 16, 18))))
			//|| (( ($_POST['shop_id'] <= 2 && $value3 == 11) || $value3 != 11) && (in_array($_POST['hope_time'], array(1, 3, 5, 7, 9, 11, 13, 15, 17, 19))))){

	            // length=3(1時間半の場合)の場合の カンセリング1 19:30、 カンセリング1以外　21:00を超えない対処
	            //if(!(in_array($value2, array("18:30", "20:00")) && ($length > 2))){

					$sql  = " WHERE type<>3 and type<>14 and type<>21 and type<>22 and del_flg=0 and  hope_date='".addslashes($_POST['hope_date'])."' AND shop_id=".$_POST['shop_id']." AND room_id=".$value3;
					$sql .= " AND (hope_time<".$_POST['hope_time'] ." AND hope_time+length>".$_POST['hope_time'] ;//予約開始時間と比較(重なりあり)
					//$sql .= " OR hope_time>=".$_POST['hope_time']." AND hope_time<".($_POST['hope_time']+$_POST['length']) ;
					$sql .= " OR hope_time<".($_POST['hope_time']+$_POST['length']) ." AND hope_time+length>".($_POST['hope_time']+$_POST['length']) ;//予約終了時間と比較(重なりあり)
					$sql .= " OR hope_time>=".$_POST['hope_time'] ." AND hope_time+length<=".($_POST['hope_time']+$_POST['length']) . ")";//中にあり

					$empty[$value3] = Get_Table_Row($table,$sql)	? 0 : 1;
					//最初のROOMの空きがあれば
					if($empty[$value3]) {
						$is_empty = true;
						$_POST['room_id'] = $value3;
						break;
					}
	            //}
			//}
		}

		//空き確認
		if(!$is_empty){
			$gMsg = "<font color='red' size='-1'>※只今、他の予約との重なりがあります。予約できませんでした。</font><br>";
			$gMsg .= '<form action="index.html" name="frm" id="frm" method="post">';
    		$gMsg .= '<input type="hidden" name="mode" value="send">';
    		$gMsg .= '<input type="hidden" name="id" value="'.$_REQUEST['id'].'"/>';
    		$gMsg .= '<input type="hidden" name="shop" value="'.$_POST['shop'].'" />';
    		$gMsg .= '<input type="hidden" name="hope_date" value="'.$_POST['hope_date'].'" />';
    		$gMsg .= '<input type="hidden" name="hope_time" value="'.$_POST['hope_time'].'" />';
    		$gMsg .= '<input type="hidden" name="shop_id" value="'.$_POST['shop_id'].'" />';
    		$gMsg .= '<input type="hidden" name="name" value="'.$_POST['name'].'" />';
    		$gMsg .= '<input type="hidden" name="name_kana" value="'.$_POST['name_kana'].'" />';
    		$gMsg .= '<input type="hidden" name="birthday" value="'.$_POST['birthday'].'" />';
    		$gMsg .= '<input type="hidden" name="tel" value="'.$_POST['tel'].'" />';
    		$gMsg .= '<input type="hidden" name="mail" value="'.$_POST['mail'].'" />';
    		// $gMsg .= '<input type="hidden" name="pair_flg" value="'.$_POST['pair_flg'].'" />';
   			$gMsg .= '<input type="hidden" name="hope_campaign" value="'.$_POST['hope_campaign'].'" /> ';
    		$gMsg .= '<input type="hidden" name="hopes_discount" value="'.$_POST['hopes_discount'].'" /> ';
    		// $gMsg .= '<input type="hidden" name="persons" value="'.$_POST['nunzu'].'" /> ';
    		$gMsg .= '<input type="hidden" name="echo" value="'.$_POST['echo'].'" /> ';
    		$gMsg .= '<input type="hidden" name="mag" value="'.$_POST['mag'].'" /> ';
    		$gMsg .= '<input type="hidden" name="hope_time_range" value="'.$_POST['hope_time_range'].'" /> ';
    		$gMsg .= '<input type="hidden" name="memo" value="'.$_POST['memo'].'" /> ';

  			// if($_POST['nunzu']>1){
    	// 		$gMsg .= '<input type="hidden" name="pair_name_kana" value="'.$_POST['pair_name_kana'].'" />';
    	// 		$gMsg .= '<input type="hidden" name="pair_tel" value="'.$_POST['pair_tel'].'" />';
    	// 	}
    		$gMsg .= '<input type="submit" value="選びなおす" /> ';
    		$gMsg .= '</form><br />';
		}else{

			//顧客新規--------------------------------------------------------------------------------------------------------------------
			$_POST['password'] = generateID(6,'small');
			$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");
			$_POST['mo_agent'] = $mo_agent;
			$_POST['adcode'] = $_SESSION['MENS_AD_CODE'];
			$_POST['mo_id'] = $mobile_id;
			$_POST['session_id'] = session_id();
			$_POST['url'] = $_SERVER['PHP_SELF'];
			$_POST['referer_url'] = $_SESSION['MENS_KIREIMO_REFERER'];
			$_POST['user_agent'] = $ua;

			//２：配信サーバより、ID有り。　１：ID無し、メールアドまた電話存在あり；　メール内変更リンクより、ACTあり、IDあり
			$_POST['rebook_flg'] = ($_POST['id']<>'' && !$_SESSION['ACT']) ? 2 : 1;//梅木より：２　一般再度申込:１
			if($_SESSION['ACT']=="change") $_POST['act_flg'] = 1; //6/26 11:18より
			//重複確認：mail+birthday or tel+birthday
			if(!$_POST['id']) $_POST['id'] = Get_Table_Col("customer","id"," WHERE del_flg=0 AND (  mail ='{$_POST['mail']}' && tel ='{$_POST['tel']}' OR mail ='{$_POST['mail']}' && birthday ='{$_POST['birthday']}' OR tel ='{$_POST['tel']}' && birthday ='{$_POST['birthday']}' ) " );

			// $customer_field  = array("no","sn_shop","password","shop_id","name","name_kana","pair_name_kana","birthday","tel","pair_tel","mail","pair_flg","hope_campaign","hopes_discount","echo","mag","hope_time_range","reg_date","edit_date","mo_agent","adcode","mo_id","session_id","url","referer_url","user_agent");
			$customer_field  = array("no","sn_shop","password","shop_id","name","name_kana","pair_name_kana","birthday","tel","pair_tel","mail","hope_campaign","hopes_discount","echo","mag","hope_time_range","reg_date","edit_date","mo_agent","adcode","mo_id","session_id","url","referer_url","user_agent");
			//$customer_field2 = 							 array("shop_id","name","name_kana","birthday","tel","mail","pair_flg","hope_campaign","echo","mag","hope_time_range","session_id","rebook_flg");
			$customer_field2 = 	array("rebook_flg");
			if($_POST['id']) {
				$_POST['customer_id'] = Update_Data("customer",$customer_field2,$_POST['id']);//再度申込
			} else {
				$_POST['customer_id'] = Input_New_Data("customer",$customer_field);//新規
				// $result  = $GLOBALS['mysqldb']->query( "select * from customer ORDER BY id desc limit 1" );
				// if($result){
				if(isset($_POST['customer_id']) && strlen($_POST['customer_id']) > 0){
					// while ( $row = $result->fetch_assoc()){
						$GLOBALS['mysqldb']->query('update customer set no="' . $shop['code'] . str_repeat("0",(5-strlen($_POST['customer_id']))). $_POST['customer_id'] . '" where id=' . $_POST['customer_id']);
						//CVTag用
						$_POST['no'] = $shop['code'] . str_repeat("0",(5-strlen($_POST['customer_id']))). $_POST['customer_id'];
					// }
				}
			}

			//クッキー情報格納,新規契約のみ---------------------------------------------------------------------------------------------------------------------
			if(isset($_COOKIE) && !$_POST['id']){
				foreach($_COOKIE as $key => $val){
					//広告IDのみ、数字のみと判断
					if(is_numeric($key)){
						if($val['MENS_KIREIMO_ADCODE_COOKIE']==99999) $val['MENS_KIREIMO_ADCODE_COOKIE']="";//媒体なし
						$cv_flg = ($val['MENS_KIREIMO_ADCODE_COOKIE']==$_SESSION['MENS_AD_CODE']) ? 1 :0;
						//var_dump("insert k_cookie (customer_id,referer_url,adcode,first_date,edit_date,reg_date,cnt,cv_flg) values (".$_POST['customer_id'].",'".$val['MENS_KIREIMO_REFERER_COOKIE']."','".$val['MENS_KIREIMO_ADCODE_COOKIE']."','".$val['MENS_KIREIMO_COOKIE_DATE']."','".$val['MENS_KIREIMO_COOKIE_LASTDATE']."','".date("Y-m-d H:i:s")."','".$val['MENS_KIREIMO_COOKIE_CNT']."',".$cv_flg.")") ;

						$GLOBALS['mysqldb']->query("insert k_cookie (customer_id,referer_url,adcode,first_date,edit_date,reg_date,cnt,cv_flg) values (".$_POST['customer_id'].",'".$val['MENS_KIREIMO_REFERER_COOKIE']."','".$val['MENS_KIREIMO_ADCODE_COOKIE']."','".$val['MENS_KIREIMO_COOKIE_DATE']."','".$val['MENS_KIREIMO_COOKIE_LASTDATE']."','".date("Y-m-d H:i:s")."','".$val['MENS_KIREIMO_COOKIE_CNT']."',".$cv_flg.")") ;
					}
					//オーガニック格納
					if($key==88888){
						$cv_flg =  1 ;
						$val['MENS_KIREIMO_ORGANIC_ADCODE_COOKIE'] = $_SESSION['MENS_AD_CODE'] ? $_SESSION['MENS_AD_CODE'] : "";
						$GLOBALS['mysqldb']->query("insert k_organic_cookie (customer_id,referer_url,entrance_url,adcode,first_date,edit_date,reg_date,cnt,lp_flg,cv_flg) values (".$_POST['customer_id'].",'".$val['MENS_KIREIMO_ORGANIC_REFERER_COOKIE']."','".$val['MENS_KIREIMO_ORGANIC_ENTRANCE_COOKIE']."','".$val['MENS_KIREIMO_ORGANIC_ADCODE_COOKIE']."','".$val['MENS_KIREIMO_ORGANIC_COOKIE_DATE']."','".$val['MENS_KIREIMO_ORGANIC_COOKIE_LASTDATE']."','".date("Y-m-d H:i:s")."','".$val['MENS_KIREIMO_ORGANIC_COOKIE_CNT']."','".$val['MENS_KIREIMO_ORGANIC_LPFLAG_COOKIE']."',".$cv_flg.")") ;

					}
				}
			}


			//将来の予約をキャンセル処理-------------------------------------------------------------------------------------------------------------
			if($_POST['id'] && $_POST['customer_id'] ) $GLOBALS['mysqldb']->query("update reservation set type=3,edit_date='".$_POST['edit_date']."' where type=1 and del_flg=0 and customer_id=".$_POST['customer_id']." and hope_date>='".date('Y-m-d')."'");

			//予約新規,担当者自動指定？room指定？
			$_POST['type'] = 1;//区分：カウンセリング
			$_POST['new_flg'] = 1;//新規契約フラッグ
			$reservation_field = array("customer_id","shop_id","room_id","type","hope_date","hope_time","length"/*,"persons"*/,"hope_campaign_checked","hope_campaign","hopes_discount","echo","mag","hope_time_range","introducer","introducer_type","special","memo","reg_date","edit_date","adcode","new_flg");
			if($_POST['id']) array_push($reservation_field,  "rebook_flg");//再度申込
			if($_SESSION['ACT']=="change") array_push($reservation_field,  "act_flg");//予約変更
			$data_ID = Input_New_Data($table ,$reservation_field);

			//再度申込が計上なし
			if(!$_POST['id']){
				//申込件数集計
				IncrementAccessLog(date('Y-m-d'), 3, $mo_agent, $_SESSION['MENS_AD_CODE']);
				//解析用
				if($result_id)IncrementAccessLog(date('Y-m-d'), $result_id, $mo_agent, $_SESSION['MENS_AD_CODE']);
			}

			//自動送信*****************************************************************
			mb_language("ja");
			mb_internal_encoding("UTF-8");
			//$_POST['name'] = mb_convert_encoding(mb_convert_encoding($_POST['name'], "sjis-win", "eucJP-win"), "UTF-8", "sjis-win");
			$_POST['name'] = str_replace("﨑", "崎", $_POST['name'] );
			$_POST['name'] = str_replace("髙", "高", $_POST['name'] );
			$returnpath = "-f ".SEND_MAIL_ADDRESS;

// 予約変更
if($_SESSION['ACT']=="change"){
    $content = $_POST['name'].'様

ご予約の変更を受付けました。

--

'.$_POST['name'].'様のご予約内容

【日時】

'.$_POST['hope_date'].'

'.$gTime2[$_POST['hope_time']].'～

【店舗名】

'.$shop['name'].'

【ご予約内容】

カウンセリング予約

●●●●●ご予約の変更・キャンセル●●●●●

・ご予約の変更の場合はこちら。
http://kireimo.jp/mens/counseling/?id='.$_POST['customer_id'].'&rid='.$data_ID.'&act=change

・ご予約のキャンセルの場合はこちら。
http://kireimo.jp/mens/counseling/?id='.$_POST['customer_id'].'&rid='.$data_ID.'&act=cancel
ご予約の変更、キャンセルの場合は上記URLからも変更が可能でございます。

※ご予約の時間をすぎる場合はコールセンター(0120-444-276)へご連絡をお願い致します。

'.$_POST['name'].'様のご来店をスタッフ一同心よりお待ち申し上げております。

その他、ご不明な点、ご質問等ございましたらお気軽に下記までご連絡ください。

◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇

脱毛サロンMEN\'S KIREIMO／メンズキレイモ　'.$shop['name'].'

http://kireimo.jp/mens/

お電話でのお問合せ（11時～20時）

フリーダイヤル：0120-444-276

メールでのお問合せ（24時間OK）

mens.info@kireimo.jp

◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇

※本メールは自動配信メールです。このメッセージに返信しないようお願いいたします。
';

    $from = "From:".SEND_MAIL_ADDRESS."\n";
    //$from.= "cc: ".MAIL_YOYAKU.",".$shop['mail']."\n";

    $subject = "◆【MEN'S KIREIMO】カウンセリングご予約日時変更完了メール";
    // if($_POST['mail']) mb_send_mail($_POST['mail'], $subject, $content, $from, $returnpath);
    if ($_POST['mail']) {
        $kagoya = new KagoyaSendMail();
        $kagoya->send($_POST['mail'], $subject, $content, $from, $returnpath);
    }

}else{

			//$subject = "無料カウンセリング予約がありました。(広告コード：".$ad_page_name.")";
			if($_POST['memo']) $subject .="【備考アリ！】";
			$subject .= "申込報告メール";
			if($_POST['id']) $subject .="(再申込)";
			$content .= "お申込内容\r\n";
			$content .= "無料カウンセリング\r\n\r\n";
			$content .= $mail_content;
			$content .= "\r\n";
			$content .= '■■■■■ご確認ください■■■■■

・MEN\'S KIREIMOは男性専用サロンとなっております。
・ご予約日の前日に確認のお電話をさせていただきます。
・カウンセリングは、60～90分のお時間を予定しております。
・ご本人様確認が出来る身分証明書のご持参をお願い致します。
（身分証明として免許証・健康保険証・パスポート・住基カードなど）

≪未成年の方≫
未成年のお客様は親権者同意書が必要になります。下記のURLをご覧ください。
http://kireimo.jp/mens/minor/index.html

●●●●●ご予約の変更・キャンセル●●●●●
・ご予約の変更の場合はこちら。
http://kireimo.jp/mens/counseling/?id='.$_POST['customer_id'].'&rid='.$data_ID.'&act=change

・ご予約のキャンセルの場合はこちら。
http://kireimo.jp/mens/counseling/?id='.$_POST['customer_id'].'&rid='.$data_ID.'&act=cancel


'.($_POST['name'] ? $_POST['name']."様の" : "").'ご来店をスタッフ一同心よりお待ち申し上げております。
ご不明な点、ご質問等ございましたらお気軽に下記までご連絡ください。

◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇

脱毛サロンMEN\'S KIREIMO／メンズキレイモ　'.$shop['name'].'
http://kireimo.jp/mens/

お電話でのお問合せ（11時～20時）
フリーダイヤル：0120-444-276

◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇
';

			//管理者へ送信
			$content1  = $mail_content1;
			$content1 .= "【お名前】\r\n".$_POST['name']."\r\n";
			$content1 .= "【お名前(カナ)】\r\n".$_POST['name_kana']."\r\n";
			$content1 .= "【生年月日】\r\n".$_POST['birthday']."\r\n";
			$content1 .= "【電話番号】\r\n".$_POST['tel']."\r\n";
			$content1 .= "【メールアドレス】\r\n".$_POST['mail']."\r\n";

			$content1 .= "【参照元】\r\n".$_SESSION['MENS_KIREIMO_REFERER']."\r\n";
			$content1 .= "【ブラウザ】\r\n".$ua."\r\n";
			$content1 .= "【広告ID】\r\n".$_SESSION['MENS_AD_CODE']."\r\n";
			$from1 = "From:".SEND_MAIL_ADDRESS."\n";

			//$from1.= "cc: ".$shop['mail']."\n";
			if($_POST['rebook_flg']==2) $from1.= "Bcc: support@kireimo.jp\n";

			// mb_send_mail(MAIL_YOYAKU, $subject, $content1, $from1,$returnpath);
            $kagoya = new KagoyaSendMail();
			$kagoya->send(MAIL_YOYAKU, $subject, $content1, $from1,$returnpath);

			$subject2 = "◆【MEN'S KIREIMO】お申込受付完了メール（自動返信）";
			$content2 .= $_POST['name']."　様\r\n\r\n";
			$content2 .= "数ある脱毛サロンの中からMEN'S KIREIMOの無料カウンセリングにお申込みいただき誠にありがとうございます。\r\n\r\n";

			$content2 .= "ご予約内容の確認のため、MEN'S KIREIMOコールセンターよりお電話をさせていただきます。\r\n";
			$content2 .= "恐れ入りますが、ご協力をよろしくお願いいたします。\r\n\r\n";

			$content2 .= "--\r\n";
			$content2 .= $_POST['name']."　様のご予約情報\r\n\r\n";

			$content2 .= $content;

			//自動返信
			$from = "From:".SEND_MAIL_ADDRESS."\n";
			$from.= "cc: ".MAIL_KAKUNIN;//未使用

			//$from .= "Disposition-notification-to:".MAIL_YOYAKU; //開封通知
			// mb_send_mail($_POST['mail'],$subject2,$content2,$from,$returnpath);
			$kagoya->send($_POST['mail'],$subject2,$content2,$from,$returnpath);

}
			//重複申込の場合がCVに計上しない
			if(!$_POST['id']){
				//CVtag list
				$result  = $GLOBALS['mysqldb']->query( "select * from tag WHERE del_flg = 0 AND status=1 AND coverage=1 AND location=2 ORDER BY id" );
					if($result){
					while ( $row = $result->fetch_assoc() ) {
						$tag_head_cv .= View_Cook_Html($row['tag']) ."\n";
					}
				}
			}
			$complete_flg= true;

		}

   }else $duplicated = true;
}
?>
