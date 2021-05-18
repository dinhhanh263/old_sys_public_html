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
include_once( "../../lib/auth.php" );

// データの取得----------------------------------------------------------------------
$dSql = "
SELECT
	staff.code AS '社員番号',
	shop.name AS '所属',
	staff.name '名前',
	staff.class AS '期',
	staff.new_face AS '新人',
	CASE staff.type
        WHEN '1' THEN '本社'
        WHEN '2' THEN '統括MG'
        WHEN '3' THEN 'エリアMG'
        WHEN '4' THEN 'エキスパートアドバイザー'
        WHEN '5' THEN '統括店長'
        WHEN '6' THEN 'SV'
        WHEN '7' THEN '店長'
        WHEN '8' THEN 'チーフアドバイザー'
        WHEN '9' THEN 'チーフセラピスト'
        WHEN '10' THEN '主任'
        WHEN '11' THEN 'セラピスト'
        WHEN '12' THEN 'アドバイザー'
        WHEN '13' THEN '副主任'
        WHEN '15' THEN 'トレーナー'
        WHEN '17' THEN 'スタッフ'
        WHEN '30' THEN '研修生'
        WHEN '22' THEN 'スタッフ（契約）'
        WHEN '23' THEN 'スタッフ（時短）'
        WHEN '24' THEN 'スタッフ（アルバイト）'
        WHEN '18' THEN '受付事務'
        WHEN '19' THEN '派遣施術'
        WHEN '21' THEN '派遣事務'
	END '役職'
FROM staff INNER JOIN shop ON staff.shop_id = shop.id WHERE staff.del_flg = 0 AND staff.status = 2 AND staff.end_day = '0000-00-00';
";

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//csv export----------------------------------------------------------------------
$filename = "csv_export.csv"; 
header("Content-type:text/csv"); 
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("社員番号,所属,名前,期,新人,役職\n","SJIS-win", "UTF-8");
	
	while ( $data = $dRtn3->fetch_assoc() ) {

		echo mb_convert_encoding($data['社員番号'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['所属'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['名前'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['期'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['新人'],"SJIS-win", "UTF-8") . ",";
		echo mb_convert_encoding($data['役職'],"SJIS-win", "UTF-8");
		echo "\n";

	}

	// CSV Export Log 
	// setCSVExportLog($_POST['csv_pw'],$filename);
}

