<?php
if(!defined('DOMAIN')){
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
	//setKireimoCookie();//入口はキャンペーンページ等の場合　20151021 12:25
}

$sess_ad_code = empty($_SESSION['AD_CODE']) ? null : $_SESSION['AD_CODE'];

// tag list
$result  = $GLOBALS['mysqldb']->query( "select * from tag WHERE del_flg = 0 AND status=1 order by id" );
if($result){
	while ( $row = $result->fetch_assoc() ) {
		
		//設置範囲に優先、完了ページヘッダ内のタグが完了ページに別途設置
		if($row['coverage']==1) {
			if($row['adcode']){
				//ASP重複カット
				if($row['adcode'] == $sess_ad_code) $tag_conversion .= View_Cook_Html($row['tag']) ."\n"; 
			// 完了ページ、BODY内
			}else $tag_conversion .= View_Cook_Html($row['tag']) ."\n"; 
		}	
		// TOPページ、BODY終了タグ直前
		elseif($row['coverage']==2) $tag_top .= View_Cook_Html($row['tag']) ."\n";
		// 全ページ、HEADタグ内	 
		elseif($row['location']==2) $tag_head .= View_Cook_Html($row['tag']) ."\n";		
		// BODY終了タグ直前
		elseif($row['location']==1) $tag_common2 .= View_Cook_Html($row['tag']) ."\n";	
		// BODY開始タグ直後
		elseif($row['location']==0) $tag_common1 .= View_Cook_Html($row['tag']) ."\n";	
	}
}

// 予約フォーム確認画面：エラーチェック
$post_mode = empty($_POST['mode']) ? null : $_POST['mode'];

if($post_mode == "conf"){
	
	// セッション使ってると戻るボタンで戻った時にフォームの内容が消えてしまう問題
	// session_cache_limiter(’none’);

	// 半角スペースを全角スペースに統一
	if($_POST['name']) 		$_POST['name'] 		= str_replace(" ", "　", $_POST['name']);

	// 2スペースを1スペースに統一
	if($_POST['name']) 		$_POST['name'] 		= str_replace("　　", "　", $_POST['name']);

	// 前全角スペース削除
	if($_POST['name']) 		$_POST['name'] 		= preg_replace('/^[ 　]+/u', '', $_POST['name']);

	// 後全角スペース削除
	if($_POST['name']) 		$_POST['name'] 		= preg_replace('/[ 　]+$/u', '', $_POST['name']);

	// 半角スペースを全角スペースに統一
	if($_POST['name_kana']) $_POST['name_kana'] = str_replace(" ", "　", trim($_POST['name_kana']));

	// 2スペースを1スペースに統一
	if($_POST['name_kana'])	$_POST['name_kana'] = str_replace("　　", "　", trim($_POST['name_kana']));

	// 前全角スペース削除
	if($_POST['name_kana'])	$_POST['name_kana'] = preg_replace('/^[ 　]+/u', '', $_POST['name_kana']);

	// 後全角スペース削除
	if($_POST['name_kana']) $_POST['name_kana'] = preg_replace('/[ 　]+$/u', '', $_POST['name_kana']);

	// 半角スペースを全角スペースに統一
	if($_POST['pair_name_kana']) $_POST['pair_name_kana'] = str_replace(" ", "　", trim($_POST['pair_name_kana']));

	// 2スペースを1スペースに統一
	if($_POST['pair_name_kana']) $_POST['pair_name_kana'] = str_replace("　　", "　", trim($_POST['pair_name_kana']));

	// 前全角スペース削除
	if($_POST['pair_name_kana']) $_POST['pair_name_kana'] = preg_replace('/^[ 　]+/u', '', $_POST['pair_name_kana']);

	// 後全角スペース削除
	if($_POST['pair_name_kana']) $_POST['pair_name_kana'] = preg_replace('/[ 　]+$/u', '', $_POST['pair_name_kana']);
	
	// 電話番号整形 comment out by ka 20170508
	// $_POST['tel'] = sepalate_tel($_POST['tel']);
	// $pair_tel = sepalate_tel($_POST['pair_tel']);
	// $_POST['pair_tel'] = $pair_tel ? $pair_tel : $_POST['pair_tel'];

	// 生年月日確認
	$_POST['birthday'] = ($_POST['birthday_y'] && $_POST['birthday_m'] && $_POST['birthday_d'])  ? "あり" : "";

	$_POST['persons'] = $_POST['nunzu'] ; 

	foreach( $gItems as $key => $value ){
		$_POST[$key] = preg_replace('/＼/','ー',$_POST[$key]);
		if($value['type']!="checkbox") $_POST[$key] = htmlspecialchars($_POST[$key]);
		if (get_magic_quotes_gpc())  $_POST[$key] = stripslashes($_POST[$key]);

		// 必須項目チェック	
		if($value['exist_check']){
			if($_POST[$key] =="" || preg_match("/^( |　)+$/", $_POST[$key])){
			 	$errmsg[$key] = $value['name']."を入力してください";
			} else {
				// お名前（全角文字)
				if(preg_match("/name/", $key)) {
					if (!strpos($_POST[$key], "　")){
						$errmsg[$key] = "姓と名の間にスペースを入れてください";
					}
				}
				// お名前(全角カナ)
				elseif(preg_match("/name_kana/", $key)) {
					if (!preg_match("/^([　 \t\r\n]|[ァ-ヶー])+$/u", $_POST[$key])){
						$errmsg[$key] = "全角カタカナを入力してください";
					}
				} 
				// 電話番号
				elseif(preg_match("/tel/", $key)) {
					$tel = sepalate_tel($_POST['tel']);
					if($tel != 0) {
						$_POST['tel'] = $tel;
					} else {
						$errmsg['tel'] = "電話番号が正しくありません";
					}
				}
				// メールアドレス
				elseif(preg_match("/mail/", $key)) { 
					$rec = Check_Email2($_POST["mail"]);

					if($rec["flg"] == 1) {
						$errmsg["mail"] = $rec["error"];
					} 
				}
			}
		}
		if($errmsg[$key]) $errmsg[$key] = "<span class='error'>".$errmsg[$key]."</span>";

	}

	if(!$_POST['doui']) $errmsg['doui'] = "<div style='text-align:center;padding-top:20px;'><span class='error' >※プライバシーポリシーをご同意ください</span></div>";

	//$_POST['hope_time'] ='17:00';
	//var_dump($_POST['hope_time'] );

	// 予約時に、予約時間を超えてしまったかを確認
	if($_POST['hope_date'] && $_POST['hope_time'] ){
		// $hope_date_time = $_POST['hope_date']." ".$gTime2[$_POST['hope_time']];
		$hope_date_time = str_replace("/","-",$_POST['hope_date'])." ".$_POST['hope_time'];
		//var_dump($hope_date_time  );
		if($hope_date_time < date('Y-m-d H:i')){
			$errmsg['hope_date_time'] = "<span class='error'>申し訳ございません。ご予約時間の時間を過ぎているため、ご予約は行えません。</span>";
		}
	}

}

?>