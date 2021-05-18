<?php 
// 参照元取得
if(!isset($_SESSION['KIREIMO_REFERER'])) $_SESSION['KIREIMO_REFERER']=$_SERVER['HTTP_REFERER'];

// Link-Aのsid格納
if(isset($_GET['sid'])) $_SESSION['AFFILIATE_ID']=$_GET['sid'];

// ブログ名よりADコードに変換
if(!isset($_GET['adcode']) && !isset($_SESSION["PRE_AD_CODE"])){
	$url = $_SERVER['HTTP_REFERER'];
	// $url = parse_url($url);
	$blog_sql = $GLOBALS['mysqldb']->query( "select adcode,name from adcode WHERE del_flg = 0 AND hide_flg=0 AND type=3 order by name" );
	while ( $result = $blog_sql->fetch_assoc() ) {
		// if(strstr($url['path'], $result['adcode'])){
		if(strstr($url, $result['adcode'])){
			$_GET['adcode'] = $result['adcode'];
			break;
		}
	}
}

// １．ADCodeのセッション格納&クリック数計上。TW/FB対応用
if(!isset($_GET['adcode']) && isset($_SESSION["PRE_AD_CODE"])){
		
		// iframe内表示のlpにadcodeを格納する
		$_SESSION['AD_CODE'] = Get_Table_Col("adcode","id"," where adcode='{$_SESSION['PRE_AD_CODE']}'");
		
		// トップページ集計
		IncrementAccessLog(date('Y-m-d'), 1, $mo_agent, $_SESSION['AD_CODE']);
		
		// 解析用
		if($page_id)IncrementAccessLog(date('Y-m-d'), $page_id, $mo_agent,$_SESSION['AD_CODE']);
}

// ２．ADCodeのセッション格納&クリック数計上
if(isset($_GET['adcode'])){
	
	// 存在しない広告コードを計上しない
	$_SESSION['AD_CODE'] = Get_Table_Col("adcode","id"," where adcode='{$_GET['adcode']}'");
	
	// トップページ集計
	IncrementAccessLog(date('Y-m-d'), 1, $mo_agent, $_SESSION['AD_CODE']);
	
	// 解析用
	if($page_id)IncrementAccessLog(date('Y-m-d'), $page_id, $mo_agent,$_SESSION['AD_CODE']);
}

// クッキーの格納
setKireimoCookie();

// オーガニック解析
setOrganicCookie();

// 店舗リスト
$shop_list = getDatalist("shop");

// courseリスト
$course_list = getDatalist("course");

?>