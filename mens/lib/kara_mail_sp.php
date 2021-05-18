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
include_once( "../../lib/tag.php" );
require_once LIB_DIR . 'KagoyaSendMail.php';

//session_save_path("../../tmp");
session_start();
#----------------------------------------------------------------------------------------------------------------------#
# メール送信                                                                                                             #
#----------------------------------------------------------------------------------------------------------------------#
if($_POST['mode']=="send"){
	if($_POST['mail'] =="" || preg_match("/^( |　)+$/", $_POST['mail']))
	 	$errmsg['mail'] = "<span class='error'>メールアドレスを入力してください</span>";
	elseif(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $_POST['mail'])){
		$errmsg['mail'] = "<span class='error'>正しいメールアドレスを入力してください</span>";
	}

	if(!is_array($errmsg)) {
		//同メールアドレス制限
		if(!($user = Get_Table_Row("mail_member"," WHERE del_flg=0 AND mail ='{$_POST['mail']}'" )) ){

			//メールアドレス登録
			$_POST['reg_date'] = date("Y-m-d H:i:s");
			$_POST['mo_agent'] = $mo_agent;
			$_POST['adcode'] = $_SESSION['MENS_AD_CODE'];
			$_POST['mo_id'] = $mobile_id;
			$_POST['session_id'] = session_id();
			$_POST['referer_url'] = $_SESSION['KIREIMO_REFERER'];
			$_POST['user_agent'] = $ua;

			$customer_field = array("mail","reg_date","mo_agent","adcode","mo_id","session_id","referer_url","user_agent");
			$data_ID = Input_New_Data("mail_member",$customer_field);

    	}
    	//自動送信*****************************************************************
			mb_language("ja");
			mb_internal_encoding("UTF-8");
			$returnpath = "-f ".SEND_MAIL_ADDRESS;

			$subject = "メールアドレス登録がありました。";
			$content .= "【KIREIMOメールサービス】にご登録いただき誠にありがとうございます。

渋谷店、池袋店のオープン情報や最新の予約情報などいち早くお届けいたしますので、お楽しみにして下さい。

何かご不明な点などございましたら、お気軽に下記フリーダイヤルまでご連絡ください。
【お問い合せ番号】0120-567-144

◆◇◆◇◆◇◆◇◆◇
本メールは自動送信メールです。
本メールにご返信いただいても、メール内容の確認及びご返答をおこなうことができません。
本メールに覚えのない方は、削除していただきますようお願いいたします。
◆◇◆◇◆◇◆◇◆◇
新・脱毛サロンKIREIMO
0120-567-144
営業時間　11:00～21:00

"; 

			//管理者へ送信
			$content1 .= "ご登録メールアドレス\r\n";
			$content1 .= $_POST['mail'];
			$content1 .= "\r\n\r\n--\r\n"; 	
			$content1 .= $content;	
			$content1 .= "\r\n【参照元】\r\n".$_SESSION['KIREIMO_REFERER']."\r\n";	
			$content1 .= "【ブラウザ】\r\n".$ua;			
			// mb_send_mail(SEND_MAIL_ADDRESS, $subject, $content1, "From:".SEND_MAIL_ADDRESS,$returnpath);
            $kagoya = new KagoyaSendMail();
			$kagoya->send(SEND_MAIL_ADDRESS, $subject, $content1, "From:".SEND_MAIL_ADDRESS,$returnpath);

			$subject2 = "【KIREIMO】メールアドレス登録完了メール（自動返信）";
			$content2 .= $content;

			//自動返信
			$from = "From:".SEND_MAIL_ADDRESS."\n";
			$from.= "Bcc: ".SEND_MAIL_BCC;

			// mb_send_mail($_POST['mail'],$subject2,$content2,$from,$returnpath);
			$kagoya->send($_POST['mail'],$subject2,$content2,$from,$returnpath);
    	
    	//完了ページへ
		header("Location: ./mail_complete.html?mailmemberid=".$data_ID);
    }
}
?>
