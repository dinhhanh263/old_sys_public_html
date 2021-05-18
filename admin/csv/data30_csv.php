<?php
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
require_once LIB_DIR . 'auth.php';

$post_date1 = filter_input( INPUT_POST, "date1" );
$post_date2 = filter_input( INPUT_POST, "date2" );

$date1 = $post_date1 ? $post_date1 : date("Y-m-d");
$date2 = $post_date2 ? $post_date2 : date("Y-m-d");

// データの取得----------------------------------------------------------------------
$dSql = 'SELECT
	(SELECT no FROM customer WHERE id = customer_id) "会員番号" ,
	(SELECT name FROM customer WHERE id = customer_id) "会員氏名" ,
	(SELECT code FROM staff WHERE id = staff_id) AS "従業員番号" ,
	(SELECT name FROM staff WHERE id = staff_id) AS "従業員氏名" ,
	(SELECT code FROM staff WHERE id = introducer_staff_id) AS "紹介元従業員番号" ,
	(SELECT name FROM staff WHERE id = introducer_staff_id) AS "紹介元従業員氏名" ,
	(SELECT name FROM course WHERE id = course_id) AS "契約コース名" ,
	contract_date AS "契約日" ,
	pay_complete_date AS "支払完了日" ,
	price AS "請求金額",
	balance AS "売掛金",
	CASE
		WHEN contract.status = 0 THEN "契約中"
		WHEN contract.status = 1 THEN "契約終了"
		WHEN contract.status = 2 THEN "クーリングオフ"
		WHEN contract.status = 3 THEN "中途解約"
		WHEN contract.status = 4 THEN "プラン変更"
		WHEN contract.status = 5 THEN "ローン取消"
		WHEN contract.status = 6 THEN "自動解約"
		WHEN contract.status = 7 THEN "契約待ち"
		WHEN contract.status = 8 THEN "返金保証回数終了"
		WHEN contract.status = 9 THEN "期限切れ解約"
		WHEN contract.status = 10 THEN "未成年プラン終了"
		WHEN contract.status = 11 THEN "月額休会中"
	END AS "契約ステータス"
FROM contract WHERE del_flg = 0 AND status IN (0,3,4) AND (SELECT type FROM course WHERE id = course_id) = 0 AND old_contract_id = 0 AND (SELECT treatment_type FROM course WHERE id = course_id) IN (1,2) AND old_course_id = 0 AND (SELECT ctype FROM customer WHERE id = customer_id) = 1 AND SUBSTRING(pay_complete_date, 1, 7 ) = "'.$date1.'" AND customer_id NOT IN (SELECT customer_id FROM contract WHERE id IN (SELECT old_contract_id FROM contract WHERE id IN (SELECT loan_cancel_before_contract_id FROM contract WHERE id IN (SELECT id FROM contract WHERE del_flg = 0 AND status IN (0,3,4) AND (SELECT type FROM course WHERE id = course_id) = 0 AND old_contract_id = 0 AND old_course_id = 0 AND (SELECT ctype FROM customer WHERE id = customer_id) = 1 AND SUBSTRING(pay_complete_date, 1, 7 ) = "'.$date1.'"))))';

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//csv export----------------------------------------------------------------------
$filename = "csv_export.csv";
header("Content-type:text/csv");
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("会員番号,会員氏名,従業員番号,従業員氏名,紹介元従業員番号,紹介元従業員氏名,契約コース名,契約日,支払完了日,請求金額,売掛金,契約ステータス\n","SJIS-win", "UTF-8");

	while ( $data = $dRtn3->fetch_assoc() ) {
		echo mb_convert_encoding($data['会員番号'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['会員氏名'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['従業員番号'],"SJIS-win", "UTF-8"). ",";
		echo mb_convert_encoding($data['従業員氏名'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['紹介元従業員番号'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['紹介元従業員氏名'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['契約コース名'],"SJIS-win", "UTF-8"). ",";
        echo mb_convert_encoding($data['契約日'],"SJIS-win", "UTF-8"). ",";
        echo mb_convert_encoding($data['支払完了日'],"SJIS-win", "UTF-8"). ",";
		echo mb_convert_encoding($data['請求金額'],"SJIS-win", "UTF-8"). ",";
		echo mb_convert_encoding($data['売掛金'],"SJIS-win", "UTF-8"). ",";
		echo mb_convert_encoding($data['契約ステータス'],"SJIS-win", "UTF-8"). ",";

		echo "\n";
	}

	// CSV Export Log
	// setCSVExportLog($_POST['csv_pw'],$filename);
}

