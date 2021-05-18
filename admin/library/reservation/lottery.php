<?php
if(empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
}else $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];

require_once $DOC_ROOT . '/config/config.php';
require_once $DOC_ROOT . '/lib/function.php';
require_once $DOC_ROOT . '/lib/db.php';
require_once $DOC_ROOT . '/lib/auth.php';

// 表示期間------------------------------------------------------------------------
$post_date1 = $_POST['date1'];
$post_date2 = $_POST['date2'];

$date1 = $post_date1 ? $post_date1 : ($post_date2 ? $post_date2 : "2014-02-28");
$date2 = $post_date2 ? $post_date2 : ($post_date1 ? $post_date1 : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($date2." -1day"));
$next_date = date("Y-m-d", strtotime($date2." +1day"));

// 表示ページ設定------------------------------------------------------------------------
$dStart = 0;
$dLine_Max = $_POST['line_max']= $_POST['line_max'] ? $_POST['line_max'] : 50;
if( is_numeric( $_POST['start'] ) && $_POST['start'] >= 0 && $_POST['start'] < 99999 ){
	$dStart = $_POST['start'];
}

// 検索条件の設定------------------------------------------------------------------------
$dWhere = "";
if( $_POST['keyword'] != "" ){
	$dWhere .= " AND ( ";
	$dWhere .= " REPLACE(c.name, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " OR REPLACE(c.name_kana, '　', '') LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " OR c.no LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " OR REPLACE(c.tel, '-', '') LIKE '%".addslashes( str_replace("-","",$_POST['keyword']) )."%'";
	$dWhere .= " OR c.mail LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " ) ";
}

$dWhere .= " AND SUBSTRING(c.reg_date,1,10)>='".$date1."' AND SUBSTRING(c.reg_date,1,10)<='".$date2."'";
if($_POST['shop_id']) $dWhere .= " AND c.shop_id='".$_POST['shop_id'] ."'";
if($_POST['prize']) $dWhere .= " AND r.prize='".$_POST['prize'] ."'";
if($_POST['adcode']) $dWhere .= " AND a.id='".$_POST['adcode'] ."'";

// データの取得------------------------------------------------------------------------
$dSql = "SELECT count(r.id) FROM reservation r,customer c LEFT JOIN adcode a ON a.id=c.adcode
		 WHERE c.id=r.customer_id AND c.del_flg=0 AND r.del_flg=0  AND (r.prize>0 OR c.adcode<>'' AND a.memo<>'' AND a.memo like '%当選。%')
		".$dWhere." ORDER BY r.reg_date";

$dRtn2 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dGet_Cnt = $dRtn2->fetch_row()[0];

$dSql = "SELECT c.id id, c.no '会員番号',c.name '名前',c.name_kana '名前（カナ）',
CASE
WHEN h.name is NULL THEN ''
ELSE h.name
END '店舗',
CASE
WHEN p.name is NULL THEN ''
ELSE p.name
END '当選賞品',
CASE
WHEN r.hope_date is NULL THEN ''
ELSE r.hope_date
END '店舗抽選日',
CASE
WHEN a.adcode is NULL THEN ''
ELSE a.adcode
END 'アドコード',
CASE
WHEN a.name is NULL THEN ''
ELSE a.name
END '媒体名',
CASE
WHEN u.name is NULL THEN ''
ELSE u.name
END '契約コース',
CASE
WHEN t.id is NULL THEN ''
ELSE (t.fixed_price-t.discount) 
END '契約金額',
CASE
WHEN t.contract_date is NULL THEN ''
ELSE t.contract_date
END '契約日',
CASE
WHEN k.id is NULL THEN ''
ELSE min(k.first_date)
END '最初アクセス日時',
CASE
WHEN k.id is NULL THEN ''
ELSE max(k.edit_date)
END '最終アクセス日時',
CASE
WHEN k.id is NULL THEN ''
ELSE k.cnt
END 'アクセス回数',
CASE
WHEN c.rebook_flg=0 THEN '直接申込'
WHEN c.rebook_flg=1 THEN '再申込（一般）'
WHEN c.rebook_flg=2 THEN '再申込（梅木）'
END '直接申込',
c.reg_date '登録日時', c.referer_url '参照元'
FROM customer c
LEFT JOIN item_prize p ON p.id=c.prize
LEFT JOIN adcode a ON a.id=c.adcode
LEFT JOIN reservation r ON c.id= r.customer_id AND r.prize>0
LEFT JOIN contract t ON c.id=t.customer_id AND t.status=0
LEFT JOIN course u ON t.course_id=u.id
LEFT JOIN shop h ON r.shop_id=h.id
LEFT JOIN k_cookie k ON c.id= k.customer_id
WHERE c.del_flg=0
AND (r.prize>0 OR c.adcode<>'' AND a.memo<>'' AND a.memo like '%当選。%')
".$dWhere." GROUP BY c.id ORDER BY r.reg_date DESC LIMIT ".$dStart.",".$dLine_Max;

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

// リスト------------------------------------------------------------------------
$shop_list = getDatalist("shop");
$shop_lists = getDatalistArray3("shop","area", $gShops_priority);
$course_list = getDatalist("course");
$prize_list = getDatalist4("item_prize","当選賞品");
$adcode_list = getDatalistWhere("adcode","id","name","くじ媒体"," AND memo<>'' AND memo like '%当選。%' ");

$param = '&keyword='.$_POST['keyword'].
		 '&shop_id='.$_POST['shop_id'].
		 '&prize='.$_POST['prize'].
		 '&adcode='.$_POST['adcode'].
		 '&line_max='.$_POST['line_max'].
		 '&start='.$dStart;
