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

if(!$_POST['shop_id']) $_POST['shop_id'] = 1;
if(!$_POST['hope_date']) $_POST['hope_date'] = date("Y-m-d");
$table = "shift";


$shift_month = $_POST['hope_date'] ? substr($_POST['hope_date'],0,7) : date("Y-m");
$current_day = date("j",strtotime($_POST['hope_date'])); //月:先頭にゼロをつけない。
$selected_field = "day".$current_day;

// 検索条件の設定-------------------------------------------------------------------
$dWhere =" WHERE del_flg=0 ";
// if($current_day) $dWhere .= " AND day".$current_day." in(1,2,3,6,8,9,10,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,101,102,103,104,105,106)";
if($current_day) $dWhere .= " AND day".$current_day." NOT IN(0,4,5,7,11,12,37,38,39,40,41,56,57,58,59,60,61,62,63,64,110)";//休み除外.基本休、希望休、有休、忌引、夏季休暇(0-38、時短・アルバイト40-109)※欠勤スタッフ(6)は表示させる
if($shift_month) $dWhere .= " AND shift_month='".$shift_month."'";
if($_POST['shop_id']) $where_shop = " AND shop_id='".$_POST['shop_id']."'"; //店舗シフト用

// データの取得----------------------------------------------------------------------
$dSql1 = "SELECT * FROM " . $table . $dWhere.$where_shop." ORDER BY day".$current_day.",staff_id ";//店舗シフト取得
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql1 );
//リスト------------------------------------------------------------------------
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 ORDER BY id" );
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
	$staff_type[$result['id']] = $result['type'];
	$staff_job_type[$result['id']] = $result['job_type'];
	$staff_employment_type[$result['id']] = $result['employment_type'];
}

$sql = $GLOBALS['mysqldb']->query( "select id,new_face,type from staff WHERE del_flg = 0  AND status=2 " );
if($sql){
	while ( $result = $sql->fetch_assoc() ) {
		$staff_staus[$result['id']] = $result['new_face'];
	}
}
$where_shop = " AND shop_id='1001'"; //本社勤務
$manager_sql = "SELECT * FROM " . $table . $dWhere.$where_shop." ORDER BY day".$current_day.",staff_id ";//本社シフト取得
$dRtn4 = $GLOBALS['mysqldb']->query($manager_sql);
$i=0;
foreach($dRtn4 as $result){
	$manager_list[$i] = $staff_list[$result['staff_id']]; //本社勤務スタッフリスト
	$manager_type[$i] = $staff_type[$result['staff_id']]; //役職
	$manager_shigt_type[$i] = $result[$selected_field]; //シフト番号
	$i++;
}

if ( $dRtn3->num_rows >= 1 ) {
	$i = 0;
	while ( $data = $dRtn3->fetch_assoc() ) {
		//欠勤・遅刻・早退
		if($gShiftType[$data[$selected_field]]=="欠" || $gShiftType[$data[$selected_field]]=="刻" || $gShiftType[$data[$selected_field]]=="退"){
			$color ="red";
		// アシスタント
		}elseif($staff_job_type[$data['staff_id']]==2){
			$color ="purple";
		// 新人
		}elseif($staff_staus[$data['staff_id']]){
			$color ="blue";
		// 時短正社員
		}elseif($staff_employment_type[$data['staff_id']] >= 6 && $staff_employment_type[$data['staff_id']] <= 13 ){
			$color ="green";
		// アルバイト＆派遣事務
		}elseif($staff_employment_type[$data['staff_id']]==2 || $staff_employment_type[$data['staff_id']]==3){
			$color ="#3ABDDE";
		// 受付事務
		}elseif($staff_type[$data['staff_id']]==18){
			$color ="#00BFFF";
		}else{
			$color ="";
		}

		if(!$staff_staus[$data['staff_id']] && $gShiftType[$data[$selected_field]]<>"欠") $i++;

		$html .="<font color=".$color.">".$staff_list[$data['staff_id']] ."(". $gShiftType[$data[$selected_field]].")</font>   &nbsp; ";

	}
	if($i) $html .= "(".$i."人)";
}
$m_num = count($manager_list);
for($i = 0; $i<$m_num; $i++){
	if(!!$manager_type[$i] && $manager_type[$i]<6 && $manager_shigt_type[$i] != 6){ //本社スタッフが存在する、統括店長以上、欠勤以外
		$html2.="<span class='staff_name'>".$manager_list[$i]."</span>";
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
	font-size: 11px;
	margin: 0 auto 0 auto;
}
#td_name {
	padding-left:10px;
}
.title{display: inline-block;*display: inline;*zoom: 1;}
.staff_name{display: inline-block;*display: inline;*zoom: 1;padding-right:1rem;}
</style>
</head>
<body>
<div id="content0">
	<div>
		<div class="title">出勤メンバー：</div>
		<?php echo $html;?>
	</div>
	<div>
		<div class="title">緊急連絡先（本社営業部）：</div>
		<?php echo $html2;?>
	</div>
</div>
</body>
</html>