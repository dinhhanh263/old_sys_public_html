<?php
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/mens/config/config.php';
require_once LIB_DIR . 'function.php';
require_once LIB_DIR . 'db.php';
include_once( "../../lib/auth.php" );

// 1回コースの表示------------------------------------------------------------------------
$table = "course";

// 検索条件の設定------------------------------------------------------------------------
$dWhere = "";
if( $_POST['keyword'] != "" ){
	$dWhere .= " AND ( ";
	$dWhere .= "  replace(name, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or id LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " ) ";
}

// データの取得------------------------------------------------------------------------
$dSql = "SELECT * FROM " . $table . " WHERE  old_flg=0 AND one_flg=1 AND part_length<>0 AND del_flg=0 and id<>49".$dWhere. " ORDER BY name";
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql );

// 店舗リスト------------------------------------------------------------------------
$shop_list = getDatalist_shop();
// $mensdb = changedb();

//courseリスト
$course_list  = getDatalistMens("course");



// カスタマイズ部位の表示------------------------------------------------------------------------
$table2 = "part";

// 検索条件の設定------------------------------------------------------------------------
$dWhere2 = "";
if( $_POST['keyword'] != "" ){
  $dWhere2 .= " AND ( ";
  $dWhere2 .= "  replace(name, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
  $dWhere2 .= " or id LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
  $dWhere2 .= " ) ";
}

// データの取得------------------------------------------------------------------------
$dSql2 = "SELECT * FROM " . $table2 . " WHERE  old_flg=0 AND one_flg=1 AND part_length<>0 AND del_flg=0".$dWhere2. " ORDER BY id";
$dRtn32 = $GLOBALS['mysqldb']->query( $dSql2 );

//partsリスト
$parts_list  = getDatalistMens("part");

?>
