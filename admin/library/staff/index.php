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

$table = "staff";

// データの仮削除------------------------------------------------------------------------------
if( $_REQUEST['action'] == "delete" && $_REQUEST['id'] >= 1 ){
	$sql = "UPDATE ".$table." SET del_flg = 1";
	$sql .= " WHERE id = '".addslashes($_REQUEST['id'])."'";
	$dRes = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);
	if( $dRes )	$gMsg = 'データの削除が完了しました。';
	else		$gMsg = '何も削除しませんでした。';
}

// 表示ページ設定------------------------------------------------------------------------------
$dStart = 0;
$dLine_Max = $_POST['line_max']= $_POST['line_max'] ? $_POST['line_max'] : 50;
if( is_numeric( $_POST['start'] ) && $_POST['start'] >= 0 && $_POST['start'] < 99999 ){
	$dStart = $_POST['start'];
}

// 検索条件の設定------------------------------------------------------------------------------
$dWhere = "";
if( $_POST['keyword'] != "" ){
	$dWhere = " and ( ";

	$dWhere .= "  replace(name, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(name_kana, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	
	$dWhere .= " ) ";
}
if( $_POST['status'] ) $dWhere .= " AND status = '".addslashes($_POST['status'])."'";
if($_POST['shop_id'])$dWhere .= " AND shop_id='".$_POST['shop_id'] ."'";
// if($_POST['type'])$dWhere .= " AND type='".$_POST['type'] ."'";
if($_POST['employment_type'])$dWhere .= " AND employment_type='".$_POST['employment_type'] ."'";
if($_POST['job_type'])$dWhere .= " AND job_type='".$_POST['job_type'] ."'";
if($_POST['position'])$dWhere .= " AND position='".$_POST['position'] ."'";
if($_POST['class'])$dWhere .= " AND class='".$_POST['class'] ."'";
if($_POST['new_face'])$dWhere .= " AND new_face='".$_POST['new_face'] ."'";
if($_POST['end_day'])$dWhere .= " AND end_day='".$_POST['end_day'] ."'";
// 名前に「店」「センター」「本社」が入っていたら除外する
$dWhere .= "AND name NOT LIKE('%店%')";
$dWhere .= "AND name NOT LIKE('%センター%')";
$dWhere .= "AND name NOT LIKE('%本社%')";

// データの取得------------------------------------------------------------------------------
$dSql = "SELECT count(*) FROM ".$table. " WHERE del_flg = 0 and code<>'' ";
$dRtn1 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dAll_Cnt = $dRtn1->fetch_row();

$dSql = "SELECT count(id) FROM " . $table . " WHERE del_flg = 0 and code<>'' ".$dWhere;
$dRtn2 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dGet_Cnt = $dRtn2->fetch_row()[0];

// ソート項目を設定する--------------------------------------------------------------------------
if ($_GET['s'] == "") {
    //パラメータsが指定されなかった場合は、1とする
    $_GET['s'] = 1;
}
switch ($_GET['s'] % 20) {
    case 1:
        $sort = "id";
        break;
    case 2:
        $sort = "name";
        break;
    case 3:
        $sort = "begin_day";
        break;
    case 4:
        $sort = "shop_id";
        break;
    case 5:
        $sort = "employment_type";
        break;
    case 6:
        $sort = "job_type";
        break;
    case 7:
        $sort = "position";
        break;
    case 8:
        $sort = "class";
        break;
    case 9:
        $sort = "new_face";
        break;
    case 10:
        $sort = "end_day";
        break;
    case 11:
        $sort = "status";
        break;
    case 12:
        $sort = "total_stars";
        break;
    case 13:
        $sort = "active_stars";
        break;
}
if ($_GET['s'] < 20) {
    $base = 20;
    $sortby = 'DESC'; //昇順
} else {
    $base = 0;
    $sortby = 'ASC'; //降順
}

$dSql = "SELECT * FROM " . $table . " WHERE del_flg = 0 and code<>'' ".$dWhere." ORDER BY ".$sort." ".$sortby." LIMIT ".$dStart.",".$dLine_Max;
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//店舗リスト------------------------------------------------------------------------------
$shop_list = getDatalist4("shop");

?>