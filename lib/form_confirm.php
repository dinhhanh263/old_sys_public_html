<?php
if (strstr( $_SERVER['SCRIPT_FILENAME'],'/sp/')) {
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/config/config.php';
require_once LIB_DIR . 'db.php';
require_once LIB_DIR . 'function.php';
	include_once( "../../lib/tag_job.php" );
}else{
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/config/config.php';
require_once LIB_DIR . 'db.php';
	require_once LIB_DIR . 'function.php';
	include_once( "../lib/tag_job.php" );
}
$errmsg = array();

	if($_GET['name']) 		$_GET['name'] 		= str_replace(" ", "　", $_GET['name']);//半角スペースを全角スペースに統一
	if($_GET['name']) 		$_GET['name'] 		= str_replace("　　", "　", $_GET['name']);//2スペースを1スペースに統一
	if($_GET['name']) 		$_GET['name'] 		= preg_replace('/^[ 　]+/u', '', $_GET['name']);//前全角スペース削除
	if($_GET['name']) 		$_GET['name'] 		= preg_replace('/[ 　]+$/u', '', $_GET['name']);//前全角スペース削除

	if($_GET['name_kana']) $_GET['name_kana'] = str_replace(" ", "　", trim($_GET['name_kana']));//半角スペースを全角スペースに統一
	if($_GET['name_kana'])	$_GET['name_kana'] = str_replace("　　", "　", trim($_GET['name_kana']));//2スペースを1スペースに統一
	if($_GET['name_kana'])	$_GET['name_kana'] = preg_replace('/^[ 　]+/u', '', $_GET['name_kana']);//前全角スペース削除
	if($_GET['name_kana']) $_GET['name_kana'] = preg_replace('/[ 　]+$/u', '', $_GET['name_kana']);//前全角スペース削除
	
	//電話番号整形
	//$_GET['tel'] = sepalate_tel($_GET['tel']);

	//生年月日確認
	if($_GET['birthday_y'] && $_GET['birthday_m'] && $_GET['birthday_d']) $_GET['birthday'] ="あり";

	$_GET['persons'] = $_GET['nunzu'] ; 

	foreach( $gItems as $key => $value ){
		$_GET[$key] = preg_replace('/＼/','ー',$_GET[$key]);
		if($value['type']!="checkbox") $_GET[$key] = htmlspecialchars($_GET[$key]);
		if (get_magic_quotes_gpc())  $_GET[$key] = stripslashes($_GET[$key]);

		//必須項目チェック	
		if($value['exist_check']){
			if($_GET[$key] =="" || preg_match("/^( |　)+$/", $_GET[$key])){
			 	$errmsg[$key] = $value['name']."を入力してください";
			} else {
				//お名前（全角文字)
				if(preg_match("/name/", $key)) {
					if (!strpos($_GET[$key], "　")){
						$errmsg[$key] = "姓と名の間にスペースを入れてください";
					}
				}
				//お名前(全角カナ)
				elseif(preg_match("/name_kana/", $key)) {
					if (!preg_match("/^([　 \t\r\n]|[ァ-ヶー])+$/u", $_GET[$key])){
						$errmsg[$key] = "全角カタカナを入力してください";
					}
				} 
				//電話番号
				elseif(preg_match("/tel/", $key)) {
					$tel = sepalate_tel($_GET['tel']);
					if($tel != 0) {
						$_GET['tel'] = $tel;
					} else {
						$errmsg['tel'] = "電話番号が正しくありません";
					}
				}
				//メールアドレス
				elseif(preg_match("/mail/", $key)) { 
					$rec = Check_Email2($_GET["mail"]);

					if($rec["flg"] == 1) {
						$errmsg["mail"] = $rec["error"];
					} 
				}
			}
		}

	}


header('Content-type: application/json; charset=UTF-8');
echo json_encode($errmsg);
?>