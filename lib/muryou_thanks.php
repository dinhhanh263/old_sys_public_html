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
$_SESSION["data2"] = array();
$_SESSION["errmsg2"] = array();
$mode = $_POST['mode'];

$db_arr = array("name","name_kana","age","zip","pref","address","mail","tel","present","reg_flg");

// メール送信先
$send_mail_address = "muryou@kireimo.co.jp";
$send_mail_bcc = "negishi.vielis@gmail.com";

// Formからの登録処理
if($mode == 2){

	// (仮)登録済みのチェック
	if($tmp_data = Get_Table_Row($table,' WHERE del_flg=0 AND mail = "'. $_POST['mail'] . '" AND reg_flg=1')){
		// 本登録済み
		// ## 本登録済みです!!とメッセージ
		header("Location: index.html?reg_err=既に無料会員サイトへ本登録済みです。#form_top");	
		exit();

	// 新規本登録
	} else {

		// 名前のスペースを全角に統一
		if($_POST['name']) 		$_POST['name'] 		= str_replace(" ", "　", $_POST['name']);//半角スペースを全角スペースに統一
		if($_POST['name']) 		$_POST['name'] 		= str_replace("　　", "　", $_POST['name']);//2スペースを1スペースに統一
		if($_POST['name']) 		$_POST['name'] 		= preg_replace('/^[ 　]+/u', '', $_POST['name']);//前全角スペース削除
		if($_POST['name']) 		$_POST['name'] 		= preg_replace('/[ 　]+$/u', '', $_POST['name']);//前全角スペース削除

		if($_POST['name_kana']) $_POST['name_kana'] = str_replace(" ", "　", trim($_POST['name_kana']));//半角スペースを全角スペースに統一
		if($_POST['name_kana'])	$_POST['name_kana'] = str_replace("　　", "　", trim($_POST['name_kana']));//2スペースを1スペースに統一
		if($_POST['name_kana'])	$_POST['name_kana'] = preg_replace('/^[ 　]+/u', '', $_POST['name_kana']);//前全角スペース削除
		if($_POST['name_kana']) $_POST['name_kana'] = preg_replace('/[ 　]+$/u', '', $_POST['name_kana']);//前全角スペース削除

		if($_POST['age']) $_POST["age"] = mb_convert_kana($_POST["age"], "a", "UTF-8");

		// バリチェック処理
		$errmsg = array();

		// 名前 チェック
		if(!isset($_POST["name"]) || $_POST["name"] == "") {
			$errmsg["name"] = "必須項目です";
		} else if(!strpos($_POST["name"], "　")){
			$errmsg["name"] = "姓と名の間にスペースを入れてください";
		}

		// 名前(カナ) チェック
		// フリガナ チェック
		if(!isset($_POST["name_kana"]) || $_POST["name_kana"] == "") {
			$errmsg["name_kana"] = "必須項目です";
		} else if(!preg_match("/^([　 \t\r\n]|[ァ-ヶー])+$/u", $_POST["name_kana"])){
			$errmsg["name_kana"] = "全角カタカナを入力してください";
		} else if(!strpos($_POST["name_kana"], "　")){
			$errmsg["name_kana"] = "姓と名の間にスペースを入れてください";
		}

		// 年齢 チェック
		if(!isset($_POST["age"]) || $_POST["age"] == "") {
			$errmsg["age"] = "必須項目です";
		} else if( !Che_Num($_POST["age"]) ) {
			$errmsg["age"] = "文字を入力して下さい";
		}

		// 郵便番号 チェック
		if((!isset($_POST["zip1"]) || $_POST["zip1"] == "")||(!isset($_POST["zip2"]) || $_POST["zip2"] == "")) {
			$errmsg["zip"] = "必須項目です";
		}

		// 都道府県 チェック
		// チェックボックスの為、不要

		// 住所 チェック
		if(!isset($_POST["address"]) || $_POST["address"] == "") {
			$errmsg["address"] = "必須項目です";
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

		// 電話 チェック
		if(!isset($_POST["tel"]) || $_POST["tel"] == "") {
			$errmsg["tel"] = "必須項目です";		
		} else {
			if($_POST['tel']) {
				$tel = sepalate_tel($_POST['tel']);
				if($tel != 0) {
					$_POST['tel'] = $tel;
				} else {
					$errmsg['tel'] = "電話番号が正しくありません";
				}
			}
		}

		// 入力チェックでエラーの場合、本登録フォーム(entry.html)へ遷移
		if(count($errmsg)){
			$_SESSION["data2"] = $_POST;
			$_SESSION["errmsg2"] = $errmsg;
			header("Location: entry.html?err=2#form_top");
			exit();
		}

		// 電話番号整形
		$_POST['tel'] = sepalate_tel($_POST['tel']);
		$_POST['reg_flg'] = 1;

		// POST整形-----------------------------------------------------------------
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

		$data = $_POST;

		// 完了処理
		// 申込件数集計--------------------------------------------------------------
		IncrementAccessLog3(date('Y-m-d'), 3, $mo_agent, $_SESSION['AD_CODE']);

		// DB更新-------------------------------------------------------------------
		Update_Data("muryou_customer", $db_arr, $_POST["id"]);

		// メール送信処理-------------------------------------------------------------------
		mb_language("ja");
		mb_internal_encoding("UTF-8");

		$subject = "本登録のご応募がありました。";
		$content .= "本登録のご応募内容\r\n\r\n";
		$content .= "------------------------------------------------------\r\n\r\n";
		$content .= "■名前 : ".$data['name']."\r\n\r\n";
		$content .= "■名前(カナ) : ".$data['name_kana']."\r\n\r\n";
		$content .= "■年齢 : ".$data['age']."歳\r\n\r\n";
		$content .= "■郵便番号 : ".$data['zip']."\r\n\r\n";
		$content .= "■都道府県 : ".$gPref2[$data['pref']]."\r\n\r\n";
		$content .= "■住所 : ".$data['address']."\r\n\r\n";
		$content .= "■メールアドレス : ".$data['mail']."\r\n\r\n";
		$content .= "■電話番号 : ".$data['tel']."\r\n\r\n";
		$content .= "■プレゼント : ".$gPresent[$data['present']]."\r\n\r\n";

		$content .= "------------------------------------------------------\r\n\r\n";

		//管理者へ送信		
		$content1  = $content;	
		$content1 .= "【IP】\r\n".$_SERVER['REMOTE_ADDR']."\r\n\r\n";	
		$content1 .= "【ブラウザ】\r\n".$_SERVER['HTTP_USER_AGENT'];	
		
		$bcc 	  .="\r\n"."bcc: ".$send_mail_bcc;

		// メール送信（管理者へ）
		// $res = mb_send_mail($send_mail_address, $subject, $content1, "From:".$data['now_email'].$bcc);
        $kagoya = new KagoyaSendMail();
		$res = $kagoya->send($send_mail_address, $subject, $content1, "From:".$data['now_email'].$bcc);

		$subject2 = "【KIREIMO無料会員サイト】本登録いただきましてありがとうございます。";
		$content2 .= $data['name']."　様\r\n\r\n";

		$content2 .= "キレイモを運営しております\r\n";
		$content2 .= "株式会社ヴィエリスでございます。\r\n\r\n";
		$content2 .= "この度はキレイモ無料会員サイトの会員に本登録いただきましてありがとうございます。\r\n";
		$content2 .= "登録内容をご確認のうえ、大切に保管してください。\r\n\r\n";

		$content2 .= $content;

		$content2 .= "このメールは送信専用メールアドレスから配信されております。\r\n";
		$content2 .= "ご返信についてはお受けできませんのでご了承ください。\r\n\r\n";

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

		// メール送信（会員へ）
		// $res = mb_send_mail($data['mail'],$subject2,$content2,$from);
		$res = $kagoya->send($data['mail'],$subject2,$content2,$from);

		// コンバージョン処理
		if(!$data['id'] && $_SESSION['AFFILIATE_ID']) {
		    $tag_conversion = str_replace("%%afid%%",  $_SESSION['AFFILIATE_ID'], $tag_conversion);
		    $tag_conversion = str_replace("%%id%%", $data['no'], $tag_conversion);
		}elseif(!$data['id']){
		    $tag_conversion = str_replace("%%id%%", $data['no'], $tag_conversion);
		}
	}
// 直リンクの場合、無料会員トップへ
} else {
	header("Location: index.html");
	exit();			  		
}

?>
