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

//テーブル設定-------------
$table = "adcode";

//媒体表示------------------------------------------
$gHideFlag = array( 0 => "掲載中" , 1 => "停止済" );
//求人フラッグ------------------------------------------------------
$gJobFlag = array( 0 => "集客用" , 1 => "求人用"  , 2 => "無料用");

// データの仮削除---------------------------------------------------------------------
if( $_POST['action'] == "delete" && $_POST['id']){

	$sql = "UPDATE adcode SET del_flg = 1,edit_date='".date('Y-m-d H:i:s')."'";
	$sql .= " WHERE id = '".addslashes($_REQUEST['id'])."'";
	$dRes = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);

	if( $dRes ){
		$gMsg = 'データの削除が完了しました。';
	}else{
		$gMsg = '何も削除しませんでした。';
	}
}
if($_POST['action'] == "new" || $_POST['action'] == "update" && $_POST['id']){

	if(!$_POST['adcode']) {
		$gMsg .= "<font color='red' size='-1'>※広告コードを入力してください。</font><br>";
	}elseif($_POST['action'] == "new" && Get_Table_Row("adcode"," WHERE adcode = '".addslashes($_POST['adcode'])."'")) {
		$gMsg .= "<font color='red' size='-1'>※当広告コードが既に使われています。</font><br>";
	}elseif( $_POST['action'] == "update" && Get_Table_Row("adcode"," WHERE del_flg=0 and  id <> ".$_POST['id']." and adcode = '".addslashes($_POST['adcode'])."'")) {
		$gMsg .= "<font color='red' size='-1'>※当広告コードが既に使われています。</font><br>";
	}elseif(!$_POST['name']) {
		$gMsg .= "<font color='red' size='-1'>※媒体名を入力してください。</font><br>";
	}elseif($_POST['action'] == "new" && Get_Table_Row("adcode"," WHERE name = '".addslashes($_POST['name'])."'")) {
		$gMsg .= "<font color='red' size='-1'>※当媒体名が既に使われています。</font><br>";
	}elseif($_POST['action'] == "update" && Get_Table_Row("adcode"," WHERE del_flg=0 and id <> ".$_POST['id']." and name = '".addslashes($_POST['name'])."'")) {
		$gMsg .= "<font color='red' size='-1'>※当媒体名が既に使われています。</font><br>";
	}elseif(!$_POST['ad_group']) {
		$gMsg .= "<font color='red' size='-1'>※グループを選択してください。</font><br>";
	}elseif(!$_POST['request_id']) {
		$gMsg .= "<font color='red' size='-1'>※請求媒体を選択してください。</font><br>";
	}elseif(!$_POST['agent_id']) {
		$gMsg .= "<font color='red' size='-1'>※代理店を選択してください。</font><br>";
	}else{
		if($_POST['page_name']){
			$_POST['page_id'] = Get_Table_Col("item_landing","id"," where name='".addslashes($_POST['page_name'])."'");
		}

		// データの新規登録----------------------------------------------------------------
		if( $_POST['action'] == "new" )	{
			if($_POST['type']==4) mkdir_file($_POST['adcode'],$_POST['page_name']);
			$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");
			//insert to DB
			Input_New_Adcode();
		}
		// データの変更------------------------------------------------------------------
		if( $_POST['action'] == "update" && $_POST['id']) {
			$_POST['edit_date'] = date("Y-m-d H:i:s");
			if($_POST['type']==4) mkdir_file($_POST['adcode'],$_POST['page_name']);
			//update DB
			Input_Update_Adcode();
		}
		//ランディングページ名をDBに追加
		if($_POST['page_name'] && !Get_Table_Row("item_landing"," WHERE name = '".addslashes($_POST['page_name'])."'")){
			$GLOBALS['mysqldb']->query("insert  item_landing set name = '".addslashes($_POST['page_name'])."'") or die('query error'.$GLOBALS['mysqldb']->error);
		}


	}
}


//代理店リスト----------------------------------------------------------------
$agent = $GLOBALS['mysqldb']->query( "SELECT * FROM agent WHERE del_flg=0 and pid='' ORDER BY name" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $list = $agent->fetch_assoc() ) {
	$gAgent[$list['id']] = $list['name'];
}


// 表示ページ設定-------------------------------------------------------------------------------------
$dStart = 0;
$dLine_Max = $_REQUEST['line_max'] ? $_REQUEST['line_max'] : 30;
if( is_numeric( $_REQUEST['start'] ) && $_REQUEST['start'] >= 0 && $_REQUEST['start'] < 99999 ){
	$dStart = $_REQUEST['start'];
}

