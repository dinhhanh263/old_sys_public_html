<?php
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/mens/config/config.php';
require_once LIB_DIR . 'db.php';
require_once LIB_DIR . 'function.php';
include_once( "./lib/tag.php" );

//ini_set("display_errors", 1);
//error_reporting(E_ALL);
//session_save_path("./tmp");
session_start();

//ブログ名よりADコードに変換
if(!isset($_GET['adcode'])){
	$url = $_SERVER['HTTP_REFERER'];
	$url = parse_url($url);
	$blog_sql = $GLOBALS['mysqldb']->query( "select adcode,name from adcode WHERE del_flg = 0 AND hide_flg=0 AND type=3 order by name" );
	while ( $result = $blog_sql->fetch_assoc() ) {
		if(strstr($url['path'], $result['name'])){
			$_GET['adcode'] = $result['adcode'];
			break;
		}
	}
}

//更新ボタン複数回クリックによる重複制御は？
if(isset($_GET['adcode']))  {
	//存在しない広告コードを計上しない
	$_SESSION['MENS_AD_CODE'] = Get_Table_Col("adcode","id"," where adcode='{$_GET['adcode']}'");
	//トップページ集計
	IncrementAccessLog(date('Y-m-d'), 1, $mo_agent, $_SESSION['MENS_AD_CODE']);
	//解析用
	if($page_id)IncrementAccessLog(date('Y-m-d'), $page_id, $mo_agent,$_SESSION['MENS_AD_CODE']);
}

//店舗リスト
$shop_list = getDatalist_shop();

$shop_sql = $GLOBALS['mysqldb']->query( "select * from shop WHERE del_flg = 0  AND status=2 order by id" );
$shop_list[0] = "-";
while ( $result = $shop_sql->fetch_assoc() ) {
	$shop_list[$result['id']] = $result['name'];
}

// $mensdb = changedb();

//courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 order by id" );
$course_list[0] = "-";
while ( $result = $course_sql->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
}

#----------------------------------------------------------------------------------------------------------------------#
# メール送信                                                                                                             #
#----------------------------------------------------------------------------------------------------------------------#
$_POST['shop_id'] = $_POST['shop_id'] ? $_POST['shop_id'] : 1;

$errmsg = "";

