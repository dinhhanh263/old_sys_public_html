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
define_approot($_SERVER['SCRIPT_FILENAME'],2);
require_once LIB_DIR . 'db.php';
include_once( "../../lib/auth3.php" );
include_once( "../library/menu.php" );

$_POST['mode'] = $_POST['mode'] ? $_POST['mode'] : "daily";
//テーブル設定
$table = "accesslog";

//page_id取得
$_POST['page_id'] = ( $_POST['page_id'] || $_POST['page_id'] == "-" ) ? $_POST['page_id'] : 1 ;//ダブル集計のため、"1"は全てのトップページである

//代理店リスト
$agent = $GLOBALS['mysqldb']->query( "SELECT * FROM agent WHERE pid='' ORDER BY name" );
while ( $list = $agent->fetch_assoc() ) {
	$gAgent[$list['id']] = $list['name'];
}

// 期間指定,前半1-15、後半16-末
if($_POST['limit_year1'] != "" && $_POST['limit_month1'] != "" && $_POST['limit_day1'] != "" && $_POST['limit_year2'] != "" && $_POST['limit_month2'] != "" && $_POST['limit_day2'] != ""){
	$limit1_year = $_POST['limit_year1']; $limit1_month = $_POST['limit_month1']; $limit1_day = $_POST['limit_day1'];
	$limit2_year = $_POST['limit_year2']; $limit2_month = $_POST['limit_month2']; $limit2_day = $_POST['limit_day2'];
}else{
	$limit1_year = date("Y"); $limit1_month = date("m"); $limit1_day = '01';
  	//$limit2_year = date("Y"); $limit2_month = (date("m") + 1); $limit2_day = '0';
  	$limit2_year  = date("Y",mktime(0, 0, 0, date("m") +1 , date("d"), date("Y")));
	$limit2_month = date("m",mktime(0, 0, 0, date("m") +1 , date("d"), date("Y")));
	$limit2_day = '0';
}
//月前半指定
if($_POST['m_half'] == "1"){
	$limit1_day = '01';
	$limit2_day = '15';
	$limit2_month = $limit1_month;
	$select1 = " selected";
}
//月後半指定
if($_POST['m_half'] == "2"){
	$limit1_day = '16';
	$limit2_month = date("m",mktime(0, 0, 0, $limit1_month +1 , $limit1_day, $limit1_year));
	$limit2_day = '0';
	$limit2_year = $limit1_month==12 ? $limit1_year+1 : $limit1_year;
	$select2 = " selected";
}
//全月指定
if($_POST['m_half'] == "3"){
	$limit1_day = '01';
	$limit2_month = date("m",mktime(0, 0, 0, $limit1_month +1 , $limit1_day, $limit1_year));
	$limit2_day = '0';
	$limit2_year = $limit1_month==12 ? $limit1_year+1 : $limit1_year;
	$select3 = " selected";
}

//月別指定
if($_POST['mode']=="monthly"){
	$limit1_day = '01';
	if($limit2_day>=28) $limit2_day2 = $limit2_day -1 ; //月末の場合、翌月になってしまう問題を回避
	else $limit2_day2 = $limit2_day +1 ; //月初の場合、先月になってしまう問題を回避
	$limit2_month = date("m",mktime(0, 0, 0, $limit2_month +1 , $limit2_day2, $limit2_year));
	$limit2_day = '0';
	$limit2_year = $limit1_month==12 ? $limit1_year+1 : $limit1_year;
}
$limit1 = $limit1_year.'-'.$limit1_month.'-'.$limit1_day;
$limit2 = $limit2_year.'-'.$limit2_month.'-'.$limit2_day;

$max = 0;//グラフの母数が合計から最大値に変更

