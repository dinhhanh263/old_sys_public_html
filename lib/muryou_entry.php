<?php

$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/config/config.php';
require_once LIB_DIR . 'KagoyaSendMail.php';


$table = "muryou_customer";

// 初期化処理
$_SESSION["data"] = array();
$_SESSION["errmsg"] = array();
$mode = $_REQUEST['mode'];
$act = $_REQUEST['act'];

// 登録用カラム
$db_arr = array("name","age","mail","referer_url");

// 送信先メール 設定
$send_mail_address = "muryou@kireimo.co.jp";
$send_mail_bcc = "negishi.vielis@gmail.com";

// メールからの登録処理
if(isset($_REQUEST["id"]) && ("reg"==$_REQUEST['act'])){

	// (仮)登録済みのチェック
	// if($tmp_data = Get_Table_Row($table,' WHERE del_flg=0 AND id = ' . $_REQUEST['id'])){
    $pdo->prepare('SELECT * FROM muryou_customer WHERE del_flg = 0 AND id = ?');
    $pdo->bindParam($_REQUEST['id']);
    $pdo->execute();
    $tmp_data = $pdo->fetch();

	// (仮)登録済みのチェック
	if(!empty($tmp_data)){
		// 本登録済み
		if($tmp_data["reg_flg"] == 1){
			// ## 本登録済みです!!とメッセージ
			header("Location: index.html?reg_err=既に無料会員サイトへ本登録済みです。#form_top");	
			exit();			  
		// 仮登録済み
		} else {
			$data = $tmp_data;
		}

	// 新規登録無し
	} else {
		header("Location: index.html#form_top");
		exit();			  		
	}

// Formからの登録処理
} elseif($mode == 1) {

	// (仮)登録済みのチェック
	//$tmp_data = Get_Table_Row($table,' WHERE del_flg=0 AND mail = "' . $_POST['mail'] . '"');
    $pdo->prepare('SELECT * FROM ? WHERE del_flg = 0 AND mail = ?');
    $pdo->bindParam([$table, $_POST['mail']]);
    $pdo->execute();
    $tmp_data = $pdo->fetch();
    
	if(!empty($tmp_data) && !empty($tmp_data['mail'])) {
		// 本登録済み
		if($tmp_data["reg_flg"] == 1){
			// ## 本登録済みです!!とメッセージ
			header("Location: index.html?reg_err=既に無料会員サイトへ本登録済みです。#form_top");

			exit();
			  
		// 仮登録済み
		} else {
			// ## 仮登録済みです!!とメッセージ
			header("Location: index.html?reg_err=既に無料会員サイトへ仮登録済みです。仮登録完了メールより本登録をお願いします。#form_top");	
			exit();
		}
	}

	// 新規仮登録
	// 名前のスペースを全角に統一
	if($_POST['name']) 		$_POST['name'] 		= str_replace(" ", "　", $_POST['name']);//半角スペースを全角スペースに統一
	if($_POST['name']) 		$_POST['name'] 		= str_replace("　　", "　", $_POST['name']);//2スペースを1スペースに統一
	if($_POST['name']) 		$_POST['name'] 		= preg_replace('/^[ 　]+/u', '', $_POST['name']);//前全角スペース削除
	if($_POST['name']) 		$_POST['name'] 		= preg_replace('/[ 　]+$/u', '', $_POST['name']);//前全角スペース削除

	if($_POST['age']) $_POST["age"] = mb_convert_kana($_POST["age"], "a", "UTF-8");

	// バリチェック処理
	$errmsg = array();

	// お名前 チェック
	if(!isset($_POST["name"]) || $_POST["name"] == "") {
		$errmsg["name"] = "必須項目です。";
	} else if(!strpos($_POST["name"], "　")){
		$errmsg["name"] = "姓と名の間にスペースを入れてください。";
	}

	// 年齢 チェック
	if(!isset($_POST["age"]) || $_POST["age"] == "") {
		$errmsg["age"] = "必須項目です";
	} else if( !Che_Num($_POST["age"]) ) {
		$errmsg["age"] = "文字を入力してください";
	}

	// メールアドレス チェック
	if(!isset($_POST["mail"]) || $_POST["mail"] == "") {
		$errmsg["mail"] = "必須項目です";
	} else {
		$rec = Check_Email2($_POST["mail"]);

		if($rec["flg"] == 1) {
			$errmsg["mail"] = $rec["error"];
		}
	}

	// 入力チェックでエラーの場合、本登録フォーム(index.html)へ遷移
	if(count($errmsg)){
		$_SESSION["data"] = $_POST;
		$_SESSION["errmsg"] = $errmsg;
		header("Location: index.html?err=1#form_top");
		exit();
	}

	$data = $_POST;

	// POST整形-----------------------------------------------------------------
	foreach($_POST as $key => $val){
		$_POST[$key] = htmlspecialchars($val);
		if ( get_magic_quotes_gpc() ) $_POST[$key] = stripslashes($val);

		if(in_array($key,$db_arr)){ 
			//SQL文生成
			$sql_name .= $key.",";
			$sql_data .= "'".$_POST[$key]. "',";
		}
	}
	
	// DB登録-------------------------------------------------------------------
	$mobile_id = get_mobile_id();
	// $sql = "INSERT INTO " . $table . " ( ".$sql_name."mo_agent,adcode,mo_id,session_id,url,user_agent,reg_date ) ";
	$sql = "INSERT INTO muryou_customer ( mo_agent, adcode, mo_id, session_id, url, user_agent, reg_date ) ";
	$sql .= ' VALUES( ?, ?, ?, ?, ?, ?, ?);';
	// $sql .= $sql_data.$mo_agent.",'".$_SESSION['AD_CODE']."','".$mobile_id."','".session_id()."','".$_SERVER['PHP_SELF']."','".$ua."','".date("Y-m-d H:i:s"). "')";
	// $rtn = mysql_query( $sql );
	$pdo->prepare($sql);
	$pdo->bindParam([
	    $sql_data.$mo_agent,
	    $_SESSION['AD_CODE'],
	    $mobile_id,
	    session_id(),
	    $_SERVER['PHP_SELF'],
	    $ua,
	    date("Y-m-d H:i:s")
    ]);
    $pdo->execute();
	$id = pdo->lastInsertId();
	$data['id'] = $id;

	// メール送信処理-------------------------------------------------------------------
	mb_language("ja");
	mb_internal_encoding("UTF-8");

	$subject = "仮登録のご応募がありました。";
	$content .= "仮登録のご応募内容\r\n\r\n";
	$content .= "------------------------------------------------------\r\n\r\n";
	$content .= "■名前 : ".$data['name']."\r\n\r\n";
	$content .= "■年齢 : ".$data['age']."歳\r\n\r\n";
	$content .= "■メール : ".$data['mail']."\r\n\r\n";
	$content .= "------------------------------------------------------\r\n\r\n";

	//管理者へ送信		
	$content1  = $content;	
	$content1 .= "【IP】\r\n".$_SERVER['REMOTE_ADDR']."\r\n\r\n";	
	$content1 .= "【ブラウザ】\r\n".$_SERVER['HTTP_USER_AGENT'];	

	$bcc 	  .="\r\n"."bcc: ".$send_mail_bcc;

	// あとで解放の事。
	// $res = mb_send_mail($send_mail_address, $subject, $content1, "From:".$data['mail'].$bcc);
    $kagoya = new KagoyaSendMail();
	$res = $kagoya->send($send_mail_address, $subject, $content1, "From:".$data['mail'].$bcc);

	$subject2 = "【KIREIMO無料会員サイト】仮登録いただきましてありがとうございます。";
	$content2 .= $data['name']."　様\r\n\r\n";

	$content2 .= "株式会社ヴィエリスでございます。\r\n";
	$content2 .= "この度はKIREIMO無料会員サイトの会員に仮登録いただきありがとうございました。\r\n";
	$content2 .= "下記URLより本会員登録の手続きをお願いいたします。\r\n\r\n";

	$content2 .= "■本会員登録\r\n";
	$content2 .= $home_url . "muryou/?id=".$data['id']."&act=reg\r\n\r\n";
	$content2 .= "このメールは送信専用メールアドレスから配信されております。\r\n";
	$content2 .= "ご返信についてはお受けできませんのでご了承ください。\r\n";

	$content2 .= "注意事項\r\n";
	$content2 .= "※本登録をこのまま完了しないと今回のプレゼント当選は対象外とさせていただきます。\r\n\r\n";

	$content2 .= "※本メールは、KIREIMO無料会員サイトより\r\n";
	$content2 .= "　会員登録を希望された方にお送りしています。\r\n";
	$content2 .= "　もしお心当たりが無い場合はこのままこのメールを破棄していただ\r\n";
	$content2 .= "　ければ会員登録はなされません。\r\n";
	$content2 .= "　またその旨muryou@kireimo.jpまで\r\n";
	$content2 .= "　ご連絡いただければ幸いです。\r\n\r\n";

	$content2 .= "◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇ \r\n\r\n";
	$content2 .= "配信元：株式会社ヴィエリス\r\n\r\n";
	$content2 .= "メールでのお問合せ（24時間OK）\r\n";
	$content2 .= "muryou@kireimo.jp\r\n\r\n";
	$content2 .= "◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇ \r\n";
	$content2 .= "\r\n※本メールは自動配信メールです。このメッセージに返信しないようお願いいたします。";

	//自動返信
	$from = "From:".$send_mail_address."\n";
	$from.="Bcc: ".$send_mail_bcc;

	// あとで解放の事。
	// $res = mb_send_mail($data['mail'],$subject2,$content2,$from);
	$res = $kagoya->send($data['mail'],$subject2,$content2,$from);

// muryou_thanks からの入力チェックエラー処理
} elseif($_REQUEST["err"] == 2) {
	$errmsg = $_SESSION["errmsg2"];
	$data = $_SESSION["data2"];

// 直リンクの場合、無料会員トップへ
} else {
	header("Location: index.html");
	exit();			  		
}


// // facebook処理
// $facebook = new Facebook(array(
// 	'appId' => 	'809159049123016',
// 	'secret' => '15b4183de0c830348f9d3f39cda56ce9',
// 	'cookie' => true
// ));

// //オブジェクトの作成
// $user = 				$facebook->getUser();
// $me = 					null;
// $friends = 				null;
// if($user){
// 	try{
// 		$uid = 			$facebook->getUser();
// 		$me = 			$facebook->api('/me');//ログイン情報
// 		// $friends = 		$facebook->api('/me/friends');//友達情報
// 	}
// 	catch(FacebookApiException $err){
// 		error_log($err);
// 	}
// }
// echo '<br>';
// var_dump($me);
// $parameters = array(
// 	// 'req_perms' => 'email'
// 	'scope' => 'email,user_birthday'
// );

// //ログインユーザーの確認
// if($me){
// 	$logoutUrl = $facebook->getLogoutUrl($parameters);
// }else{
// 	$loginUrl = $facebook->getLoginUrl($parameters);
// }


?>