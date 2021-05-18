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

$table = "shift";

$_POST['shift_month']=$_POST['shift_month'] ? substr($_POST['shift_month'],0,7) : date("Y-m");
$pre_month = date("Y-m", strtotime($_POST['shift_month']." -1 month"));
$next_month = date("Y-m", strtotime($_POST['shift_month']." +1 month"));
/*
$shift_month = $_POST['shift_date'] ? substr($_POST['shift_date'],0,7) : date("Y-m");
$current_day = $_POST['shift_date'] ? date("j",strtotime($_POST['shift_date'])) : date("j"); //月:先頭にゼロをつけない。
$selected_day = "day".$current_day;*/

// 検索条件の設定-------------------------------------------------------------------
$dWhere =" WHERE del_flg=0 ";
if($_POST['shop_id']) $dWhere .= " AND shop_id='".$_POST['shop_id']."'";
else $dWhere .= " AND shop_id<>1001";//本社除く
//if($current_day) $dWhere .= " AND day".$current_day." in(1,2,3)";
if($_POST['shift_month']) $dWhere .= " AND shift_month='".$_POST['shift_month']."'";

// データの取得----------------------------------------------------------------------
$dSql = "SELECT * FROM " . $table . $dWhere." ORDER BY staff_id ";
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql );
//var_dump($dSql);exit;

//エクスポートファイル名用
$shit_list_date = $_POST['shift_month'];
if($_POST['shop_id'] == '') {
$shit_list_shop = "shift_list_ALL";
}else{
$shit_list_shop = "shift_list";
}

//csv export----------------------------------------------------------------------
$filename = "".$shit_list_date."_".$shit_list_shop.".csv";
header("Content-type:text/csv"); 
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	while ( $data = $dRtn3->fetch_assoc() ) {
		for($i=1; $i<=31; $i++){
			$selected_day = "day".$i;
			$selected_date = str_replace("-", "/", $_POST['shift_month'])."/".($i<10 ? "0" : "").$i;
			$staff = Get_Table_Row("staff"," WHERE del_flg=0 and id = '".addslashes($data['staff_id'])."'");

			if($data[$selected_day] && $staff['code']){
			
				/* 古い仕様で特別処理が要らない。　edit by ka  20170904
				//契約社員、早番
				if($staff['type']==22 && ($data[$selected_day]==2 || $data[$selected_day]==13 || $data[$selected_day]==14 )) $pattern_code = "008";
				//契約社員、中番
				elseif($staff['type']==22 && ($data[$selected_day]==10 || $data[$selected_day]==15 || $data[$selected_day]==16 )) $pattern_code = "005";
				//契約社員、遅番
				elseif($staff['type']==22 && ($data[$selected_day]==3 || $data[$selected_day]==17 || $data[$selected_day]==18 )) $pattern_code = "016";
				*/
				//本社所属が店舗勤務の場合、早番,1001が本社勤務
				if($data[$selected_day]>10000 && $data['shop_id']=1001) $pattern_code = $gShiftCode['2'];
				else $pattern_code = $gShiftCode[$data[$selected_day]];

				
				//$pattern_code = $gShiftCode[$data[$selected_day]];
				if($staff['code']<>0 && $staff['type']!=24){
					echo View_Cook( $selected_date ) . ","; 		// 勤務日
					echo View_Cook( $staff['code'] ) . ",";			// 従業員コード
					echo $pattern_code . "\n";						// スケジュールパターンコード
					//echo $shop_code[$data['shop_id']] . "\n";		// 出勤所属コード　不要になったためにコメントアウト
				}
			}
		}
	}
}
?>