//export data by adcode
if($_POST['mode']=="byad"){
	
// 検索条件の設定
	$dWhere = "where id!=0 ";
	if($_POST['agent_id'] != "" ){
		$dWhere .= " and agent_id = '".$GLOBALS['mysqldb']->real_escape_string($_POST['agent_id'])."'";
	}
	if($_POST['type'] != "" ){
		$dWhere .= " and type = '".$GLOBALS['mysqldb']->real_escape_string($_POST['type'])."'";
	}

	// データの取得
	$adcode = $GLOBALS['mysqldb']->query( "SELECT * FROM adcode ".$dWhere." order by agent_id" );
	$data = array();
	$total = array();
	$i=0;
	while ( $list = $adcode->fetch_assoc() ) {
		$data[$i]['id'] = $list['id'];
		$data[$i]['release_date'] = $list['release_date'];
		$data[$i]['adcode'] = $list['adcode']; 
		$data[$i]['type'] = $list['type'];
		$data[$i]['name'] = $list['name'];
		$data[$i]['del_flg'] = $list['del_flg'];
		$data[$i]['del_date'] = $list['del_date'];
		$data[$i]['net_cost'] = $list['cost1'];
		$data[$i]['gross_cost'] = $list['cost'];
		$data[$i]['maximum'] = $list['maximum'];
	
		//代理店名取得
		$aAgent = Get_Table_Row("agent"," WHERE id = '".addslashes($list['agent_id'])."'");
		$data[$i]['agent'] = $aAgent['name'];
	
		//端末別ページクリック数取得
		$data[$i]['total_all'] = 0;			
		foreach($moStatus as $key=>$val){
			// base: adcode -> id
			$sql = "SELECT sum(count) as cnt FROM ".$table." WHERE page_id=".$_POST['page_id']." and mo_agent=".$key." and adcode='".$list['id']."' and access_date >= '".$limit1."' AND access_date <= '".$limit2."' GROUP BY adcode";
			//var_dump($sql);
			$rtn = $GLOBALS['mysqldb']->query($sql);
			if($rtn->num_rows >= 1){
				while($line = $rtn->fetch_assoc()){
					$data[$i][$val] = $line['cnt'];
					$data[$i]['total_all'] += $line['cnt'];
					$total[$val] += $line['cnt'];
					$total['total_all'] += $line['cnt'];
				}
			}
		}
		//最大値取得
		if($data[$i]['total_all'] > $max) $max = $data[$i]['total_all'];
		$i++;
	}

}

