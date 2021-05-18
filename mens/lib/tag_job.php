<?php
if(!defined('DOMAIN')){
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/mens/config/config.php';
require_once LIB_DIR . 'db.php';
	require_once LIB_DIR . 'function.php';
}

//tag list
$result  = $GLOBALS['mysqldb']->query( "select * from tag WHERE del_flg = 0 AND status=1 order by id" );
if($result){
	while ( $row = $result->fetch_assoc() ) {
		//設置範囲に優先、完了ページヘッダ内のタグが完了ページに別途設置
		if($row['coverage']==1) {
			if($row['adcode']){
				if($row['adcode'] == $_SESSION['MENS_AD_CODE']) $tag_conversion .= View_Cook_Html($row['tag']) ."\n"; //ASP重複カット
			}else $tag_conversion .= View_Cook_Html($row['tag']) ."\n"; // 完了ページ、BODY内
		}	
		elseif($row['coverage']==2) $tag_top .= View_Cook_Html($row['tag']) ."\n";		 // TOPページ、BODY終了タグ直前
		elseif($row['location']==2) $tag_head .= View_Cook_Html($row['tag']) ."\n";		// 全ページ、HEADタグ内
		elseif($row['location']==1) $tag_common2 .= View_Cook_Html($row['tag']) ."\n";	// BODY終了タグ直前
		elseif($row['location']==0) $tag_common1 .= View_Cook_Html($row['tag']) ."\n";	// BODY開始タグ直後
		
	}
}

//
if(!$_POST['mode']){
	
//参照元取得
if(!isset($_SESSION['KIREIMO_REFERER'])) $_SESSION['KIREIMO_REFERER']=$_SERVER['HTTP_REFERER'];

//ブログ名よりADコードに変換
if(!isset($_GET['adcode'])){
	$url = $_SERVER['HTTP_REFERER'];
	//$url = parse_url($url);
	$blog_sql = $GLOBALS['mysqldb']->query( "select adcode,name from adcode WHERE del_flg = 0 AND hide_flg=0 AND type=3 order by name" );
	while ( $result = $blog_sql->fetch_assoc() ) {
		//if(strstr($url['path'], $result['adcode'])){
		if(strstr($url, $result['adcode'])){
			$_GET['adcode'] = $result['adcode'];
			break;
		}
	}
}

//更新ボタン複数回クリックによる重複制御は？
if(isset($_GET['adcode'])){
	//存在しない広告コードを計上しない
	$_SESSION['MENS_AD_CODE'] = Get_Table_Col("adcode","id"," where adcode='{$_GET['adcode']}'");
	//トップページ集計
	IncrementAccessLog2(date('Y-m-d'), 1, $mo_agent, $_SESSION['AD_CODE']);
	//解析用
	if($page_id)IncrementAccessLog2(date('Y-m-d'), $page_id, $mo_agent,$_SESSION['AD_CODE']); 
}

}

?>