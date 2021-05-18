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

if(!$_POST['shop_id']) $_POST['shop_id'] = 1;
$table = "shift";


$shift_month = $_POST['hope_date'] ? substr($_POST['hope_date'],0,7) : date("Y-m");
$current_day = date("j",strtotime($_POST['hope_date'])); //月:先頭にゼロをつけない。
$selected_field = "day".$current_day;

// 検索条件の設定-------------------------------------------------------------------
$dWhere =" WHERE del_flg=0 ";
if($_POST['shop_id']) $dWhere .= " AND shop_id='".$_POST['shop_id']."'";
if($current_day) $dWhere .= " AND day".$current_day." in(1,2,3)";
if($shift_month) $dWhere .= " AND shift_month='".$shift_month."'";

// データの取得----------------------------------------------------------------------
$dSql = "SELECT * FROM " . $table . $dWhere." ORDER BY day".$current_day.",staff_id ";
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql );

//staffリスト------------------------------------------------------------------------
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 ORDER BY id" );
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

if ( $dRtn3->num_rows >= 1 ) {
	while ( $data = $dRtn3->fetch_assoc() ) {
		$html .= "<tr><td>".$staff_list[$data['staff_id']] ."</td><td> : ". $gShiftType[$data[$selected_field]]."</td></tr>";
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>KIREIMO SYSTEM</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<style type="text/css"> 
#content0 {
color: #333;
font-family: Arial, Helvetica, sans-serif,"メイリオ";
font-size: 12px;
line-height: 18px;
margin: 0 auto 0 auto;

padding: 20px 0px 10px 140px;
}
</style>
</head>
<body>
<div id="content0">
	<table>
		<?php echo $html;?>
	</table>
</div>
</body>
</html>