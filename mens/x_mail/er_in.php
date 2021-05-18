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


$sql = "SET NAMES utf8";

// デバック用
//$sql = "SET NAMES EUC-JP";

$GLOBALS['mysqldb']->query($sql);


// データの文字コードを変換する関数
function cnv_enc($string, $to, $from) {
    
		// 文字コードを変換する
		$det_enc = mb_detect_encoding($string, $from . ", " . $to);

		if ($det_enc  and $det_enc != $to) {
			return mb_convert_encoding($string, $to, $det_enc);
		}
		else {
			return $string;
		}
}

// データをSQL用に変換
function cnv_sqlstr($string) {
	   	if (get_magic_quotes_gpc()) {
    		$string = stripslashes($string);
    	}
    	$string = htmlspecialchars($string);
    	$string = $GLOBALS['mysqldb']->real_escape_string($string);

    	return $string;
}

// データ変換
function data_cnv($str, $str_if, $str_rst) {
	if ($str == $str_if)
		return $str_rst;
	else
		return $str;
}


if ($_REQUEST["mail"] == ""){

	echo '<p align="center">メールアドレスが空白です。<br><br>正しく入力してください。</p>';

}

else if ($_REQUEST["type"] == ""){

	echo '<p align="center">登録タイプが空白です。<br><br>正しく入力してください。</p>';

}
else if (($_REQUEST["type"] == "jp") or ($_REQUEST["type"] == "net")){

	// 登録

	$sql = "SELECT * FROM z_ermail WHERE mail ='".$_REQUEST["mail"]."'";

	$res = $GLOBALS['mysqldb']->query($sql, $conn) or die("データ抽出エラー");
	$count = $res->num_rows;


	if ($count == "0"){
		$sql = "INSERT INTO z_ermail (mail,erct,".$_REQUEST["type"].",reg_date,edit_date) values('".$_REQUEST["mail"]."',1,1,NOW(),NOW())";
		$res = $GLOBALS['mysqldb']->query($sql, $conn) or die("データ挿入エラー");
			echo '登録が完了しました。<br>';
	}

	// 更新
	else {
		$sql = "SELECT * FROM z_ermail WHERE mail ='".$_REQUEST["mail"]."'";

		$res = $GLOBALS['mysqldb']->query($sql, $conn) or die("データ抽出エラー");
		$count = $res->num_rows;

		while ($row = $res->fetch_array()) {
			$erct = $row["erct"];
			$jp = $row["jp"];
			$net = $row["net"];
		}	

		if ($count != 0){
			$erct = $erct + 1;
			$jp = $jp + 1;
			$net = $net + 1;
			
			if ($_REQUEST["type"]== "jp") {$cn= $jp;}
			if ($_REQUEST["type"]== "net") {$cn= $net;}
			
			$sql = "UPDATE z_ermail set erct = ".$erct.", ". $_REQUEST["type"]." = ".$cn.", edit_date=now() WHERE mail ='".$_REQUEST["mail"]."'";
			$res = $GLOBALS['mysqldb']->query($sql, $conn) or die("データ抽出エラー");

		}

		echo '更新が完了しました。<br>';

	}

}





// 接続を解除する
mysqli_close(DB_CONN);



?>

<p>　　　　　</p>
<p>　　　　　</p>
<p>　　　　　</p>
</div>

</body>
</html>