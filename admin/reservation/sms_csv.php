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

// 検索条件の設定------------------------------------------------------------------------

$dWhere = "";
if( $_POST['keyword'] != "" ){
	$dWhere .= " AND ( ";
	$dWhere .= "  replace(c.name, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(c.name_kana, '　', '') LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or c.no LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(c.tel, '-', '') LIKE '%".addslashes( str_replace("-","",$_POST['keyword']) )."%'";
	$dWhere .= " or c.mail LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " ) ";
}
if($_POST['search_shop_id'])$dWhere .= " AND  r.shop_id='".$_POST['search_shop_id'] ."'";

// データの取得------------------------------------------------------------------------

$dSql = 'SELECT r.hope_date , r.hope_time,r.reg_date , c.no ,c.name, c.name_kana ,  c.tel, c.pair_name_kana , c.pair_tel , s.name as shop_name, 
s.id , r.id  as rid, c.id as cid, r.preday_status ,r.memo4 ,r.memo3
FROM reservation AS r, customer AS c, shop AS s
WHERE r.del_flg = 0
AND r.type = 1
AND substring(r.reg_date,1,10) <= "'. date("Y-m-d") .'"
AND r.hope_date =  (SELECT date_format((SELECT adddate(now(), interval + 2 day)),"%Y-%m-%d"))
AND c.id = r.customer_id
AND c.ctype = 1
AND c.del_flg = 0
AND r.shop_id = s.id
AND SUBSTR(c.tel,1,3) in (090,080,070) 
AND r.preday_status <> 1
AND r.today_status <>1
'.$dWhere.'
ORDER BY r.shop_id,r.reg_date';

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);


// 店舗リスト------------------------------------------------------------------------

$shop_list = getDatalist("shop");

// csv export----------------------------------------------------------------------
$filename = "sms_list.csv"; 
header("Content-type:text/csv"); 
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	//echo mb_convert_encoding("電話番号,お客様の苗字,予約店舗名,予約時間\n","SJIS-win", "UTF-8");
	while ( $data = $dRtn3->fetch_assoc() ) {
		$name = $data['name'] ? $data['name'] : $data['name_kana'];
		list($name1,$name2) = explode("　", $name);
		echo $data['tel']  . ",";
		// echo mb_convert_encoding($name1,"SJIS-win", "UTF-8")  . ",";
		echo ( mb_strlen($name1,"UTF-8")<=3 ? mb_convert_encoding($name1."様","SJIS-win", "UTF-8") : "" ) . ",";
		echo mb_convert_encoding($data['shop_name'],"SJIS-win", "UTF-8") . "," ;
		echo $gTime2[$data['hope_time']] ;
		// echo date("n/j",strtotime($data['hope_date'])).' '.$gTime2[$data['hope_time']] ;
		// echo $data['cid'].'R'.$data['rid']. ",";
		echo "\n";
	}
	//CSV Export Log 
	setCSVExportLog($_POST['csv_pw'],$filename);
}

?>
