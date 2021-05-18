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
if($_POST['tel_status']==1)$dWhere .= " AND r.preday_status <>1 AND r.today_status <>1 ";
elseif($_POST['tel_status']==2)$dWhere .= "";
else $dWhere .= " AND  r.preday_status = 0";

// データの取得------------------------------------------------------------------------

$dSql = 'SELECT r.hope_date , r.hope_time,r.reg_date , c.no , c.name_kana ,  c.tel, c.pair_name_kana , c.pair_tel , s.name,
s.id , r.id as reservation_id, c.id , r.preday_status , r.preday_cnt, r.memo4 ,r.memo3
FROM reservation AS r, customer AS c, shop AS s
WHERE r.del_flg = 0
AND r.type = 1
AND r.hope_date =  (SELECT date_format((SELECT adddate(now(), interval + 1 day)),"%Y-%m-%d"))
AND c.id = r.customer_id
AND c.ctype = 1
AND c.del_flg = 0
AND r.shop_id = s.id
AND r.today_status <>1
'.$dWhere.'
ORDER BY r.reg_date';

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);


// 店舗リスト------------------------------------------------------------------------

$shop_list = getDatalist("shop");
$shop_lists = getDatalistArray3("shop","area", $gShops_priority);

?>