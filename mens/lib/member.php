<?php
#--------------------------------------------------#
# 会員用環境依存関数                               #
#--------------------------------------------------#

// 会員用環境パス
define("MEMBER_DIR", "../lib/member/");

// ログ取得用設定
define("LOG_DIR", "../../mypage_log/");

// クッキー（セッション）情報確認
function Member_Cookie_Check() {
	if (!isset($_COOKIE["PHPSESSID"])){
		header("Location: ./login.html");
		exit();
	}
}

// セッション情報確認
function Member_Session_Check() {
	//セッション情報に顧客情報が存在するか確認（無ければ不正アクセスとして、ログイン画面に遷移）
	if (!isset($_SESSION["customerInfo"]["id"])){
		$_SESSION = array();
		//クッキーの破棄
		if (isset($_COOKIE[session_name()])) {
			setcookie(session_name(), '', time() - 864000, '/');
		}
		session_destroy();
		header("Location: ./login.html");
		exit();
	}

	//セッションIDを変更
	if (!session_regenerate_id()){
		header("Location: ./login.html");
		exit();
	}
}
?>