//export data by daily
if($_POST['mode']=="daily"){
	// 検索条件の設定
	if($_POST['agent_id'] != "" ){
		$dWhere = " and adcode.agent_id = '".$GLOBALS['mysqldb']->real_escape_string($_POST['agent_id'])."'";
	}
	if($_POST['type'] != "" ){
		$dWhere .= " and adcode.type = '".$GLOBALS['mysqldb']->real_escape_string($_POST['type'])."'";
	}
	//端末別ページクリック数取得
	foreach($moStatus as $key=>$val){
		// base: adcode -> id
		$sql  = "SELECT accesslog.access_date as date ,sum(accesslog.count) as cnt ,adcode.id as adcode ,adcode.type as type ";
		$sql .= "FROM accesslog,adcode ";
		// base: adcode -> id,adcodeなしのは除外
		$sql .= "WHERE accesslog.page_id=".$_POST['page_id']." and accesslog.mo_agent=".$key." and accesslog.adcode=adcode.id and accesslog.access_date >= '".$limit1."' AND accesslog.access_date <= '".$limit2."' ";
		
		$sql .= $dWhere;
		$sql .= " GROUP BY accesslog.access_date ORDER BY accesslog.access_date";

		$rtn = $GLOBALS['mysqldb']->query($sql);
		if($rtn->num_rows >= 1){
			while($line = $rtn->fetch_assoc()){
				$data [$line['date']]['date'] = $line['date'];
				$data[$line['date']][$val] = $line['cnt']; //日付別+端末別集計数
				$data[$line['date']]['total_all'] += $line['cnt']; //全端末日付別集計数
				$total[$val] += $line['cnt'];
				$total['total_all'] += $line['cnt'];

				//最大値取得
				if($data[$line['date']]['total_all'] > $max) $max = $data[$line['date']]['total_all'];
			}
			$if_data = true;
		}
	}
	//日付順に再配列
	if($if_data) ksort($data);
}
//export data by monthly
if($_POST['mode']=="monthly"){
	// 検索条件の設定
	if($_POST['agent_id'] != "" ){
		$dWhere = " and adcode.agent_id = '".$GLOBALS['mysqldb']->real_escape_string($_POST['agent_id'])."'";
	}
	if($_POST['type'] != "" ){
		$dWhere .= " and adcode.type = '".$GLOBALS['mysqldb']->real_escape_string($_POST['type'])."'";
	}
	//端末別ページクリック数取得
	foreach($moStatus as $key=>$val){
		// base: adcode -> id
		$sql  = "SELECT DATE_FORMAT(accesslog.access_date,'%Y-%m') as date ,sum(accesslog.count) as cnt ,adcode.id as adcode ,adcode.type as type ";
		$sql .= "FROM accesslog,adcode ";
		// base: adcode -> id
		$sql .= "WHERE accesslog.page_id=".$_POST['page_id']." and accesslog.mo_agent=".$key." and accesslog.adcode=adcode.id and accesslog.access_date >= '".$limit1."' AND accesslog.access_date <= '".$limit2."' ";
		
		$sql .= $dWhere;
		$sql .= " GROUP BY DATE_FORMAT(accesslog.access_date,'%Y%m') ORDER BY accesslog.access_date";

		$rtn = $GLOBALS['mysqldb']->query($sql);
		if($rtn->num_rows >= 1){
			while($line = $rtn->fetch_assoc()){
				$data [$line['date']]['date'] = $line['date'];
				$data[$line['date']][$val] = $line['cnt']; //日付別+端末別集計数
				$data[$line['date']]['total_all'] += $line['cnt']; //全端末日付別集計数
				$total[$val] += $line['cnt'];
				$total['total_all'] += $line['cnt'];

				//最大値取得
				if($data[$line['date']]['total_all'] > $max) $max = $data[$line['date']]['total_all'];
			}
			$if_data = true;
		}
	}
	//日付順に再配列
	if($if_data) ksort($data);
}
//export data by page
if($_POST['mode']=="bypage"){
	$_POST['page_id'] = "";
	// 検索条件の設定
	if($_POST['agent_id'] != "" ){
		$dWhere = " and adcode.agent_id = '".$GLOBALS['mysqldb']->real_escape_string($_POST['agent_id'])."'";
	}
	if($_POST['type'] != "" ){
		$dWhere .= " and adcode.type = '".$GLOBALS['mysqldb']->real_escape_string($_POST['type'])."'";
	}
	
	foreach($gPage_id as $page_id =>$page_name){
		if($page_id ==1)continue;//トップページを外す
		
		//端末別ページクリック数取得
		foreach($moStatus as $key=>$val){
			// base: adcode -> id
			$sql  = "SELECT accesslog.access_date as date ,sum(accesslog.count) as cnt ,adcode.id as adcode ,adcode.type as type ";
			$sql .= "FROM accesslog,adcode ";
			// base: adcode -> id
			$sql .= "WHERE accesslog.page_id=".$page_id." and accesslog.mo_agent=".$key." and accesslog.adcode=adcode.id and accesslog.access_date >= '".$limit1."' AND accesslog.access_date <= '".$limit2."' ";
			
			$sql .= $dWhere;
			$sql .= " GROUP BY accesslog.page_id ORDER BY accesslog.page_id";

			$rtn = $GLOBALS['mysqldb']->query($sql);
			if($rtn->num_rows >= 1){
				while($line = $rtn->fetch_assoc()){
					$data [$page_name]['date'] = $page_name;
					$data[$page_name][$val] = $line['cnt'];
					$data[$page_name]['total_all'] += $line['cnt'];
					$total[$val] += $line['cnt'];
					$total['total_all'] += $line['cnt'];

					//最大値取得
					if($data[$page_name]['total_all'] > $max) $max = $data[$page_name]['total_all'];
				}
			}
		}
	}
}
?>