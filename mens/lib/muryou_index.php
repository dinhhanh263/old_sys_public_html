<?php
$table = "muryou_customer";

// 仮登録済みメールからの処理
if($_REQUEST['id'] && $_REQUEST['act']=="reg") {
	header( "Location: entry.html?id=".$_REQUEST['id']."&act=".$_REQUEST['act'] );
	exit();

// 登録済みエラー
} else if ($_REQUEST["reg_err"]) {
	$reg_err = $_REQUEST["reg_err"];

// muryou_entry からの入力チェックエラー処理
} elseif($_REQUEST["err"] == 1) {
	$errmsg = $_SESSION["errmsg"];
	$data = $_SESSION["data"];

}

?>
