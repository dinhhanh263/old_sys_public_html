<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja-JP">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta http-equiv="content-style-type" content="text/css"/>
	<meta name="description" content="">
	<meta name="Keywords" content="">

</head>

<body>


<div align="center">


<?php


// 文字コード
$enc_disp = "utf8";
$enc_db = "utf8";


// 接続設定（サーバ／データベース／ユーザ／パスワード）
define("DB_SV", "localhost");
define("DB_NAME", "kireimo");
define("DB_USER", "kireimo");
define("DB_PASS", "qJnyVAw0");
$conn = mysqli_connect(DB_SV, DB_USER, DB_PASS) or die("接続エラー");
define("DB_CONN", $conn);

$GLOBALS['mysqldb']->select_db(DB_NAME) or die("接続エラー");


// デバック用
$sql = "SET NAMES utf8";

$GLOBALS['mysqldb']->query($sql);



$sql = "SELECT id FROM z_mail where mail = '".$_REQUEST["ml"]."';";

$res = $GLOBALS['mysqldb']->query($sql, $conn) or die("データ抽出エラー");

$count = $res->num_rows();

if( $count != 0 ){

	$sql = "UPDATE z_mail set kaijo_flg=1 ,edit_date=now() WHERE mail ='".$_REQUEST["ml"]."'";
	$res = $GLOBALS['mysqldb']->query($sql, $conn) or die("データ抽出エラー");
}


// echo $count;



// 接続を解除する
mysqli_close(DB_CONN);



?>

<p></p>
<p></p>
<p></p>
</div>

</body>
</html>