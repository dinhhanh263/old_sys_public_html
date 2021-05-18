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
if(!$_POST['hope_date']) $_POST['hope_date'] = date("Y-m-d");
$table = "shift";


$shift_month = $_POST['hope_date'] ? substr($_POST['hope_date'],0,7) : date("Y-m");
$current_day = date("j",strtotime($_POST['hope_date'])); //月:先頭にゼロをつけない。
$selected_field = "day".$current_day;

// 検索条件の設定-------------------------------------------------------------------
$dWhere =" WHERE del_flg=0 ";
if($_POST['shop_id']) $dWhere .= " AND shop_id='".$_POST['shop_id']."'";
if($current_day) $dWhere .= " AND day".$current_day." in(1,2,3,6,8,9,10,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,51,52,53,54,56,101,102,103,104,105,106)";
if($shift_month) $dWhere .= " AND shift_month='".$shift_month."'";

// データの取得----------------------------------------------------------------------
$dSql = "SELECT * FROM " . $table . $dWhere." ORDER BY day".$current_day.",staff_id ";
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql );
//var_dump($dSql);
//リスト------------------------------------------------------------------------
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 ORDER BY id" );
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

$sql = $GLOBALS['mysqldb']->query( "select id,new_face,type from staff WHERE del_flg = 0  AND status=2 " );
if($sql){
	while ( $result = $sql->fetch_assoc() ) {
		$staff_staus[$result['id']] = $result['new_face'];
		$staff_type[$result['id']] = $result['type'];
	}
}


if ( $dRtn3->num_rows >= 1 ) {
	$i = 0;
	while ( $data = $dRtn3->fetch_assoc() ) {
		if($gShiftType[$data[$selected_field]]=="欠" || strpos($gShiftType[$data[$selected_field]],"A") || strpos($gShiftType[$data[$selected_field]],"B")|| strpos($gShiftType[$data[$selected_field]],"C") || strpos($gShiftType[$data[$selected_field]],"D") || strpos($gShiftType[$data[$selected_field]],"F") || strpos($gShiftType[$data[$selected_field]],"G") || strpos($gShiftType[$data[$selected_field]],"H")|| strpos($gShiftType[$data[$selected_field]],"I") || strpos($gShiftType[$data[$selected_field]],"J") || strpos($gShiftType[$data[$selected_field]],"K") || strpos($gShiftType[$data[$selected_field]],"L") ){
			$color ="red";
			if($gShiftType[$data[$selected_field]]<>"欠")$i++;
		}elseif($staff_type[$data['staff_id']]==19 || $data['treat_only']){
			$color ="purple";
		}elseif($staff_type[$data['staff_id']]==21){
			$color ="gray";
		}elseif($staff_staus[$data['staff_id']] || $staff_type[$data['staff_id']]==18){
			$color ="blue";
		}else{
			$color ="";
			$i++;
		}

		$html .="<font color=".$color.">".$staff_list[$data['staff_id']] ."(". $gShiftType[$data[$selected_field]].")</font>   &nbsp; ";
		
	}
	if($i) $html .= "(".$i."人)";
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
font-size: 11px;
margin: 0 auto 0 auto;

}
#td_name {
	padding-left:10px;
}
</style>
</head>
<body>
<div id="content0">

		<?php echo $html;?>

</div>
</body>
</html>