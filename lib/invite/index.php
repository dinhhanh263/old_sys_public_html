<?php
/*
#--------------------------------------------------#
# 友達紹介ページ                                  #
#--------------------------------------------------#
*/
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/config/config.php';
require_once LIB_DIR . 'function.php';
require_once LIB_DIR . 'db.php';
require_once LIB_DIR . 'member.php';
require_once LIB_DIR . 'classes/encryption.php';


//	session_save_path("../tmp");
	session_start();

	$params = '';

	// エラーメッセージのセッションが存在する
	if ( isset($_SESSION['errorMessage']) ) {
		$error_msg = $_SESSION['errorMessage'];
		unset($_SESSION['errorMessage']);
	} else {
		$error_msg = '';
	}

	// URLパラメータが存在する時
	if (isset($_GET['code'])) {

		$inviteCode = isset($_GET['code'])? $encryption->decode($_GET['code']) : '';

		$customerInfo = Get_Table_Row("customer"," WHERE del_flg=0 and no = '" . $GLOBALS['mysqldb']->real_escape_string($inviteCode) . "'");

		$params = isset($_GET['code'])? '?code='.$_GET['code'] : '';

		if ( $customerInfo == "" || $customerInfo == false ) {
			$inviteCode = '';
				if ( !isset($_SESSION['redirectLoopFlg']) ) {
					$_SESSION['redirectLoopFlg'] = true;
					$_SESSION['errorMessage'] = '入力された会員番号の顧客が見つかりません。';
					header("Location: ../invite/index.html" . $params);
				} else {
					unset($_SESSION['redirectLoopFlg']);
				}
		}

	}

	// 入力データ読み込み
	if ( isset($_SESSION['formDataDescription']['inviteCode']) ) {
		$formData = $_SESSION['formDataDescription']['inviteCode'];
		unset($_SESSION['formDataDescription']);
	}

	// お申し込みボタンを押下した時
	if (isset($_POST['submitDescription']) ) {

		$inviteCode = isset($_POST['inviteCode'])? $_POST['inviteCode'] : '';

		// 未入力時
		if ( $inviteCode == '' ) {
			$_SESSION['formDataDescription'] = $_POST;
			$_SESSION['errorMessage'] = '会員番号を入力してください。';
			header("Location: ../invite/index.html" . $params . '#formDescription');
			exit;
		}

		$customerInfo = Get_Table_Row("customer"," WHERE del_flg=0 and no = '" . $GLOBALS['mysqldb']->real_escape_string($inviteCode) . "'");

		if ( isset($customerInfo) && $customerInfo !== false ) {
			unset($_SESSION['formDataDescription']);
			$_SESSION['invite_customerId'] = $customerInfo['id'];
			header("Location: ../counseling");
		} else {
			$_SESSION['formDataDescription'] = $_POST;
			$_SESSION['errorMessage'] = '入力された会員番号の顧客が見つかりません。';
			header("Location: ../invite/index.html" . $params . '#formDescription');
		}

	}
