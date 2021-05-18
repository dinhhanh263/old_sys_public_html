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
SELECT c.id '顧客ID', c.no '会員番号',c.name '名前', c.name_kana '名前カナ',c.card_name_kana 'カード名義（カナ）',c.card_name 'カード名義(ローマ字)',c.card_no 'カード下4桁', c.memo '備考'
FROM  `customer` c,contract t
WHERE c.del_flg =0
AND (
c.memo LIKE  '%カード%'
OR c.card_no <>  ''
)
AND c.id=t.customer_id
AND t.del_flg=0
AND t.status in (0,7)

";

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//csv export----------------------------------------------------------------------
$filename = "csv_export.csv";
header("Content-type:text/csv");
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("顧客ID,会員番号,名前,名前カナ,カード名義（カナ）,カード名義(ローマ字),カード下4桁,備考\n","SJIS-win", "UTF-8");
	
	while ( $data = $dRtn3->fetch_assoc() ) {
		$data['備考'] = str_replace(",", "、", $data['備考']);
		$data['備考'] = str_replace("\n", " ", $data['備考']);
		$data['備考'] = str_replace("\r", " ", $data['備考']);
		echo mb_convert_encoding($data['顧客ID'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['会員番号'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['名前'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['名前カナ'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['カード名義（カナ）'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['カード名義(ローマ字)'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['カード下4桁'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['備考'],"SJIS-win", "UTF-8") . ","; 
		echo "\n";

	}

	// CSV Export Log 
	// setCSVExportLog($_POST['csv_pw'],$filename);
}

