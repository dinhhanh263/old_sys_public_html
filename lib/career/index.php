<?php
session_start();

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
include_once( "../lib/tag_job.php" );
// include_once( "../lib/tag_job.php" );
// include_once( '../../lib/recruit_entry.php');

//スマートフォン用ページに切り替え
// if( !strstr( $_SERVER['SCRIPT_FILENAME'],"sp") && ($mo_agent==1 || $mo_agent==3))  header("Location: /sp/career/?adcode=".$_GET['adcode']);

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
    $_SESSION['AD_CODE'] = Get_Table_Col("adcode","id"," where adcode='{$_GET['adcode']}'");
    //トップページ集計
    IncrementAccessLog2(date('Y-m-d'), 1, $mo_agent, $_SESSION['AD_CODE']);
    //解析用
    if($page_id)IncrementAccessLog2(date('Y-m-d'), $page_id, $mo_agent,$_SESSION['AD_CODE']);
}

?>