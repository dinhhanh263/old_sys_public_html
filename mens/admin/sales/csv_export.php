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

$table = "sales";

//店舗リスト------------------------------------------------------------------------

$shop_list = getDatalist_shop();
// $mensdb = changedb();

//courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 ORDER BY id" );
while ( $result2 = $course_sql->fetch_assoc() ) {
	$course_list[$result2['id']] = $result2['name'];
	$course_type[$result2['id']] = $result2['type'];
}


//------------------------------------------------------------------------------------

$_POST['pay_date']=$_POST['pay_date'] ? $_POST['pay_date'] : date("Y-m-d");
$_POST['pay_date2']=$_POST['pay_date2'] ? $_POST['pay_date2'] : ($_POST['pay_date'] ? $_POST['pay_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['pay_date']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['pay_date']." +1day"));

// 検索条件の設定-------------------------------------------------------------------

$dWhere = "";
if( $_POST['keyword'] != "" ){
	$dWhere .= " AND ( ";
	$dWhere .= "  replace(customer.name, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(customer.name_kana, '　', '') LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or customer.no LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(customer.tel, '-', '') LIKE '%".addslashes( str_replace("-","",$_POST['keyword']) )."%'";
	$dWhere .= " or customer.mail LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " ) ";
}
$dWhere .= " AND  sales.pay_date>='".$_POST['pay_date']."'";
$dWhere .= " AND  sales.pay_date<='".$_POST['pay_date2']."'";
if($_POST['shop_id']) $dWhere .= " AND  sales.shop_id='".$_POST['shop_id'] ."'";
if( $_POST['status'] ) $dWhere .= " AND sales.status = '".addslashes($_POST['status'])."'";

// 月額コースID取得
// $month_id = implodeArray("course","id"," where del_flg=0 and type=1");
// if( $_POST['type']==20 ) $dWhere .= " AND sales.type =5 AND sales.course_id in(".$month_id." )";
// elseif( $_POST['type']==5 ) $dWhere .= "  AND sales.type =5 AND sales.course_id not in(".$month_id." )";
// elseif( $_POST['type'] ) $dWhere .= " AND sales.type = '".addslashes($_POST['type'])."'";

// 区分検索条件 ※売掛回収はtype=2,27どちらも表示する
if( $_POST['type'] ==7 || $_POST['type'] ==27 ){ 
	$dWhere .= " AND sales.type in(7,27)"; // 2.売掛回収 OR 27.トリートメント/売掛回収
} elseif($_POST['type']) {
	$dWhere .= " AND sales.type = '".addslashes($_POST['type'])."'"; // それ以外の区分
}
// オプション名
if( $_POST['option_name'] ) $dWhere .= " AND sales.option_name = '".addslashes($_POST['option_name'])."'";
// コースID
// if( $_POST['course_id'] ) $dWhere .= " AND sales.course_id = '".addslashes($_POST['course_id'])."'";
if( $_POST['course_id'] ) $dWhere .= " AND FIND_IN_SET (".addslashes($_POST['course_id']).", sales.multiple_course_id )"; // 複数コースIDは選んだコースを含むコースが表示される


// データの取得----------------------------------------------------------------------

$dSql  = "SELECT sales.*,customer.no as no,customer.name as name,customer.name_kana as name_kana,customer.tel as tel ";
$dSql .= "FROM " . $table . ",customer WHERE sales.customer_id=customer.id AND customer.del_flg=0 AND sales.del_flg = 0".$dWhere;
$dSql .= " ORDER BY sales.pay_date,sales.reg_date ";//sales.type desc:プラン変更がの残金
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql );

//csv export----------------------------------------------------------------------

$filename = "sales_list.csv"; 
header("Content-type:text/csv"); 
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("会員番号,名前,名前カナ,区分,店舗,日付,コース,コース2,コース3,カスタマイズ,請求金額,オプション名,オプション金額,現金入金,カード入金,銀行振込,ローン,売掛金\n","SJIS-win", "UTF-8");
	while ( $data = $dRtn3->fetch_assoc() ) {
		//役務消化除外
		if($data['r_times'] && !$data['payment_cash'] && !$data['payment_card'] && !$data['payment_transfer'] && !$data['payment_loan'] && !$data['payment_coupon'] && !$data['option_price'] && !$data['option_price'] && !$data['option_transfer'] && !$data['option_card'] ) continue; 	
		$loan_status = Get_Table_Col("contract","loan_status"," where del_flg=0 and id=".$data['contract_id']);
		
		// ローン取消・ローン非承認の場合、売掛金を0円で計算する
		// if( $data['type']==9) $data['balance']=0;		
		if( $data['type']==9 || $data['type']==15 ) $data['balance']=0;

		echo $data['no']  . ",";
		echo mb_convert_encoding($data['name'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['name_kana'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($course_type[$data['course_id']] ? $gResType6[$data['type']] : $gResType3[$data['type']],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($shop_list[$data['shop_id']],"SJIS-win", "UTF-8")  . ",";
		echo $data['pay_date'] . ","; 

		// echo mb_convert_encoding($course_list[$data['course_id']],"SJIS-win", "UTF-8")  . ",";
		// 1つのコース/複数コース表示出しわけ
		$course_diff_num = 0; // コース差分数(最大4つまでとして、差分がいくつあるのか)
		if(is_numeric($data['multiple_course_id'])){
			echo mb_convert_encoding($course_list[$data['multiple_course_id']],"SJIS-win", "UTF-8");
			echo ",,,,";
		} else {
			// 複数コースIDがあるときは分解した配列を作り、各コース名を表示する
			$multiple_course = explode(',', $data['multiple_course_id']);
			$course_diff_num = 4- count($multiple_course); // コース差分数
			foreach ($multiple_course as $key => $value) {
				$value = mb_convert_encoding($value,"SJIS-win", "UTF-8");
				echo mb_convert_encoding($course_list[$value],"SJIS-win", "UTF-8");
				echo ",";
			}
		}
		// コース差分数があったら数分列を増やす
		if($course_diff_num<>0)echo str_repeat(",", $course_diff_num);

		echo ( ( ($data['type']==4 || $data['type']==5 || $data['type']==9 || $data['type']==12)) ? 0 : $data['price'] ) . ",";
		echo mb_convert_encoding($gOption2[$data['option_name']],"SJIS-win", "UTF-8")  . ",";
		echo ($data['option_price'] + $data['option_transfer'] + $data['option_card']). ",";
		echo ($data['payment_cash'] + $data['option_price']) . ",";
		echo ($data['payment_card'] + $data['option_card']) . ",";
		echo ($data['payment_transfer']+ $data['option_transfer']) . ",";
		echo $data['payment_loan'] . ","; 
		echo $data['balance'] . ","; 
		echo "\n";
	}
	//CSV Export Log 
	setCSVExportLog($_POST['csv_pw'],$filename);
}
?>
