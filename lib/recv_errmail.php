#!/usr/local/bin/php -q
<?php

$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/config/config.php';
include_once( "./function.php" );
define_approot($_SERVER['SCRIPT_FILENAME'],1);

//DBに接続してデータ登録
$conn = mysqli_connect(HOST_NAME, DB_UESR, DB_PW);
$GLOBALS['mysqldb']->select_db(DB_NAME,$conn);

//メールソースを標準入力から読み込み
$source = file_get_contents("php://stdin");
if(!$source){
	echo "標準入力に失敗\n";
	exit(0);
}

// To:を抽出する
ereg('To: .+@[0-9a-zA-Z_\.\-]+',$source,$tolist);
ereg('[0-9a-zA-Z_\.\-]+@[0-9a-zA-Z_\.\-]+',$tolist[0],$to);
$to = $to[0];

//一番目のTOを消去
$source2 = str_replace($to,"",$source);
$source2 = str_replace("Delivered-To:","",$source2);

ereg('To: .+@[0-9a-zA-Z_\.\-]+',$source2,$tolist2);
ereg('[0-9a-zA-Z_\.\-]+@[0-9a-zA-Z_\.\-]+',$tolist2[0],$to2);
$errmail = $to2[0];

//tabel_mail_history:id取得
if (preg_match('/___(\S+?)___/', $source, $match))$id =  $match[1];

$mailmg = Get_Table_Row("mail_history"," WHERE id = '".addslashes($id)."'");

//テーブル設定
$table = $mailmg['scenario_id'] ? "mail_scenario_data" : "user" ;

//条件の設定
$where = " where mail='" . $errmail . "'";
$where .= $mailmg['scenario_id'] ? " and scenario_id='" . $mailmg['scenario_id'] . "'" : "" ;

//err_flg標記
$sql = "update {$table} set err_flg=1 ".$where;

$GLOBALS['mysqldb']->query($sql);

//成功配信/エラー数調整
$GLOBALS['mysqldb']->query("update mail_history set success_cnt=success_cnt-1, err_cnt=err_cnt+1 where id='$id'");

?>