// 検索条件の設定------------------------------------------------------------------
$dWhere = " WHERE del_flg=0";
if( $_POST['keyword'] != "" ){
	$dWhere .= " and (";
	$dWhere .= "  id ='".addslashes( $_POST['keyword'] )."'";
	$dWhere .= "  or name LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= "  or adcode LIKE '%".addslashes( $_POST['keyword'] )."%'";
	//$dWhere .= "  or agent_id LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= "  or release_date LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " )";
}
if($_POST['daili_id'] != "" ){
	$dWhere .= ( $_POST['keyword'] != "" ) ? " AND " : " WHERE ";
	$dWhere .= "  agent_id = '".$GLOBALS['mysqldb']->real_escape_string($_POST['daili_id'])."'";
}

// データの取得
$dSql = "SELECT count(*) FROM " . $table." WHERE del_flg=0";
$dRtn1 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dAll_Cnt = $dRtn1->fetch_row();

$dSql = "SELECT count(id) FROM " . $table . $dWhere;
$dRtn2 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dGet_Cnt = $dRtn2->fetch_row()[0];

$dSql = "SELECT * FROM " . $table . $dWhere." ORDER BY id DESC LIMIT ".$dStart.",".$dLine_Max;
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//ランディングリスト作成
$item_landing = $GLOBALS['mysqldb']->query( "SELECT * FROM item_landing where del_flg=0 order by ldp_name" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $list = $item_landing->fetch_assoc() ) $item_landing_list[$list['name']] = $list['ldp_name'];
if(is_array($item_landing_list))$landing_name_list = implode(";", $item_landing_list);


//create folder/file------------------------------------------------------------------------------------
function mkdir_file($adcode,$page_name){

	$path = '../../lp/'.$adcode;

	if (is_dir($path) || !mkdir($path, 0775)) {
    	return false;
	}else{
		$output_path = $path.'/index.html';
		$html_data = '
<?php
// uaによる振り分け
  $ua = $_SERVER["HTTP_USER_AGENT"];
if ((strpos($ua, "Android") !== false) && (strpos($ua, "Mobile") !== false) || (strpos($ua, "iPhone") !== false) || (strpos($ua, "Windows Phone") !== false)) {
  forsp();
  exit();
};
  function forsp(){
    include_once("../../lib/lp_common_sp.php");
    $adcodeUrl = $_SERVER["REQUEST_URI"];//現在のURL取得
    $patternUrl = "/\/lp\/.*?\//";
    $thiscode = preg_match($patternUrl,$adcodeUrl,$matches);//[/lp/]を検索
    // if($thiscode == 1){
        $pattern = array();
        $pattern[0] = "/lp/";
        $pattern[1] = "/\//";
        $thiscode = preg_replace($pattern,"",$matches[0]);//adcode部分のみ切り取り
//        session_save_path("../tmp");
        session_start();
        $_SESSION["PRE_AD_CODE"] = $thiscode;
       // $replaceUrl = "http://demo3.kireimo.biz/";
      // };

    //iframeで読み込む予定のurlを取得する
    $getHtml = file_get_contents("index.html");
    $iframePart = explode("\n", $getHtml);
    $iframeNo = key(preg_grep("/id=\"frame\"/",$iframePart));
    $targetUrl = strstr($iframePart[$iframeNo],"src=\"");
    $getUrl = explode("\"",$targetUrl);
    $target = explode("?",$getUrl[1]);

    //読み込み先のurlファイルを取得する
    $originUrl =$target[0]."sp/index.html";
    $dot ="../";
    $url = str_replace($dot, "", $target[0])."sp/index.html";
    $replaceUrlSp= $target[0]."sp";
    $replacesrc = "src=\"./img";
    $contents = "";
    ob_start();//読み込み先のurlファイル出力のバッファリングを有効にする
    include_once($originUrl);
    $contents = ob_get_contents();//読み込み先のurlファイルのバッファを取得,変数に代入
    $contents = str_replace($replacesrc, "src=\"".$replaceUrlSp."/img", $contents);
    ob_end_clean();//出力バッファリングを終了
    echo $contents;
  };
?>
<html>
<head>
<meta name="robots" content="noindex,nofollow">
<meta name="viewport" content="height=device-height,width=device-width,initial-scale=1">
<link rel="shortcut icon" href="../../img/favicon.ico" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

<style type="text/css" media="all">
html{
  overflow:hidden;
}
body{
  margin:0;
  padding:0;
}
#frame{
  height:100%;
  width:100%;
}
</style></head>
<body onContextmenu="return false">
<div class="clWrap">
<iframe id="frame" src="../../'.$page_name.'/?adcode='.$adcode.'" frameborder="0" ></iframe>
</div><!--/clWrap-->
<script type="text/javascript">
(function($){
  $("document").ready(function(){
      resizef();
  });
  window.onresize = resizef;
  function resizef(){
    var frame,ihref;
    frame = document.getElementById("frame"),
    frame.style.cssText = ";height:" + (window.innerHeight ||document.documentElement.clientHeight) + "px;";
  };
})(jQuery);
</script>
</body></html>

		';

		//ファイル書き出し処理
		$handle = fopen($output_path, "w");
		fwrite($handle, $html_data);
		fclose($handle);

		return true;
	}
}
?>