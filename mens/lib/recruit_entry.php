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


$table = "shop";
//店舗list
$sql = $GLOBALS['mysqldb']->query( "select * from ".$table." WHERE del_flg = 0  AND status=2 and id<>6 order by id" );
if($sql){
	while ( $result = $sql->fetch_assoc() ) {
		$data_list[$result['name']] = $result['name'];
	}
}

//初期設定
$mode = $_POST['mode'];

$db_arr = array("entry_name","entry_name_2","entry_name_kana","entry_name_kana_2","sex","age","birthday","zip","pref","now_address_1","now_address_2","now_tel_1","now_tel_2","now_email","line","station","shop","type","exeperience_c","exeperience_y","opportunity","input_form_title_tab_self_pr","comment");

$send_mail_address = "info@vielis.co.jp";
//$send_mail_bcc = "ka@plus-innovation.co.jp";

if($mode && !strstr($_POST['comment'],"http") && !Get_Table_Row("job"," WHERE del_flg=0 AND session_id ='".session_id()."' " ) ){
// if($mode && !strstr($_POST['comment'],"http")){

	//電話番号整形
	$_POST['now_tel_1'] = sepalate_tel($_POST['now_tel_1']);
	$_POST['now_tel_2'] = sepalate_tel($_POST['now_tel_2']);

	//POST整形-----------------------------------------------------------------
	foreach($_POST as $key => $val){
		$_POST[$key] = htmlspecialchars($val);
		if ( get_magic_quotes_gpc() ) $_POST[$key] = stripslashes($val);

		if($key == "zip1" || $key == "birthday_year" || $key == "birthday_month") {
			continue;
		} else if($key == "zip2") {
			$_POST["zip"] = $_POST["zip1"]."-".$_POST["zip2"];
			$key = "zip";
		} else if($key == "birthday_day") {
			$_POST["birthday"] = $_POST["birthday_year"]."-".sprintf('%02d', $_POST["birthday_month"])."-".sprintf('%02d', $_POST["birthday_day"]);
			$key = "birthday";
		}

		if(in_array($key,$db_arr)){ 
			//SQL文生成
			$sql_name .= $key.",";
			$sql_data .= "'".$_POST[$key]. "',";
		}
	}

	//申込件数集計--------------------------------------------------------------
	IncrementAccessLog2(date('Y-m-d'), 4, $mo_agent, $_SESSION['MENS_AD_CODE']);
	
	//DB登録-------------------------------------------------------------------
	$mobile_id = get_mobile_id();
	$sql = "INSERT INTO job ( ".$sql_name."mo_agent,adcode,mo_id,session_id,url,referer_url,user_agent,reg_flg,reg_date ) VALUES(";
	$sql .= $sql_data.$mo_agent.",'".$_SESSION['MENS_AD_CODE']."','".$mobile_id."','".session_id()."','".$_SERVER['PHP_SELF']."','".$_SESSION['KIREIMO_REFERER']."','".$ua."',2,'".date("Y-m-d H:i:s"). "')";

	$rtn = $GLOBALS['mysqldb']->query( $sql );

	//送信処理-------------------------------------------------------------------
	mb_language("ja");
	mb_internal_encoding("UTF-8");

	$subject = "ご応募がありました。";
	$content .= "ご応募内容\r\n\r\n";
	$content .= "------------------------------------------------------\r\n\r\n";
	$content .= "■お名前 : ".$_POST['entry_name']." ".$_POST['entry_name_2']."\r\n\r\n";
	$content .= "■お名前(カナ) : ".$_POST['entry_name_kana']." ".$_POST['entry_name_kana_2']."\r\n\r\n";

	$sex = ($_POST['sex'] == 'sex_s00') ? '男' : '女';
	$content .= "■性別 : " . $sex . "\r\n\r\n";
	$content .= "■年齢 : ".$_POST['age']."歳\r\n\r\n";
	$content .= "■生年月日 : ".$_POST['birthday']."\r\n\r\n";
	$content .= "■郵便番号 : ".$_POST['zip']."\r\n\r\n";
	$content .= "■ご住所 : ".$gPref2[$_POST['pref']].$_POST['now_address_1'].$_POST['now_address_2']."\r\n\r\n";
	$content .= "■電話番号 : ".$_POST['now_tel_1']."\r\n\r\n";
	$content .= "■携帯番号 : ".$_POST['now_tel_2']."\r\n\r\n";
	$content .= "■メールアドレス : ".$_POST['now_email']."\r\n\r\n";

	$content .= "■最寄り駅 : ".$_POST['line']."線".$_POST['station']."駅\r\n\r\n";
	$content .= "■ご希望店舗 : ".$_POST['shop']."\r\n\r\n";
	$content .= "■採用対象 : ".$_POST['type']."\r\n\r\n";
	$content .= "■脱毛サロン勤務経験 : ".$_POST['exeperience_c']."\r\n\r\n";
	// $content .= "■経験年数 : ".$_POST['exeperience_y']."年\r\n\r\n";
	$content .= "■ご応募のきっかけ : ".$_POST['opportunity']."\r\n\r\n";
	$content .= "■自己PR : ".$_POST['input_form_title_tab_self_pr']."\r\n\r\n";
	$content .= "■ご質問等 : ".$_POST['comment']."\r\n\r\n";
	$content .= "------------------------------------------------------\r\n\r\n";

	//管理者へ送信		
	$content1  = $content;	
	$content1 .= "\r\n【IP】\r\n".$_SERVER['REMOTE_ADDR']."\r\n";	
	$content1 .= "【ブラウザ】\r\n".$_SERVER['HTTP_USER_AGENT'];	
	
	$bcc 	  .="\r\n"."bcc: ".$send_mail_bcc;

	// $res = mb_send_mail($send_mail_address, $subject, $content1, "From:".$_POST['now_email'].$bcc);
    $kagoya = new KagoyaSendMail();
	$res = $kagoya->send($send_mail_address, $subject, $content1, "From:".$_POST['now_email'].$bcc);

	$subject2 = "【キレイモ】ご応募受付のお知らせ";
	$content2 .= $_POST['entry_name']." ".$_POST['entry_name_2']."　様\r\n\r\n";

	$content2 .= "キレイモを運営しております\r\n";
	$content2 .= "株式会社ヴィエリスでございます。\r\n";
	$content2 .= "この度は、ご応募いただきありがとうござます。\r\n\r\n";

	$content2 .= "本日より2～3日営業日以内にご返答しますので、\r\n";
	$content2 .= "今しばらくお待ちいただきますようお願いいたします。\r\n\r\n";
	$content2 .= $content;

	$content2 .= "※ このメールはサーバーより自動返信いたしております。\r\n\r\n";

	$content2 .= "●∞∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞∞● \r\n";
	$content2 .= "株式会社ヴィエリス　カスタマーサポート\r\n";
	$content2 .= "〒106-0032\r\n"; 
	$content2 .= "東京都港区六本木4-8-6パシフィックキャンセルプラザ8F\r\n"; 
	$content2 .= "電話番号：03-6721-1641（受付時間：月〜金9:00〜20:00（日・祝日除く））\r\n"; 
	$content2 .= "メールアドレスinfo@vielis.co.jp\r\n";
	$content2 .= "●∞∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞∞● \r\n";

	//自動返信
	$from = "From:".$send_mail_address."\n";
	$from.="Bcc: ".$send_mail_bcc;

	// $res = mb_send_mail($_POST['now_email'],$subject2,$content2,$from);
	$res = $kagoya->send($_POST['now_email'],$subject2,$content2,$from);

	$msg='
<div class="block_thanks">

<p style="text-align:center;font-weight: bold;font-size:170%;">ご応募頂き、ありがとうございます。</p>
<p>ご応募に関する受付メール(自動返信メール)を、ご登録のメールアドレス宛にお送りさせていただきましたのでメールをご確認ください。</p>
<p>ご応募内容に関しては2日～3日営業日までにこちらのinfo@vielis.co.jpのアドレスよりご連絡差し上げます。</p>
<p>10分以上経過しても受付メールが届かない場合は、迷惑メールフォルダに入っているか、入力ミスもしくは何らかの問題が考えられますので、再度フォームより申し込みいただくか、以下のアドレスまでご連絡ください。</p>
<p>ご不明な点は、<a href="mailto:info@vielis.co.jp">info@vielis.co.jp</a>までお問い合わせください。 </p>

</div>

<script src="//platform.twitter.com/oct.js" type="text/javascript"></script>
<script type="text/javascript">
twttr.conversion.trackPid("l4hqn");
</script>
<noscript>
<img height="1" width="1" style="display:none;" alt="" src="https://analytics.twitter.com/i/adsct?txn_id=l4hqn&p_id=Twitter" />
<img height="1" width="1" style="display:none;" alt="" src="//t.co/i/adsct?txn_id=l4hqn&p_id=Twitter" />
</noscript>


<!-- Facebook Conversion Code for kireimo求人CV -->
<script type="text/javascript">
var fb_param = {};
fb_param.pixel_id = "6016933106925";
fb_param.value = "1.00";
fb_param.currency = "JPY";
(function(){
var fpw = document.createElement("script");
fpw.async = true;
fpw.src = "//connect.facebook.net/en_US/fp.js";
var ref = document.getElementsByTagName("script")[0];
ref.parentNode.insertBefore(fpw, ref);
})();
</script>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/offsite_event.php?id=6016933106925&amp;value=1&amp;currency=JPY" /></noscript>

';

if(!$_POST['id'] && $_SESSION['AFFILIATE_ID']) {
    $tag_conversion = str_replace("%%afid%%",  $_SESSION['AFFILIATE_ID'], $tag_conversion);
    $tag_conversion = str_replace("%%id%%", $_POST['no'], $tag_conversion);
}elseif(!$_POST['id']){
    $tag_conversion = str_replace("%%id%%", $_POST['no'], $tag_conversion);
}

$msg .= $tag_conversion;

}
?>
