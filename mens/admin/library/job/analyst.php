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


//テーブル設定----------------------------------------------------------------------------
$table = "accesslog";

//媒体表示----------------------------------------------------------------------------
if(!$_POST )$_POST['hide_flg'] =  0 ;
$gHideFlag = array( 0 => "掲載中" , 1 => "停止済", 2 => "全て" );

//代理店リスト----------------------------------------------------------------------------
$agent = $GLOBALS['mysqldb']->query( "SELECT * FROM agent WHERE pid='' ORDER BY name" );
while ( $list = $agent->fetch_assoc() ) {
	$gAgent[$list['id']] = $list['name'];
}

// 期間指定---------------------------------------------------------------------------

$_POST['access_date']=$_POST['access_date'] ? $_POST['access_date'] : date("Y-m-d");
$_POST['access_date2']=$_POST['access_date2'] ? $_POST['access_date2'] : ($_POST['access_date'] ? $_POST['access_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['access_date']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['access_date']." +1day"));

// 検索条件の設定----------------------------------------------------------------------------
if($_POST['agent_id'] != "" ){
	$dWhere .= " and adcode.agent_id = '".$GLOBALS['mysqldb']->real_escape_string($_POST['agent_id'])."'";
}
if($_POST['type'] != "" ){
	$dWhere .= " and adcode.type = '".$GLOBALS['mysqldb']->real_escape_string($_POST['type'])."'";
}
/*
if($_POST['hide_flg'] != 2 ){
	$dWhere .= " and adcode.hide_flg = ".$GLOBALS['mysqldb']->real_escape_string($_POST['hide_flg'])." ";
}*/

//表示順設定----------------------------------------------------------------------------
if($_REQUEST['sort']=='agent') 	$order = "agent.name" ;
elseif($_REQUEST['sort']) 		$order = "adcode.". $_REQUEST['sort'];
else 							$order = "agent.name,adcode.name" ;
$order = " order by ".$order .$_REQUEST['seq'];

// データの取得,求人媒体以外経由は？----------------------------------------------------------------------------
$sql = "SELECT adcode.*,agent.name as agent FROM adcode,agent where adcode.agent_id=agent.id and adcode.job_flg=1 ".$dWhere.$order;
$adcode = $GLOBALS['mysqldb']->query( $sql );

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
	//$aAgent = Get_Table_Row("agent"," WHERE id = '".addslashes($list['agent_id'])."'");
	//$data[$i]['agent'] = $aAgent['name'];

	$data[$i]['agent'] = $list['agent'];

	
	//携帯別と申込件数合計をまとめ,5->1
	$data[$i]['total_top'] = 0;	
	// base: adcode -> id
	$rtn = $GLOBALS['mysqldb']->query("SELECT page_id,mo_agent,sum(count) as cnt FROM ".$table." WHERE page_id<=4 and adcode='".$list['id']."' and access_date >= '".$_POST['access_date']."' AND access_date <= '".$_POST['access_date2']."' GROUP BY adcode,page_id,mo_agent ORDER BY adcode,page_id,mo_agent ");
	if($rtn->num_rows >= 1){
		while($line = $rtn->fetch_assoc()){
			//端末別TOPページクリック数取得
			if($line['page_id']==1){
				$data[$i][$line['mo_agent']] = $line['cnt'];
				$data[$i]['total_top'] += $line['cnt'];
				$total[$line['mo_agent']] += $line['cnt'];
				$total['total_top'] += $line['cnt'];
			}
			//申込件数合計数取得
			if($line['page_id']==4){
				$data[$i]['reg_all'] += $line['cnt'];
				$total['reg_all'] += $line['cnt'];
			}
		}
	}

	//今月CV
	$rtn = $GLOBALS['mysqldb']->query("SELECT page_id,mo_agent,sum(count) as cnt FROM ".$table." WHERE page_id=4 and adcode='".$list['id']."' and access_date >= '".date("Y-m-01")."' AND access_date <= '".date("Y-m-t")."' GROUP BY adcode,page_id,mo_agent ORDER BY adcode,page_id,mo_agent ");
	if($rtn->num_rows >= 1){
		while($line = $rtn->fetch_assoc()){
			//申込件数合計数取得
			$data[$i]['reg_month'] += $line['cnt'];
			$total['reg_month'] += $line['cnt'];

		}
	}
	
	//本日と昨日をまとめ,2->1
	$yesterday = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));
	// base: adcode -> id
	$rtn = $GLOBALS['mysqldb']->query("SELECT access_date,sum(count) as cnt FROM ".$table." WHERE  page_id=4 and access_date>='".$yesterday."' and adcode='".$list['id']."' GROUP BY adcode,access_date ORDER BY adcode,access_date ");
	if($rtn->num_rows >= 1){

		while($line = $rtn->fetch_assoc()){
			if($line['access_date']==date("Y-m-d")){//本日空メール申込件数取得
				$data[$i]['reg_today'] = $line['cnt'];
				$total['reg_today'] += $line['cnt'];
			}
			if($line['access_date']==$yesterday){//昨日空メール申込件数取得
				$data[$i]['reg_yesterday'] = $line['cnt'];
				$total['reg_yesterday'] += $line['cnt'];
			}

		}
	}
	$i++;
}
//var_dump($data);
if($_REQUEST['access_date'])	$param = "&access_date=".$_REQUEST['access_date'];
if($_REQUEST['access_date2'])	$param .= "&access_date2=".$_REQUEST['access_date2'];
if($_REQUEST['agent_id'])		$param .= "&agent_id=".$_REQUEST['agent_id'];
if($_REQUEST['hide_flg'])		$param = "&hide_flg=".$_REQUEST['hide_flg'];

?>