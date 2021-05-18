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
require_once LIB_DIR . 'classes/encryption.php';

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

		$myCode = isset($_GET['code'])? $encryption->decode($_GET['code']) : '';

		$customerInfo = Get_Table_Row("customer"," WHERE del_flg=0 and no = '" . $GLOBALS['mysqldb']->real_escape_string($myCode) . "'");

		$params = isset($_GET['code'])? '?code='.$_GET['code'] : '';

		if ( $customerInfo == "" || $customerInfo == false ) {
			$myCode = '';
				if ( !isset($_SESSION['redirectLoopFlg']) ) {
					$_SESSION['redirectLoopFlg'] = true;
					$_SESSION['errorMessage'] = '入力された会員番号の顧客が見つかりません。';
					header("Location: ../introduction/index.html" . $params);
				} else {
					unset($_SESSION['redirectLoopFlg']);
				}
		}

	}

	// 入力データ読み込み
	if ( isset($_SESSION['formDataDescription']['formDataInput__myCode']) ) {
		$formData = $_SESSION['formDataDescription']['formDataInput__myCode'];
		unset($_SESSION['formDataDescription']);
	}

	// お申し込みボタンを押下した時
	if (isset($_POST['buttonDescription']) ) {

		$myCode = isset($_POST['formDataInput__myCode'])? $_POST['formDataInput__myCode'] : '';


		// 未入力時
		if ( $myCode == '' ) {
			$_SESSION['formDataDescription'] = $_POST;
			$_SESSION['errorMessage'] = '会員番号を入力してください。';
			header("Location: ../introduction/index.html" . $params . '#introductionIdDescription');
			exit;
		}

		$customerInfo = Get_Table_Row("customer"," WHERE del_flg=0 and no = '" . $GLOBALS['mysqldb']->real_escape_string($myCode) . "'");

		if ( isset($customerInfo) && $customerInfo !== false ) {
			unset($_SESSION['formDataDescription']);
			$_SESSION['introduction__customerId'] = $customerInfo['id'];
			'存在する会員番号です。';
			header("Location: ../counseling");
		} else {
			$_SESSION['formDataDescription'] = $_POST;
			$_SESSION['errorMessage'] = '入力された会員番号の顧客が見つかりません。';
			header("Location: ../introduction/index.html" . $params . '#introductionIdDescription');
		}

	}