if($_POST['mode']){
		if($_POST['hope_month'] && $_POST['hope_day']) $_POST['hope_date'] = date("Y")."-".$_POST['hope_month']."-".$_POST['hope_day'];
		foreach( $gItems as $key => $value ){
			$_POST[$key] = preg_replace('/＼/','ー',$_POST[$key]);
			if($value['type']!="checkbox") $_POST[$key] = htmlspecialchars($_POST[$key]);
			if (get_magic_quotes_gpc())  $_POST[$key] = stripslashes($_POST[$key]);

			//必須項目チェック
			if($value['exist_check']){
				if($_POST[$key] =="" || preg_match("/^( |　)+$/", $_POST[$key]))
				 	$errmsg[$key] = $value['name']."を入力してください";
				elseif($_POST[$key] == "選択したください")
					$errmsg[$key] = $value['name']."を選択してください";
				elseif(preg_match("/mail/", $key) && (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $_POST[$key]))){
					$errmsg[$key] = "正しいメールアドレスを入力してください";
				}
				//カタカナのみ
				elseif(preg_match("/name_kana/", $key) && (!preg_match("/^[ァ-ヶー]+$/u", $_POST[$key]))){
					$errmsg[$key] = "全角カタカナを入力してください";
				}
				/*/数字のみ
				elseif(preg_match("/tel/", $key) && (!preg_match("/^[0-9]+$/", $_POST[$key]))){
					$errmsg[$key] = "有効な数字を入力してください";
				}*/
			}

			//お申込み内容
			if($value['item_name']) 	$mail_content .= "【".$value['name']."】\r\n"; // 項目名表示
			if($value['type']=="checkbox" && is_array($_POST[$key])) 	$mail_content .= implode(",",$_POST[$key]); // 複数選択結合
			else 						$mail_content .= $_POST[$key];
				 						$mail_content .= $value['additional']; // "～"、"-"、"歳"等を付加
			if($value['enter_check']) 	$mail_content .= "\r\n"; //

			//SQL文生成
			$sql_name .= $key.",";
			if($value['type']=="checkbox" && is_array($_POST[$key])) $sql_data .= "'".implode(",",$_POST[$key])."',"; // 複数選択結合
			else $sql_data .= "'".$_POST[$key]. "',";
		}
		var_dump($errmsg);
		//同名、同メールアドレス、同日で予約制限?
		if(!is_array($errmsg)) {
			mb_language("ja");
			mb_internal_encoding("UTF-8");
			$returnpath = "-f ".SEND_MAIL_ADDRESS;

			//$subject = "無料カウンセリング予約がありました。(広告コード：".$ad_page_name.")";
			$subject = "無料カウンセリング予約がありました。";
			$content .= "ご予約内容\r\n\r\n";
			$content .= $mail_content;
			$content .= "\r\n\r\n";

			$content .= "【お知らせ】
サロン状況等により、複数名でお手入れに入る場合がございます。
お手入れ時間が短くなりましても、サービスの質には影響ございません。
今後とも引き続きより多くのお客様にご満足いただけるよう、サービス向上に努めてまいります。
--
＊＊注意＊＊
下記の場合、お手入れをお断りする場合があります。

《特にご注意ください》
■お手入れ箇所のシェービングを前日または当日に行なっていただいていない
※【背中上下・腰・ヒップ・ヒップ奧・襟足】は事前にシェービングしていただく必要はございません。
■お肌が著しく乾燥している、または日焼けをしている

・当日に薬の服用や飲酒をしている
（通院先のお医者様の同意書が取れている場合は可能ですが、処方薬を施術の一週間以内（市販薬は3日間以内）に服用をされたお客様はご遠慮させて頂いております。）
・14日以内に予防接種を受けている、またはお手入れ後14日以内に受ける予定がある
・当日のお手入れ前に激しい運動や、発汗を促す行為をしている
・当日、お手入れ箇所に制汗剤や日焼け止め（化粧品、医薬品含む）を塗布している
・10分以上の遅刻
・お子様連れでのご来店
・結婚式を挙げられる予定日の一か月以内
・お手入れ箇所にシェービングによる赤み等がある
＊＊＊＊＊＊＊

当日のご来店を心よりお待ちしております。

※なお、お手入れ後1週間は、温泉・岩盤浴・プール等のご利用はお控え下さい。予めご了承お願い致します。

ご予約の変更・キャンセルは、KIREIMOマイページよりお願いします。

↓マイページ↓
http://kireimo.jp/member/login.php

□予約変更・・・ご予約前日まで
□キャンセル・・ご予約前日まで

＊＊＊＊＊＊＊
本メールは本日6時頃の時点でご予約がある方に配信しています。
予約変更・キャンセルした時刻によっては、本メールが行き違いに送信される場合があります。
あしからずご了承くださいませ。
＊＊＊＊＊＊＊


◇◇◇◇◇◇◇◇
本メールは自動送信メールです。
本メールにご返信いただいても、メール内容の確認及びご返答はできません。
本メールに覚えのない方は、削除していただきますようお願い致します。
　◇◇◇◇◇◇◇◇
";

			//管理者へ送信
			//mb_send_mail(SEND_MAIL_ADDRESS, $subject, $content, "From:".SEND_MAIL_ADDRESS,$returnpath);

			$subject2 = "【KIREIMO】ご予約確認メール（自動返信）";
			$content2 .= $_POST['name']."　様\r\n";
			$content2 .= "KIREIMOのお手入れ予約事前案内サービスをご利用いただき、誠にありがとうございます。\r\n\r\n";

			$content2 .= "以下の通り、ご予約を承っております。\r\n";
			$content2 .= "--\r\n";
			$content2 .= $_POST['name']."　様のご予約情報\r\n\r\n";

			$content2 .= $content;

			//自動返信
			$from = "From:".SEND_MAIL_ADDRESS."\n";
			$from.="Bcc: ".SEND_MAIL_BCC;
			//mb_send_mail($_POST['mail'],$subject2,$content2,$from);

			//申込件数集計
			IncrementAccessLog(date('Y-m-d'), 3, $mo_agent, $_SESSION['MENS_AD_CODE']);
			//解析用
			if($result_id)IncrementAccessLog(date('Y-m-d'), $result_id, $mo_agent, $_SESSION['MENS_AD_CODE']);

			//旧会員番号自動付与
			// $shop_code = Get_Table_Col("shop","code"," WHERE id={$_POST['shop_id']} ");
			// if($pre_sn_shop = Get_Table_Col("customer","sn_shop"," WHERE shop_id={$_POST['shop_id']} ORDER BY sn_shop DESC LIMIT 1")){
			// 	$_POST['sn_shop'] = $pre_sn_shop + 1 ;
			// 	$_POST['no'] = $shop_code.str_repeat("0",(5-strlen($_POST['sn_shop'])).$_POST['sn_shop']);
			// }else{
			// 	$_POST['sn_shop'] = 1 ;
			// 	$_POST['no'] = $shop_code."00001";
			// }
//var_dump($_POST);

			//DB登録
			$mobile_id = get_mobile_id();
			$sql = "INSERT INTO customer ( ".$sql_name."mo_agent,sn_shop,no,adcode,mo_id,session_id,url,reg_flg,reg_date ) VALUES(";
			$sql .= $sql_data.$mo_agent.",'".$_POST['sn_shop']."','".$_POST['no']."','".$_SESSION['MENS_AD_CODE']."','".$mobile_id."','".session_id()."','".$_SERVER['PHP_SELF']."',2,'".date("Y-m-d H:i:s"). "')";
			var_dump($sql);
			$rtn = $GLOBALS['mysqldb']->query( $sql );

			//会員番号自動付与
			$shop_code = Get_Table_Col("shop","code"," WHERE id={$_POST['shop_id']} ");

			$result  = $GLOBALS['mysqldb']->query( "select * from customer ORDER BY id desc limit 1" );
			if($result){
				while ( $row = $result->fetch_assoc()){
					$GLOBALS['mysqldb']->query('update customer set no="' . $shop_code . $row['id'] . '" where id=' . $row['id']);
				}
			}

$msg='
<div class="block thanks">
<div class="text">
<p>お問い合わせ頂き、ありがとうございます。<br />
お問い合わせに関する受付メール(自動返信メール)を、<br />
ご登録のメールアドレス宛にお送りさせていただきましたのでメールをご確認ください。</p>
<p>お問い合わせ内容に関しては2日～3日営業日までに<br />
こちらのtenpo-kanri@japan-ps.jpのアドレスよりご連絡差し上げます。</p>
<p>10分以上経過しても受付メールが届かない場合は、迷惑メールフォルダに<br />
入っているか、入力ミスもしくは何らかの問題が考えられますので<br />
再度フォームより申し込みいただくか、以下のアドレスまでご連絡ください。</p>
<p>ご不明な点は、<a href="mailto:info@cut-factory.net">info@cut-factory.net</a>までお問い合わせください。 </p>
</div>
</div>';

		  }
}
?>
